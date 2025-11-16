<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        User::updateOrCreate([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ]);
        
        $this->call([
            CurrencySeeder::class,
        ]);
    }
}