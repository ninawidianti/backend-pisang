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
        Schema::create('stokbahans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('stock_quantity', 10,0);
            $table->string('unit');
            $table->decimal('purchase_price', 10,0);
            $table->string('supplier');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stokbahans');
    }
};