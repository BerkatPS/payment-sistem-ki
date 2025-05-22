<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create initial data
        $this->call([
            \Database\Seeders\PaymentSystemSeeder::class,
        ]);
        
        // Create super_admin role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        
        // Create admin user
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Assign super_admin role
        $user->assignRole($role);
    }
}
