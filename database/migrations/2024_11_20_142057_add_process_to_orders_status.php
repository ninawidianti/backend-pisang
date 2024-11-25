<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessToOrdersStatus extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah kolom status untuk menambahkan 'process' jika belum ada
            $table->enum('status', ['pending', 'process', 'completed', 'canceled'])->default('pending')->change();
        });
    }

    /**
     * Balikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengembalikan perubahan jika migrasi dibatalkan
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending')->change();
        });
    }
}
