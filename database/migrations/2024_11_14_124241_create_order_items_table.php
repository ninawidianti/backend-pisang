<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Menghubungkan ke tabel orders
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Menghubungkan ke tabel products
            $table->integer('quantity'); // Jumlah produk
            $table->decimal('price', 10, 2); // Harga produk pada saat pesanan
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
        Schema::dropIfExists('order_items');
    }
}