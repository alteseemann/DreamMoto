<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->String('title')->comment('Наименование');
            $table->String('block_id')->comment('ID блока скрипта');
            $table->text('script')->comment('Содержимое скрипта');
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
        Schema::dropIfExists('ad_blocks');
    }
}
