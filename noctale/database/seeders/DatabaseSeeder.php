<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default Admin User
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@noctale.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin Noctale'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
                'role' => 'admin',
            ]
        );
    }
}
