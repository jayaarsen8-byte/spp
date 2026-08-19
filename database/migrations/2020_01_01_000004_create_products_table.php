<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('unit_id');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('cost_price', 15, 2);
            $table->decimal('consumer_price', 15, 2);
            $table->decimal('applicator_price', 15, 2);
            $table->decimal('buyer_price', 15, 2);
            $table->enum('calculation_type', ['quantity', 'meter', 'sheet_meter'])->default('quantity');
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->index('category_id');
            $table->index('unit_id');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
