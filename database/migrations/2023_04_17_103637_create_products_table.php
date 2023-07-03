<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id('product_id');
            $table->string('product_code');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories')->onUpdate('restrict')->onDelete('restrict');
            $table->string('product_name');
            $table->string('merk')->nullable();
            $table->integer('price');
            $table->tinyInteger('discount')->default(0)->nullable();
            $table->integer('stock')->nullable();
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
