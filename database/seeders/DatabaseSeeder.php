<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('email', 'admin@example.com')->exists()) {
            $admin = new User([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
            ]);
            $admin->role = 'admin';
            $admin->save();
        }

        if (! User::where('email', 'user@example.com')->exists()) {
            $user = new User([
                'name' => 'User Mahasiswa',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
            ]);
            $user->role = 'user';
            $user->save();
        }

        $this->call([
            ProjectSampleSeeder::class,
        ]);
    }
}
