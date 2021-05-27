<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->comment('Наименование категории техники');
            $table->string('title_single')->comment('Наименование в единственном числе');
            $table->string('title_single_chego')->comment('Наименование в единственном числе в род. падеже');
            $table->string('title_chego')->comment('Наименование в род. падеже');
            $table->string('alias')->comment('Алиас категории техники для ЧПУ');
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
        Schema::dropIfExists('motos');
    }
}
