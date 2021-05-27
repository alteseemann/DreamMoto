<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotoDealerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moto_dealer', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('moto_id')->index()->comment('ID категории техники');
            $table->bigInteger('dealer_id')->index()->comment('ID дилера');

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
        Schema::dropIfExists('moto_dealer');
    }
}
