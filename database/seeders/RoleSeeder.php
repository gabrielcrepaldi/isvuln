<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;      // THIS LINE IS REQUIRED

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
	    Role::firstOrCreate(['name' => 'admin']);
	    Role::firstOrCreate(['name' => 'analyst']);
	    Role::firstOrCreate(['name' => 'viewer']);
	    
	    $this->command->info('Roles created: admin, analyst, viewer');
    }
}
