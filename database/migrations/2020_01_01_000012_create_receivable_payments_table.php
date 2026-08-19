<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivablePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('receivable_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receivable_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 15, 2);
            $table->enum('method', ['cash', 'transfer', 'qris', 'debit', 'credit_card', 'e_wallet']);
            $table->text('note')->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();
            
            $table->foreign('receivable_id')->references('id')->on('receivables')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('receivable_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receivable_payments');
    }
}
