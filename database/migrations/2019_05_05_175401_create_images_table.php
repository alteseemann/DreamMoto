<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->String('path')->comment('Путь к изображению');
            $table->String('description')->nullable()->comment('Описание изображения');

            $table->bigInteger('imageable_id')->comment('ID записи таблицы, содержащей изображение');
            $table->String('imageable_type')->comment('Класс таблицы, содержащей изображение');

            $table->Integer('sort')->default(1)->comment('Сортировка');

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
        Schema::dropIfExists('images');
    }
}
