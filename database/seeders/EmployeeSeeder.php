<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            // Sales Department
            ['name' => 'Rajesh Kumar', 'email' => 'rajesh@mayfair.com', 'department' => 'Sales', 'designation' => 'Sales Manager'],
            ['name' => 'Priya Sharma', 'email' => 'priya@mayfair.com', 'department' => 'Sales', 'designation' => 'Sales Executive'],
            ['name' => 'Amit Patel', 'email' => 'amit@mayfair.com', 'department' => 'Sales', 'designation' => 'Sales Executive'],
            
            // Management
            ['name' => 'Suresh Gupta', 'email' => 'suresh@mayfair.com', 'department' => 'Management', 'designation' => 'General Manager'],
            ['name' => 'Meena Reddy', 'email' => 'meena@mayfair.com', 'department' => 'Management', 'designation' => 'Operations Manager'],
            
            // Accounts
            ['name' => 'Vijay Singh', 'email' => 'vijay@mayfair.com', 'department' => 'Accounts', 'designation' => 'Accounts Manager'],
            ['name' => 'Kavita Joshi', 'email' => 'kavita@mayfair.com', 'department' => 'Accounts', 'designation' => 'Accountant'],
            
            // HR
            ['name' => 'Anjali Mehta', 'email' => 'anjali@mayfair.com', 'department' => 'HR', 'designation' => 'HR Manager'],
            
            // IT
            ['name' => 'Rahul Verma', 'email' => 'rahul@mayfair.com', 'department' => 'IT', 'designation' => 'IT Manager'],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
