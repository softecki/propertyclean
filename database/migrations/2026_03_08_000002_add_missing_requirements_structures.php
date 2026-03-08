<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('blocks') && !Schema::hasColumn('blocks', 'parent_id')) {
            Schema::table('blocks', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_id')->default(1)->after('id');
            });
        }

        Schema::create('land_rate_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('property_id');
            $table->decimal('amount', 14, 2);
            $table->date('payment_date');
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('integration_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('integration_type');
            $table->string('provider')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('status')->default('pending');
            $table->text('message')->nullable();
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('tenant_documents')) {
            Schema::table('tenant_documents', function (Blueprint $table) {
                if (!Schema::hasColumn('tenant_documents', 'bank_statement')) {
                    $table->string('bank_statement')->nullable()->after('document');
                }
                if (!Schema::hasColumn('tenant_documents', 'previous_lease_contract')) {
                    $table->string('previous_lease_contract')->nullable()->after('bank_statement');
                }
                if (!Schema::hasColumn('tenant_documents', 'memorandum_of_association')) {
                    $table->string('memorandum_of_association')->nullable()->after('previous_lease_contract');
                }
                if (!Schema::hasColumn('tenant_documents', 'trading_license')) {
                    $table->string('trading_license')->nullable()->after('memorandum_of_association');
                }
                if (!Schema::hasColumn('tenant_documents', 'application_flow_document')) {
                    $table->string('application_flow_document')->nullable()->after('trading_license');
                }
            });
        }

        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (!Schema::hasColumn('tenants', 'application_status')) {
                    $table->string('application_status')->default('new')->after('lease_end_date');
                }
                if (!Schema::hasColumn('tenants', 'verification_status')) {
                    $table->string('verification_status')->default('pending')->after('application_status');
                }
                if (!Schema::hasColumn('tenants', 'approval_status')) {
                    $table->string('approval_status')->default('pending')->after('verification_status');
                }
                if (!Schema::hasColumn('tenants', 'verified_by')) {
                    $table->unsignedBigInteger('verified_by')->nullable()->after('approval_status');
                }
                if (!Schema::hasColumn('tenants', 'approved_by')) {
                    $table->unsignedBigInteger('approved_by')->nullable()->after('verified_by');
                }
                if (!Schema::hasColumn('tenants', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable()->after('approved_by');
                }
                if (!Schema::hasColumn('tenants', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('verified_at');
                }
                if (!Schema::hasColumn('tenants', 'application_notes')) {
                    $table->text('application_notes')->nullable()->after('approved_at');
                }
            });
        }

        if (Schema::hasTable('contracts')) {
            $driver = DB::connection()->getDriverName();
            if ($driver !== 'sqlite') {
                try {
                    DB::statement('ALTER TABLE contracts DROP INDEX contracts_lease_tenure_unique');
                } catch (\Throwable $e) {
                    // Index may not exist on some deployments.
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                $dropColumns = [];
                foreach (['application_status', 'verification_status', 'approval_status', 'verified_by', 'approved_by', 'verified_at', 'approved_at', 'application_notes'] as $column) {
                    if (Schema::hasColumn('tenants', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('tenant_documents')) {
            Schema::table('tenant_documents', function (Blueprint $table) {
                $dropColumns = [];
                foreach (['bank_statement', 'previous_lease_contract', 'memorandum_of_association', 'trading_license', 'application_flow_document'] as $column) {
                    if (Schema::hasColumn('tenant_documents', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('blocks') && Schema::hasColumn('blocks', 'parent_id')) {
            Schema::table('blocks', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }

        Schema::dropIfExists('integration_logs');
        Schema::dropIfExists('land_rate_payments');
    }
};
