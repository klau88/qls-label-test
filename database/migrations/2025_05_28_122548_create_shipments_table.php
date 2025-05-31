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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id')->nullable();
            $table->string('company_id');
            $table->string('brand_id');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('product_combination_id');
            $table->string('barcode')->nullable();
            $table->string('tracking_url')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->unsignedInteger('weight');
            $table->string('reference');
            $table->unsignedInteger('cod_amount')->nullable();
            $table->string('customs_shipment_type')->nullable();
            $table->string('customs_invoice_number')->nullable();
            $table->string('label_pdf_url')->nullable();
            $table->string('label_zpl_url')->nullable();
            $table->string('sender_company_name');
            $table->string('sender_name');
            $table->string('sender_street');
            $table->string('sender_housenumber');
            $table->string('sender_address2')->nullable();
            $table->string('sender_postalcode');
            $table->string('sender_city');
            $table->string('sender_country');
            $table->string('sender_phone')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_vat')->nullable();
            $table->string('sender_eori')->nullable();
            $table->string('sender_oss')->nullable();
            $table->string('sender_type')->nullable();
            $table->string('receiver_company_name');
            $table->string('receiver_name');
            $table->string('receiver_street');
            $table->string('receiver_housenumber');
            $table->string('receiver_address2')->nullable();
            $table->string('receiver_postalcode');
            $table->string('receiver_city');
            $table->string('receiver_country');
            $table->string('receiver_phone')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('receiver_vat')->nullable();
            $table->string('receiver_eori')->nullable();
            $table->string('receiver_oss')->nullable();
            $table->string('receiver_type')->nullable();
            $table->string('shipment_short')->nullable();
            $table->string('shipment_name')->nullable();
            $table->string('shipment_type')->nullable();
            $table->string('tracking_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
