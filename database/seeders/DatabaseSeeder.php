<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin — auto-approved, instant control center access
        User::create([
            'name'              => 'Efrem Admin',
            'email'             => 'efremfretewahdo@gmail.com',
            'password'          => Hash::make('mearg@42027israel'),
            'email_verified_at' => now(),
            'is_approved'       => true,
        ]);
    }
}
