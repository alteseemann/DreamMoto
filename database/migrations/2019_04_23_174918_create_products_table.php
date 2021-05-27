<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('moto_id')->index()->comment('ID категории техники');
            $table->bigInteger('brand_id')->index()->comment('ID бренда');
            $table->bigInteger('class_id')->nullable()->comment('ID класса техники');

            $table->string('title')->comment('Наименование модели каталога');
            $table->string('alias')->unique()->comment('Алиас продукта каталога для ЧПУ');
            $table->text('description')->nullable()->comment('Описание');
            $table->string('old_alias')->nullable()->comment('Старый алиас');

            $table->integer('price_catalog')->comment('Цена производителя');

            $table->integer('sale_count')->default(0)->comment('Количество объявлений');
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
        Schema::dropIfExists('products');
    }
}
