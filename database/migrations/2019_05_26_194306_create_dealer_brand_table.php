<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_brand', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('dealer_id')->index()->comment('ID дилера');
            $table->bigInteger('brand_id')->index()->comment('ID бренда');

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
        Schema::dropIfExists('dealer_brand');
    }
}
