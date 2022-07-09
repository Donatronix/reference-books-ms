<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinMarketCapExchangeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_market_cap_exchange_histories', function (Blueprint $table) {
            $table->id();
            $table->string('currency')->nullable();
            $table->text('currency_name')->nullable();
            $table->string('rate')->nullable();
            $table->string('time')->nullable();
            $table->string('coin_market_cap_id')->nullable();
            $table->string('provider')->nullable();
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('coin_market_cap_exchange_histories');
    }
}