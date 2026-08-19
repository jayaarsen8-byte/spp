<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivablesTable extends Migration
{
    public function up()
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->date('due_date')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();
            
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->index('sale_id');
            $table->index('customer_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receivables');
    }
}
