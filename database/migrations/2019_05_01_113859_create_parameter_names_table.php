<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_names', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('moto_id')->index()->comment('ID категории техники');
            $table->bigInteger('parameter_name_group_id')->index()->comment('ID группы характеристики');
            $table->String('title')->comment('Название технической характеристики');
            $table->String('alias')->comment('Алиас технической характеристики');

            $table->boolean('preview')->default(0)->comment('Выводить в превью модели');
            $table->String('unit', 10)->nullable()->comment('Единицы измерения');
            $table->Integer('sort')->nullable()->comment('Сортировка');

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
        Schema::dropIfExists('parameter_names');
    }
}
