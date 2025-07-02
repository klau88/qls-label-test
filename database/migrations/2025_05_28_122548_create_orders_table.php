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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_combination_id');
            $table->string('company_id');
            $table->string('brand_id');
            $table->string('number');
            $table->string('billing_companyname')->nullable();
            $table->string('billing_name');
            $table->string('billing_street');
            $table->string('billing_housenumber');
            $table->string('billing_address_line_2')->nullable();
            $table->string('billing_zipcode');
            $table->string('billing_city');
            $table->string('billing_country');
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('delivery_companyname')->nullable();
            $table->string('delivery_name');
            $table->string('delivery_street');
            $table->string('delivery_housenumber');
            $table->string('delivery_address_line_2')->nullable();
            $table->string('delivery_zipcode');
            $table->string('delivery_city');
            $table->string('delivery_country');
            $table->string('delivery_email')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
