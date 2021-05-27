<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotoBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moto_brand', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('moto_id')->index()->comment('ID категории техники');
            $table->bigInteger('brand_id')->index()->comment('ID бренда');
            $table->text('catalog_description')->nullable()->comment('Описание бренда каталога');

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
        Schema::dropIfExists('moto_brand');
    }
}
