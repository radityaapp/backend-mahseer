<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{

    public function run(): void
    {
        Currency::updateOrCreate(
            ['code' => 'IDR'],
            [
                'name'              => 'Indonesian Rupiah',
                'symbol'            => 'Rp',
                'exchange_rate'     => 1,
                'is_default'        => true,
                'is_active'         => true,
            ]
        );
        
        Currency::updateOrCreate(
            ['code' => 'USD'],
            [
                'name'              => 'US Dollar',
                'symbol'            => '$',
                'exchange_rate'     => 0.000067,
                'is_default'        => false,
                'is_active'         => true,
            ]
        );
    }
}