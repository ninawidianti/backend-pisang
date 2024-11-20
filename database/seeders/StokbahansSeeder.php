<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokbahansSeeder extends Seeder
{
    public function run()
    {
        DB::table('stokbahans')->insert([
            [
                'name' => 'Bahan Kayu',
                'stock_quantity' => 100,
                'unit' => 'kg',
                'purchase_price' => 50000,
                'supplier' => 'Supplier A',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bahan Besi',
                'stock_quantity' => 200,
                'unit' => 'kg',
                'purchase_price' => 75000,
                'supplier' => 'Supplier B',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bahan Plastik',
                'stock_quantity' => 150,
                'unit' => 'meter',
                'purchase_price' => 25000,
                'supplier' => 'Supplier C',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
