<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_id');
            $table->enum('method', ['cash', 'transfer', 'qris', 'debit', 'credit_card', 'e_wallet'])->default('cash');
            $table->decimal('amount', 15, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->index('sale_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
