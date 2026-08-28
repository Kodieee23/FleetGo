@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Manager Dashboard</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Monitor daily operations and driver availability.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Active Trips -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Active Trips</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $activeTrips }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
            <ion-icon name="map-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
    
    <!-- Vehicles on Trip -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Vehicles On Trip</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $vehiclesOnTrip }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
            <ion-icon name="car-sport-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
    
    <!-- Drivers on Trip -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Drivers On Trip</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $driversOnTrip }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400">
            <ion-icon name="id-card-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Inactive Drivers</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $offlineQueue }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
            <ion-icon name="hourglass-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Available Drivers</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($availableDrivers) }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center text-teal-600 dark:text-teal-400">
            <ion-icon name="person-outline" class="text-3xl"></ion-icon>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Available Vehicles</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $allVehicles->where('status', 'available')->count() }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
            <ion-icon name="car-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Available Drivers List -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center"><ion-icon name="people" class="mr-2 text-green-500"></ion-icon> Drivers Ready for Dispatch</h3>
        </div>
        <div class="p-4 max-h-64 overflow-y-auto">
            @if($availableDrivers->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($availableDrivers as $driver)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            {{ $driver->first_name }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No drivers are currently available.</p>
            @endif
        </div>
    </div>

    <!-- Fleet Status List -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center"><ion-icon name="car-sport" class="mr-2 text-purple-500"></ion-icon> Fleet Status</h3>
        </div>
        <div class="p-4 max-h-64 overflow-y-auto">
            @if($allVehicles->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($allVehicles as $vehicle)
                        @if($vehicle->status === 'available')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                {{ $vehicle->name }}
                            </span>
                        @elseif($vehicle->status === 'on_trip')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800">
                                <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2 animate-pulse"></span>
                                {{ $vehicle->name }} (On Trip)
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                {{ $vehicle->name }} (Maintenance)
                            </span>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No vehicles in the system.</p>
            @endif
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Trips</h3>
        <a href="{{ route('manager.reports') }}" class="px-5 py-2 text-sm font-semibold text-primary-600 bg-primary-50 rounded-full hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 transition-colors">
            Generate Report
        </a>
    </div>
    <div class="p-8">
        @if($recentTrips->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-3 px-4">Driver</th>
                        <th class="pb-3 px-4">Destination</th>
                        <th class="pb-3 px-4">Vehicle</th>
                        <th class="pb-3 px-4 text-center">Status</th>
                        <th class="pb-3 px-4 text-right">Time Out</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($recentTrips as $trip)
                    <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-4 px-4 font-bold text-gray-900 dark:text-white">{{ $trip->driver->first_name ?? 'Unknown' }}</td>
                        <td class="py-4 px-4 text-gray-600 dark:text-gray-300">{{ Str::limit($trip->destination, 20) }}</td>
                        <td class="py-4 px-4 text-gray-600 dark:text-gray-300">{{ $trip->vehicle->name ?? 'N/A' }}</td>
                        <td class="py-4 px-4 text-center">
                            @if($trip->time_returned)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Completed</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">On Trip</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($trip->time_out)->format('g:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <ion-icon name="document-text-outline" class="text-6xl text-gray-300 dark:text-gray-600 mb-4"></ion-icon>
            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300">No Trips Logged Yet</h4>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Data will appear here once trips are created.</p>
        </div>
        @endif
    </div>
</div>
@endsection
