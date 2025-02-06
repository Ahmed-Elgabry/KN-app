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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_user_id')->constrained('social_users')->cascadeOnDelete();
            $table->string('entity_name');
            $table->string('location', 500);
            $table->text('advertisement_description')->nullable();
            $table->enum('advertisement_type', ['direct_product', 'percentage_all', 'percentage_specific']);
            $table->decimal('advertisement_percentage')->default(0);
            $table->text('description')->nullable();
            $table->boolean('daily_advertisement')->default(false);
            $table->boolean('all_day_advertisement')->default(false);
            $table->time('start_discount')->nullable();
            $table->time('end_discount')->nullable();
            $table->time('start_work');
            $table->time('end_work');
            $table->string('working_days' , 400);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_advertisement')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected' , 'blocked'])->default('pending');
            $table->timestamps();
            $table->index('social_user_id');
            $table->index('advertisement_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
