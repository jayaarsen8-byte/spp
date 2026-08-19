<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('price_type', ['consumer', 'applicator', 'buyer'])->default('consumer');
            $table->decimal('subtotal_normal', 15, 2)->default(0);
            $table->decimal('total_discount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->decimal('receivable_amount', 15, 2)->default(0);
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->timestamp('sold_at');
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('customer_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('sold_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
