<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->string('model')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('changes')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('action');
            $table->index('model');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
