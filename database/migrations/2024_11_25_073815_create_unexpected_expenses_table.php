<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnexpectedExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('unexpected_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Penjelasan biaya
            $table->decimal('amount', 15, 2); // Jumlah biaya
            $table->date('date'); // Tanggal kejadian
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('unexpected_expenses');
    }
}