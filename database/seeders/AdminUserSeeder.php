<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'bielcrepaldi07@gmail.com'],
            [
                'name'     => 'admin',
                'password' => Hash::make('dev4crpld!'),
            ]
        );

        $admin->assignRole('admin');

        $this->command->info('Admin user created: bielcrepaldi07@gmail.com');
    }
}
