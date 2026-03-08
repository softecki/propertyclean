<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UnifiedPmsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        parent::setUp();
    }

    public function test_overpayment_creates_customer_credit_balance(): void
    {
        $projectId = DB::table('projects')->insertGetId([
            'parent_id' => 1,
            'name' => 'Project A',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $blockId = DB::table('blocks')->insertGetId([
            'project_id' => $projectId,
            'name' => 'Block A',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $plotId = DB::table('plots')->insertGetId([
            'parent_id' => 1,
            'block_id' => $blockId,
            'plot_number' => 'A-01',
            'sale_price' => 1000,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $customerId = DB::table('customers')->insertGetId([
            'parent_id' => 1,
            'type' => 'buyer',
            'name' => 'Buyer One',
            'credit_balance' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sale = $this->postJson('/api/v1/sales', [
            'project_id' => $projectId,
            'block_id' => $blockId,
            'plot_id' => $plotId,
            'customer_id' => $customerId,
            'sale_price' => 1000,
        ])->assertCreated()->json('data');

        $saleId = $sale['sale_id'];

        $this->postJson("/api/v1/sales/{$saleId}/payments", [
            'amount' => 1200,
            'payment_method' => 'bank',
        ])->assertOk();

        $this->assertDatabaseHas('customer_credits', [
            'sale_id' => $saleId,
            'customer_id' => $customerId,
            'amount' => 200.00,
        ]);
    }

    public function test_commission_is_created_when_sale_is_confirmed(): void
    {
        $projectId = DB::table('projects')->insertGetId([
            'parent_id' => 1,
            'name' => 'Project B',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $blockId = DB::table('blocks')->insertGetId([
            'project_id' => $projectId,
            'name' => 'Block B',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $plotId = DB::table('plots')->insertGetId([
            'parent_id' => 1,
            'block_id' => $blockId,
            'plot_number' => 'B-01',
            'sale_price' => 1000,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $customerId = DB::table('customers')->insertGetId([
            'parent_id' => 1,
            'type' => 'buyer',
            'name' => 'Buyer Two',
            'credit_balance' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $agentId = DB::table('agents')->insertGetId([
            'parent_id' => 1,
            'name' => 'Agent One',
            'commission_type' => 'percentage',
            'commission_value' => 10,
            'commission_trigger' => 'sale_confirmed',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sale = $this->postJson('/api/v1/sales', [
            'project_id' => $projectId,
            'block_id' => $blockId,
            'plot_id' => $plotId,
            'customer_id' => $customerId,
            'agent_id' => $agentId,
            'sale_price' => 1000,
        ])->assertCreated()->json('data');

        $saleId = $sale['sale_id'];
        $this->postJson("/api/v1/sales/{$saleId}/status", [
            'status' => 'confirmed',
        ])->assertOk();

        $this->assertDatabaseHas('commissions', [
            'sale_id' => $saleId,
            'agent_id' => $agentId,
            'commission_amount' => 100.00,
            'status' => 'approved',
        ]);
    }

    public function test_asset_depreciation_updates_book_value(): void
    {
        $assetId = $this->postJson('/api/v1/assets', [
            'name' => 'Generator',
            'acquisition_date' => '2026-01-01',
            'cost' => 1200,
            'salvage_value' => 0,
            'useful_life_years' => 2,
        ])->assertCreated()->json('id');

        $this->postJson("/api/v1/assets/{$assetId}/depreciate", [
            'period_start' => '2026-01-01',
            'period_end' => '2026-12-31',
        ])->assertOk();

        $asset = DB::table('asset_registers')->where('id', $assetId)->first();
        $this->assertEquals(600.00, (float) $asset->book_value);
    }

    public function test_portal_listings_returns_available_plot(): void
    {
        $projectId = DB::table('projects')->insertGetId([
            'parent_id' => 1,
            'name' => 'Project C',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $blockId = DB::table('blocks')->insertGetId([
            'project_id' => $projectId,
            'name' => 'Block C',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('plots')->insert([
            'parent_id' => 1,
            'block_id' => $blockId,
            'plot_number' => 'C-01',
            'sale_price' => 1000,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/portal/listings')->assertOk()->json();
        $this->assertNotEmpty($response['plots']);
    }
}
