<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('city_id')->comment('ID города');
            $table->string('title')->comment('Наименование дилера');
            $table->string('alias')->unique()->comment('Алиас дилера для ЧПУ');
            $table->string('address')->nullable()->comment('Адрес');
            $table->string('phone')->nullable()->comment('Телефон');
            $table->string('site')->nullable()->comment('Сайт');
            $table->double('latitude')->nullable()->comment('Широта');
            $table->double('longitude')->nullable()->comment('Долгота');
            $table->integer('sale_count')->default(0)->comment('Количество объявлений');

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
        Schema::dropIfExists('dealers');
    }
}
