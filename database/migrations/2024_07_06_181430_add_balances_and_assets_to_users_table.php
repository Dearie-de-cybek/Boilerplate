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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('usdt_balance', 15, 2)->default(0.00);
            $table->decimal('eth_balance', 15, 2)->default(0.00);
            $table->decimal('btc_balance', 15, 2)->default(0.00);
            $table->string('type')->nullable();
            $table->string('assets')->nullable();
            $table->decimal('price', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['usdt_balance', 'eth_balance', 'btc_balance', 'type', 'assets', 'price']);
        });
    }
};
