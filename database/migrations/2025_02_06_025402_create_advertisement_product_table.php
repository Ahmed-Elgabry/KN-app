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
        Schema::create('advertisement_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->nullable()->constrained()->cascadeOnDelete()->index();
            $table->string('product_name')->nullable();
            $table->float('original_price')->nullable()->default(0);
            $table->float('discounted_price')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisement_product');
    }
};
