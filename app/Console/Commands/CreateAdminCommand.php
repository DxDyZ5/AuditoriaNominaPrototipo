<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'app:create-admin {--name=} {--email=} {--password=}';
    protected $description = 'Create an admin user';

    public function handle()
    {
        $name = $this->option('name') ?? 'Admin';
        $email = $this->option('email') ?? 'admin@localhost';
        $password = $this->option('password') ?? Str::random(12);

        if (User::where('email', $email)->exists()) {
            $this->info('User already exists');
            return 0;
        }

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->locale = config('app.locale');
        $user->role = 1;
        $user->timezone = 'UTC';
        $user->api_token = Str::random(64);
        $user->email_verified_at = now();
        $user->save();

        $this->info('Admin created: '.$email);
        return 0;
    }
}
