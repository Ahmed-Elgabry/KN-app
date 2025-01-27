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
        Schema::create('sub_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->index();
            $table->double('balance', 8, 3);
            $table->enum('operation', ['plus', 'minus'])->default('plus')->index();
            $table->string('description');
            $table->integer('type')->nullable();
            $table->integer('data_id')->nullable();
            $table->timestamps();
            $table->foreign('wallet_id')->references('id')->on('wallets')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_wallets');
    }
};
