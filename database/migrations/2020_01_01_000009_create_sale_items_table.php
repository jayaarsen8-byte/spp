<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleItemsTable extends Migration
{
    public function up()
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 15, 4);
            $table->decimal('length', 15, 4)->nullable();
            $table->decimal('total_meter', 15, 4)->nullable();
            $table->decimal('normal_unit_price', 15, 2);
            $table->decimal('selling_unit_price', 15, 2);
            $table->decimal('discount_per_unit', 15, 2)->default(0);
            $table->decimal('cost_price', 15, 2);
            $table->decimal('subtotal_normal', 15, 2);
            $table->decimal('total_discount', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('profit', 15, 2);
            $table->timestamps();
            
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index('sale_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_items');
    }
}
