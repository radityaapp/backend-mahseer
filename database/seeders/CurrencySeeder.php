<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'code'         => 'IDR',
                'name'         => 'Indonesian Rupiah',
                'symbol'       => 'Rp',
                'exchange_rate'=> 1,
                'is_default'   => true,
                'is_active'    => true,
            ],
            [
                'code'         => 'USD',
                'name'         => 'US Dollar',
                'symbol'       => '$',
                'exchange_rate'=> 0.000067,
                'is_default'   => false,
                'is_active'    => true,
            ],
        ];

        foreach ($currencies as $data) {
            Currency::firstOrCreate(
                ['code' => $data['code']],
                $data,
            );
        }
    }
}