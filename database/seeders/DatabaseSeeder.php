<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = 'Admin ATI San Roman';
        $user->email = 'atisanroman@gmail.com';
        $user->password = 'atisanroman2024';
        $user->role = 'SuperAdmin';
        $user->save();
    }
}
