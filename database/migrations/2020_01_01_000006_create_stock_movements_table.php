<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['opening', 'purchase', 'sale', 'return', 'adjustment', 'opname']);
            $table->decimal('quantity', 15, 4);
            $table->string('reference')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('product_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
