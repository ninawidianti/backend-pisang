<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Menghubungkan ke tabel users
            $table->string('payment_method'); // Metode pembayaran
            $table->string('delivery_method'); // Metode pengiriman
            $table->text('address')->nullable(); // Alamat pengiriman (nullable)
            $table->decimal('total_price', 10, 2); // Total harga
            $table->enum('status', ['pending', 'process', 'completed', 'canceled'])->default('pending'); // Status pesanan
            $table->timestamps(); // Menyimpan waktu dibuat dan diperbarui
        });
    }

    /**
     * Balikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}