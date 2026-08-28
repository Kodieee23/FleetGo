<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\TripPurpose;
use App\Models\Vehicle;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Departments
        $departments = [
            'Finance', 'IT', 'Human Resources', 'Procurement', 'Energy', 'Water', 
            'Legal', 'Risk-Management', 'Internal Audit', 'Regulatory Economics', 
            'Research', 'Corporate Affairs', 'Transport', 'Regional Operations'
        ];
        foreach ($departments as $dept) {
            Department::create(['name' => $dept]);
        }

        // 2. Seed Trip Purposes
        $purposes = [
            'Bank Errands', 'Letter Dispatch', 'Travel', 'Staff Transportation', 
            'Department Errand', 'Car Servicing', 'Other Official Duty'
        ];
        foreach ($purposes as $purpose) {
            TripPurpose::create(['name' => $purpose]);
        }

        // 3. Seed Vehicles
        $vehicles = [
            'Kantanka', 'Trooper 1', 'Trooper 2', 'Nissan Patrol', 'Toyota Pickup', 
            'Toyota Prado', 'Toyota Prado 2', 'Volkswagen', 'Toyota Yaris', 'Toyota Yaris 2'
        ];
        foreach ($vehicles as $vehicle) {
            Vehicle::create(['name' => $vehicle]);
        }

        // 4. Seed Users
        // Admin
        User::create([
            'username' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);

        // Manager
        User::create([
            'username' => 'Frontdesk_HR',
            'name' => 'Frontdesk (HR)',
            'email' => 'hr@example.com',
            'role' => 'manager',
            'password' => Hash::make('password')
        ]);

        // Drivers
        $drivers = [
            ['username' => 'kmensah', 'name' => 'Kojo Mensah'],
            ['username' => 'aboateng', 'name' => 'Ama Boateng'],
            ['username' => 'yowusu', 'name' => 'Yaw Owusu'],
            ['username' => 'edarko', 'name' => 'Efua Darko'],
            ['username' => 'kappiah', 'name' => 'Kwame Appiah'],
            ['username' => 'aosei', 'name' => 'Abena Osei'],
            ['username' => 'kofimensah', 'name' => 'Kofi Mensah'],
            ['username' => 'aaddo', 'name' => 'Akua Addo'],
            ['username' => 'yserwaa', 'name' => 'Yaa Serwaa'],
        ];
        foreach ($drivers as $driver) {
            User::create([
                'username' => $driver['username'],
                'name' => $driver['name'],
                'role' => 'driver',
                'password' => Hash::make('password')
            ]);
        }

        // 5. Seed Settings
        Setting::create(['name' => 'driver_availability_window', 'value' => '5', 'description' => 'Cooldown in minutes for drivers before they can assigned another trip']);
        Setting::create(['name' => 'late_trip_threshold', 'value' => '60', 'description' => 'Threshold in minutes to flag a trip as late']);
    }
}
