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
        Schema::create('business_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('city_id')->index();
            $table->foreignId('post_id')->index();
            $table->foreignId('ad_plan_id')->index();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('work_hours')->nullable();
            $table->double('price')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_paid')->nullable();
            $table->integer('days')->nullable();
            $table->double('ad_price')->nullable();
            $table->foreign('user_id')->references('id')->on('social_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ad_plan_id')->references('id')->on('ad_plans')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_centers');
    }
};
