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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('address')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->text('description')->nullable();
            $table->integer('discount_type')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->text('discount_percentage_text')->nullable();
            $table->boolean('daly_discount')->nullable();
            $table->boolean('all_work_hours')->nullable();
            $table->text('daly_discount_text')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->foreignId('user_id')->index();
            $table->foreignId('city_id')->index();
            $table->foreign('user_id')->references('id')->on('social_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
