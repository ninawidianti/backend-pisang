<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddBatchIdToOrderItemsTable extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom batch_id.
     *
     * @return void
     */
    public function up(): void
    {
        // Menambahkan kolom batch_id ke tabel order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('batch_id')->nullable()->after('price'); // Kolom batch_id yang akan menyimpan nilai unik
        });

        // Memperbarui batch_id untuk setiap record yang ada
        DB::table('order_items')->update([
            'batch_id' => DB::raw('CONCAT(order_id, "-", UNIX_TIMESTAMP(created_at))')
        ]);
    }

    /**
     * Balikkan migrasi untuk menghapus kolom batch_id.
     *
     * @return void
     */
    public function down(): void
    {
        // Menghapus kolom batch_id jika migrasi dibatalkan
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('batch_id');
        });
    }
}
