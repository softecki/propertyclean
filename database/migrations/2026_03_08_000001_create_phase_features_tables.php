<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('block_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('plot_number');
            $table->string('title_deed_no')->nullable();
            $table->decimal('size_sqm', 14, 2)->nullable();
            $table->decimal('sale_price', 14, 2)->default(0);
            $table->decimal('rental_price', 14, 2)->default(0);
            $table->string('status')->default('available');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('type')->default('buyer');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('tin')->nullable();
            $table->string('taxpayer_identification_number')->nullable();
            $table->string('id_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('credit_balance', 14, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('ownership_reference')->nullable();
            $table->decimal('agreed_amount', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('amount_remaining', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('commission_type')->default('percentage');
            $table->decimal('commission_value', 14, 4)->default(0);
            $table->string('commission_trigger')->default('sale_confirmed');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('block_id')->nullable();
            $table->unsignedBigInteger('plot_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('agreement_no')->nullable()->unique();
            $table->string('status')->default('draft');
            $table->string('currency_code')->default('TZS');
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->decimal('sale_price', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('penalty_interest_rate', 8, 4)->default(0);
            $table->decimal('extra_charge_total', 14, 2)->default(0);
            $table->decimal('total_contract_value', 14, 2)->default(0);
            $table->decimal('total_paid', 14, 2)->default(0);
            $table->decimal('outstanding_balance', 14, 2)->default(0);
            $table->decimal('recognized_revenue', 14, 2)->default(0);
            $table->decimal('deferred_revenue', 14, 2)->default(0);
            $table->string('commission_rule_type')->nullable();
            $table->decimal('commission_rule_value', 14, 4)->nullable();
            $table->string('commission_trigger')->default('sale_confirmed');
            $table->date('sale_date')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->date('due_date');
            $table->decimal('amount', 14, 2);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->string('status')->default('due');
            $table->timestamps();
        });

        Schema::create('sale_extra_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->string('charge_type');
            $table->string('description')->nullable();
            $table->decimal('amount', 14, 2);
            $table->unsignedBigInteger('added_by')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('bank_control_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->string('bank_name')->nullable();
            $table->string('control_number')->unique();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency_code')->default('TZS');
            $table->string('status')->default('generated');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('control_number_id')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('bank_reference')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('amount', 14, 2);
            $table->string('currency_code')->default('TZS');
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('source_payment_id')->nullable();
            $table->decimal('amount', 14, 2);
            $table->decimal('balance', 14, 2);
            $table->string('action')->default('credit');
            $table->string('status')->default('open');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('expected_amount', 14, 2)->default(0);
            $table->decimal('received_amount', 14, 2)->default(0);
            $table->decimal('balance', 14, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });

        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('agent_id');
            $table->string('rule_type');
            $table->decimal('rule_value', 14, 4);
            $table->decimal('base_amount', 14, 2)->default(0);
            $table->decimal('commission_amount', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamp('recognized_at')->nullable();
            $table->timestamp('payable_at')->nullable();
            $table->timestamps();
        });

        Schema::create('commission_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commission_id');
            $table->decimal('amount', 14, 2);
            $table->date('payment_date');
            $table->string('method')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('accrual_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->date('entry_date');
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->string('account_code');
            $table->string('entry_type');
            $table->decimal('amount', 14, 2);
            $table->string('currency_code')->default('TZS');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('asset_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('category')->nullable();
            $table->date('acquisition_date');
            $table->decimal('cost', 14, 2);
            $table->decimal('salvage_value', 14, 2)->default(0);
            $table->integer('useful_life_years')->default(1);
            $table->string('method')->default('straight_line');
            $table->decimal('accumulated_depreciation', 14, 2)->default(0);
            $table->decimal('book_value', 14, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('depreciation_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_register_id');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('depreciation_amount', 14, 2);
            $table->decimal('accumulated_depreciation', 14, 2);
            $table->decimal('book_value', 14, 2);
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('email_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('subject')->nullable();
            $table->string('channel')->default('email');
            $table->string('linked_type')->nullable();
            $table->unsignedBigInteger('linked_id')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });

        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_thread_id');
            $table->string('direction');
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            $table->text('cc_addresses')->nullable();
            $table->text('bcc_addresses')->nullable();
            $table->string('message_id')->nullable();
            $table->longText('body');
            $table->json('attachments')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->string('channel')->default('email');
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('pending');
            $table->json('context')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('property_unit_id')->nullable();
            $table->string('service_type');
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date');
            $table->decimal('cost_estimate', 14, 2)->default(0);
            $table->string('currency_code')->default('TZS');
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });

        Schema::create('property_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('message');
            $table->string('status')->default('new');
            $table->timestamps();
        });

        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_id')->nullable();
            $table->string('bill_type');
            $table->string('provider')->nullable();
            $table->string('period')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency_code')->default('TZS');
            $table->string('status')->default('pending');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->timestamps();
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency', 3)->default('TZS');
            $table->string('quote_currency', 3);
            $table->decimal('rate', 18, 6);
            $table->date('effective_date');
            $table->timestamps();
        });

        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                if (!Schema::hasColumn('properties', 'plot_no')) {
                    $table->string('plot_no')->nullable()->after('type');
                }
                if (!Schema::hasColumn('properties', 'title_deed_no')) {
                    $table->string('title_deed_no')->nullable()->after('plot_no');
                }
                if (!Schema::hasColumn('properties', 'property_size')) {
                    $table->decimal('property_size', 14, 2)->nullable()->after('title_deed_no');
                }
                if (!Schema::hasColumn('properties', 'rental_space_count')) {
                    $table->integer('rental_space_count')->default(0)->after('property_size');
                }
                if (!Schema::hasColumn('properties', 'occupancy_status')) {
                    $table->string('occupancy_status')->default('available')->after('rental_space_count');
                }
                if (!Schema::hasColumn('properties', 'land_rates_paid')) {
                    $table->decimal('land_rates_paid', 14, 2)->default(0)->after('occupancy_status');
                }
            });
        }

        if (Schema::hasTable('property_units')) {
            Schema::table('property_units', function (Blueprint $table) {
                if (!Schema::hasColumn('property_units', 'size_sqm')) {
                    $table->decimal('size_sqm', 14, 2)->nullable()->after('name');
                }
                if (!Schema::hasColumn('property_units', 'status')) {
                    $table->string('status')->default('vacant')->after('size_sqm');
                }
                if (!Schema::hasColumn('property_units', 'last_maintenance_date')) {
                    $table->date('last_maintenance_date')->nullable()->after('status');
                }
                if (!Schema::hasColumn('property_units', 'next_maintenance_date')) {
                    $table->date('next_maintenance_date')->nullable()->after('last_maintenance_date');
                }
            });
        }

        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (!Schema::hasColumn('contracts', 'lease_start_date')) {
                    $table->date('lease_start_date')->nullable()->after('tenant_id');
                }
                if (!Schema::hasColumn('contracts', 'lease_end_date')) {
                    $table->date('lease_end_date')->nullable()->after('lease_start_date');
                }
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'currency_code')) {
                    $table->string('currency_code', 3)->default('TZS')->after('unit_id');
                }
                if (!Schema::hasColumn('invoices', 'exchange_rate')) {
                    $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency_code');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('currency_rates');
        Schema::dropIfExists('utility_bills');
        Schema::dropIfExists('property_feedback');
        Schema::dropIfExists('maintenance_schedules');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('email_messages');
        Schema::dropIfExists('email_threads');
        Schema::dropIfExists('depreciation_entries');
        Schema::dropIfExists('asset_registers');
        Schema::dropIfExists('accrual_entries');
        Schema::dropIfExists('commission_payments');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('receivables');
        Schema::dropIfExists('customer_credits');
        Schema::dropIfExists('sale_payments');
        Schema::dropIfExists('bank_control_numbers');
        Schema::dropIfExists('sale_extra_charges');
        Schema::dropIfExists('sale_installments');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('plots');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('branches');

        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                $dropColumns = [];
                foreach (['plot_no', 'title_deed_no', 'property_size', 'rental_space_count', 'occupancy_status', 'land_rates_paid'] as $column) {
                    if (Schema::hasColumn('properties', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('property_units')) {
            Schema::table('property_units', function (Blueprint $table) {
                $dropColumns = [];
                foreach (['size_sqm', 'status', 'last_maintenance_date', 'next_maintenance_date'] as $column) {
                    if (Schema::hasColumn('property_units', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                $dropColumns = [];
                foreach (['lease_start_date', 'lease_end_date'] as $column) {
                    if (Schema::hasColumn('contracts', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $dropColumns = [];
                foreach (['currency_code', 'exchange_rate'] as $column) {
                    if (Schema::hasColumn('invoices', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }
    }
};
