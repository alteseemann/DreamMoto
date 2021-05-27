<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotoClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moto_types', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('moto_id')->index()->comment('ID категории техники');
            $table->String('title')->comment('Класс техники');
            $table->String('alias')->comment('Алиас');
            $table->String('seo_title')->comment('SEO заголовок');
            $table->String('title_kakih')->comment('Каких?');
            $table->text('description')->nullable()->comment('Описание класса');

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
        Schema::dropIfExists('moto_types');
    }
}
