<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\TripStop;
use App\Models\Vehicle;
use App\Models\Department;
use App\Models\TripPurpose;
use App\Models\User;
use Carbon\Carbon;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Trip::with(['vehicle', 'purpose', 'department', 'stops'])
                     ->where('driver_id', $user->id);

        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('purpose')) {
            $query->where('purpose_id', $request->purpose);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('time_returned');
            } elseif ($request->status === 'completed') {
                $query->whereNotNull('time_returned');
            }
        }

        $trips = $query->latest()->get();
        $purposes = TripPurpose::where('is_active', true)->get();

        return view('driver.trips.index', compact('trips', 'purposes'));
    }

    public function adminIndex(Request $request)
    {
        return $this->getTripsView($request, 'admin.trips.index');
    }

    public function managerIndex(Request $request)
    {
        return $this->getTripsView($request, 'manager.trips.index');
    }

    private function getTripsView(Request $request, $viewName)
    {
        $query = Trip::with(['vehicle', 'purpose', 'department', 'stops', 'driver']);

        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('purpose')) {
            $query->where('purpose_id', $request->purpose);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('time_returned');
            } elseif ($request->status === 'completed') {
                $query->whereNotNull('time_returned');
            }
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('destination', 'like', "%{$search}%")
                  ->orWhereHas('driver', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehicle', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $trips = $query->latest()->paginate(20);
        $purposes = TripPurpose::where('is_active', true)->get();

        return view($viewName, compact('trips', 'purposes'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Ensure no active trip
        $activeTrip = Trip::where('driver_id', $user->id)->whereNull('time_returned')->first();
        if ($activeTrip) {
            return redirect()->route('driver.dashboard')->with('error', 'You already have an active trip.');
        }

        // Ensure not on cooldown
        if ($user->cooldown_until && $user->cooldown_until > now()) {
            return redirect()->route('driver.dashboard')->with('error', 'You are still on cooldown.');
        }

        $vehicles = Vehicle::where('status', 'available')->get();
        $purposes = TripPurpose::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('driver.trips.create', compact('vehicles', 'purposes', 'departments'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'purpose_id' => 'required|exists:trip_purposes,id',
            'department_id' => 'required|exists:departments,id',
            'destination' => 'required|string',
            'other_purpose_description' => 'nullable|string',
            'stops' => 'nullable|array',
            'stops.*' => 'string'
        ]);

        $trip = Trip::create([
            'driver_id' => $user->id,
            'vehicle_id' => $request->vehicle_id,
            'purpose_id' => $request->purpose_id,
            'department_id' => $request->department_id,
            'destination' => $request->destination,
            'other_purpose_description' => $request->other_purpose_description,
            'time_out' => now(),
            'is_offline_entry' => false,
        ]);

        // Save multiple stops if provided
        if ($request->filled('stops')) {
            $order = 1;
            foreach ($request->stops as $stopDest) {
                if (!empty($stopDest)) {
                    TripStop::create([
                        'trip_id' => $trip->id,
                        'location' => $stopDest,
                        'order_index' => $order++,
                    ]);
                }
            }
        }

        // Update vehicle status
        $vehicle = Vehicle::find($request->vehicle_id);
        $vehicle->status = 'on_trip';
        $vehicle->save();

        return redirect()->route('driver.dashboard')->with('success', 'Trip started successfully.');
    }

    public function end(Request $request, Trip $trip)
    {
        if ($trip->driver_id !== auth()->id() || $trip->time_returned !== null) {
            abort(403);
        }

        $trip->time_returned = now();
        $trip->save();

        // Release vehicle
        $vehicle = $trip->vehicle;
        $vehicle->status = 'available';
        $vehicle->save();

        // Set 5 minute cooldown
        $user = auth()->user();
        $user->cooldown_until = now()->addMinutes(5);
        $user->save();

        return redirect()->route('driver.dashboard')->with('success', 'Trip ended. You are now in the 5-minute cooldown queue.');
    }
}
