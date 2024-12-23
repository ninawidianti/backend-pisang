<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAmountColumnInUnexpectedExpensesTable extends Migration
{
    public function up()
    {
        Schema::table('unexpected_expenses', function (Blueprint $table) {
            $table->decimal('amount', 10, 0)->change(); // Mengubah jumlah biaya menjadi decimal(10, 0)
        });
    }

    public function down()
    {
        Schema::table('unexpected_expenses', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change(); // Mengembalikan kolom ke decimal(15, 2) jika rollback
        });
    }
}
