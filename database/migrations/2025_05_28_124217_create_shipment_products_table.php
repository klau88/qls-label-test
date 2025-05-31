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
        Schema::create('shipment_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->string('shipment_product_id')->nullable();
            $table->unsignedInteger('amount');
            $table->string('name');
            $table->string('country_code_of_origin')->nullable();
            $table->string('hs_code')->nullable();
            $table->string('ean')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedInteger('price_per_unit')->nullable();
            $table->unsignedInteger('weight_per_unit')->nullable();
            $table->string('currency');
            $table->timestamps();

            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_products');
    }
};
