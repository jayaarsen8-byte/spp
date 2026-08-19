<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('customer_type', ['consumer', 'applicator', 'buyer'])->default('consumer');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('customer_type');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
