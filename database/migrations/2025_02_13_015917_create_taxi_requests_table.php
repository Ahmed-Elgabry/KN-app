<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxi_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('taxi_id')->index();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('from')->nullable();
            $table->string('from_longitude')->nullable();
            $table->string('from_latitude')->nullable();
            $table->string('to')->nullable();
            $table->string('to_longitude')->nullable();
            $table->string('to_latitude')->nullable();
            $table->string('price')->nullable();
            $table->string('notes')->nullable();
            $table->foreign('user_id')->references('id')->on('social_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('taxi_id')->references('id')->on('taxis')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_requests');
    }
};
