<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('amount_ordered');
            $table->string('name');
            $table->string('country_code_of_origin')->nullable();
            $table->string('hs_code')->nullable();
            $table->string('sku')->nullable();
            $table->string('ean')->nullable();
            $table->decimal('price_per_unit')->nullable();
            $table->unsignedInteger('weight_per_unit')->nullable();
            $table->string('currency')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
