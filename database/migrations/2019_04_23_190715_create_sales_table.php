<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table -> uuid( 'uuid' )->comment('uuid Для вывода в url');
            $table->bigInteger('product_id')->index()->comment('ID модели каталога');
            $table->bigInteger('dealer_id')->index()->comment('ID дилера');

            $table->Integer('price')->index()->comment('Цена');
            $table->integer('view_count')->default(0)->comment('Количество просмотров');

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
        Schema::dropIfExists('sales');
    }
}
