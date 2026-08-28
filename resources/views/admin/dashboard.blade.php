@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Admin Overview</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">System-wide statistics and management.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Trips Today</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $todayTrips }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
            <ion-icon name="analytics-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Vehicles On Trip</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $vehiclesOnTrip }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
            <ion-icon name="car-sport-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Available Drivers</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $availableDrivers }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
            <ion-icon name="people-outline" class="text-3xl"></ion-icon>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">System Activity</h3>
        <a href="{{ route('admin.trips') }}" class="px-5 py-2 text-sm font-semibold text-primary-600 bg-primary-50 rounded-full hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 transition-colors">
            View All
        </a>
    </div>
    <div class="p-8">
        @if($recentActivity->count() > 0)
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
                    @foreach($recentActivity as $trip)
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
        <div class="flex flex-col items-center justify-center text-center py-10">
            <ion-icon name="document-text-outline" class="text-6xl text-gray-300 dark:text-gray-600 mb-4"></ion-icon>
            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300">No Recent Activity</h4>
            <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-sm">Trips logged by drivers will appear here.</p>
        </div>
        @endif
    </div>
</div>
@endsection
