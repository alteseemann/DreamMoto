<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterNameTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_name_terms', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('parameter_name_id')->index()->comment('ID Характеристики');
            $table->String('title')->comment('Термин');

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
        Schema::dropIfExists('parameter_name_terms');
    }
}
