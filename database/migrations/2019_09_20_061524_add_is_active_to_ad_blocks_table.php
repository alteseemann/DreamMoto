<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveToAdBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_blocks', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->after('script')->comment('Флаг активности блока');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_blocks', function (Blueprint $table) {
            //
        });
    }
}
