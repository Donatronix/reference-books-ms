<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('title', 100);
            $table->string('code', 3);
            $table->string('symbol', 7)->default('U+00A4');
            $table->binary('icon')->nullable();
            $table->float('rate',15,2)->unsigned();

            $table->unsignedTinyInteger('type_id');
            $table->foreign('type_id')->references('id')->on('currency_types')->onDelete('cascade');

            $table->boolean('status')->default('0');
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
        Schema::dropIfExists('currencies');
    }
}
