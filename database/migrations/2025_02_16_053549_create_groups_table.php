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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_interest_id')->constrained()->cascadeOnDelete();
            $table->string('group_name');
            $table->enum('group_status', ['public', 'private'])->default('public')->index();
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->text('group_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
