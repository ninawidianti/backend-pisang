<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'payment_method' => 'credit_card',
                'delivery_method' => 'courier',
                'address' => '123 Main Street',
                'total_price' => 150000,
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'payment_method' => 'cash_on_delivery',
                'delivery_method' => 'pickup',
                'address' => '456 Secondary Avenue',
                'total_price' => 75000,
                'status' => 'completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'payment_method' => 'bank_transfer',
                'delivery_method' => 'courier',
                'address' => '789 Tertiary Road',
                'total_price' => 250000,
                'status' => 'canceled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'payment_method' => 'bank_transfer',
                'delivery_method' => 'courier',
                'address' => '789 Tertiary Road',
                'total_price' => 250000,
                'status' => 'completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ]);
    }
}
