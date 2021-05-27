<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterNameGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_name_groups', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->String('title')->comment('Название группы характеристик');
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
        Schema::dropIfExists('parameter_name_groups');
    }
}
