<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->comment('Наименование бренда');
            $table->string('alias')->comment('Алиас бренда для ЧПУ');
            $table->string('title_ru')->nullable()->comment('Наименование для тайтла');
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
        Schema::dropIfExists('brands');
    }
}
