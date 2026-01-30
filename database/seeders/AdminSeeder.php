<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update default admin user
        Employee::updateOrCreate(
            ['email' => 'admin@mayfair.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Change this in production!
                'department' => 'Management',
                'designation' => 'Administrator',
                'is_active' => true,
                'is_admin' => true,
            ]
        );

        // Create or update additional test admin
        Employee::updateOrCreate(
            ['email' => 'manager@mayfair.com'],
            [
                'name' => 'John Manager',
                'password' => Hash::make('password'),
                'department' => 'Sales',
                'designation' => 'Sales Manager',
                'is_active' => true,
                'is_admin' => true,
            ]
        );

        echo "Admin users created/updated successfully!\n";
    }
}
