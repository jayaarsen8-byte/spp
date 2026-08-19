<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->unique();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('category_id');
            $table->index('user_id');
            $table->index('expense_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
