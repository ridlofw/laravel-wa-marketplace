<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove old admin if exists to prevent duplicates or clean up
        User::where('email', 'admin@permataklepu.com')->delete();

        User::updateOrCreate(
            ['email' => 'admin@marketplace.com'],
            [
                'name' => 'Pemdes Desa Klepu',
                'password' => Hash::make('admin'),
                'role' => 'superadmin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
