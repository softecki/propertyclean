<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('tenant_id')->default(0);
            $table->string('lease_tenure')->unique();
            $table->float('amount')->default(0);
            $table->float('amount_paid')->default(0);
            $table->float('amount_remained')->default(0);
            $table->string('lease_terms')->nullable();
            $table->string('lease_rate')->nullable();
            $table->string('increments')->nullable();
            $table->string('payment_cycle',100)->nullable();
            $table->string('penalty',10)->nullable();
            $table->string('discount',10)->nullable();
            $table->string('contract_status',10)->nullable();
            $table->string('status',10)->nullable();
            $table->string('payment_date',10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
