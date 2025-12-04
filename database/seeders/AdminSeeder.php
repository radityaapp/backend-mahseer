<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => env('ADMIN_NAME', 'Administrator'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ],
        );

        if (! $user->is_admin) {
            $user->is_admin = true;
            $user->save();
        }

        $this->command->info("Admin siap dipakai: {$user->email}");
    }
}