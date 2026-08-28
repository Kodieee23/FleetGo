<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::latest()->paginate(20);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicles',
            'status' => 'required|in:available,on_trip,maintenance',
        ]);

        Vehicle::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Vehicle added successfully.');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('vehicles')->ignore($vehicle->id)],
            'status' => 'required|in:available,on_trip,maintenance',
        ]);

        $vehicle->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->status === 'on_trip') {
            return back()->with('error', 'Cannot delete a vehicle that is currently on a trip.');
        }

        $vehicle->delete();

        return back()->with('success', 'Vehicle permanently removed from the system.');
    }
}
