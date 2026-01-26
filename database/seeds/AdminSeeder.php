<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@localhost'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin123!'),
                'role' => 1,
                'email_verified_at' => Carbon::now(),
                'locale' => 'es',
                'timezone' => 'UTC'
            ]
        );
    }
}
