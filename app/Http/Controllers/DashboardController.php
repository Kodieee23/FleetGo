<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function admin()
    {
        $todayTrips = Trip::whereDate('created_at', today())->count();
        $vehiclesOnTrip = Vehicle::where('status', 'on_trip')->count();
        
        // Drivers available (FIFO Queue: ordered by when they became available)
        $availableDrivers = User::where('role', 'driver')
            ->where('is_active', true)
            // and not currently on an active trip
            ->whereDoesntHave('trips', function ($query) {
                $query->whereNull('time_returned');
            })
            // Sort by cooldown_until ASC (oldest first). Nulls first.
            ->orderByRaw('cooldown_until IS NOT NULL') 
            ->orderBy('cooldown_until', 'asc')
            ->count();

        $recentActivity = Trip::with(['driver', 'vehicle', 'purpose'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('todayTrips', 'vehiclesOnTrip', 'availableDrivers', 'recentActivity'));
    }

    public function manager()
    {
        $activeTrips = Trip::whereNull('time_returned')->count();
        $vehiclesOnTrip = Vehicle::where('status', 'on_trip')->count();
        // Since each active trip has one driver, drivers on trip is basically activeTrips
        $driversOnTrip = $activeTrips;

        // Drivers inactive or offline
        $offlineQueue = User::where('role', 'driver')->where('is_active', false)->count();
        
        $recentTrips = Trip::with(['driver', 'vehicle', 'purpose'])
            ->latest()
            ->take(10)
            ->get();

        $availableDrivers = User::where('role', 'driver')
            ->where('is_active', true)
            ->whereDoesntHave('trips', function ($query) {
                $query->whereNull('time_returned');
            })
            // Sort by cooldown_until ASC (oldest first). Nulls first.
            ->orderByRaw('cooldown_until IS NOT NULL') 
            ->orderBy('cooldown_until', 'asc')
            ->get();
            
        $allVehicles = Vehicle::orderBy('status')->get();

        return view('manager.dashboard', compact('activeTrips', 'vehiclesOnTrip', 'driversOnTrip', 'offlineQueue', 'recentTrips', 'availableDrivers', 'allVehicles'));
    }

    public function driver()
    {
        $user = auth()->user();
        
        $todayCompleted = Trip::where('driver_id', $user->id)
            ->whereDate('created_at', today())
            ->whereNotNull('time_returned')
            ->count();
            
        $isOnCooldown = $user->cooldown_until && $user->cooldown_until > now();
        $activeTrip = Trip::where('driver_id', $user->id)
            ->whereNull('time_returned')
            ->first();
            
        $status = 'Available';
        if ($activeTrip) {
            $status = 'On Trip';
        }

        return view('driver.dashboard', compact('todayCompleted', 'status', 'isOnCooldown', 'activeTrip'));
    }
}
