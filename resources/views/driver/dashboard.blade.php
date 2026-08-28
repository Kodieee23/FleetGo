@extends('layouts.app')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">My Dashboard</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage your current trip and availability.</p>
    </div>
    <div class="mt-4 md:mt-0">
        @if($status === 'Available')
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2 animate-pulse"></span> Available
        </span>
        @elseif($status === 'On Trip')
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800 shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 mr-2 animate-pulse"></span> On Trip
        </span>
        @else
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800 shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full bg-purple-500 mr-2 animate-pulse"></span> In Queue
        </span>
        @endif
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-8 relative">
    <!-- Decorative background element -->
    <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-primary-50 dark:bg-primary-900/20 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="p-8 md:p-10 relative z-10 flex flex-col items-center justify-center text-center">
        @if($activeTrip)
            <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900/40 rounded-full flex items-center justify-center mb-6 text-yellow-600 dark:text-yellow-400 shadow-inner">
                <ion-icon name="car-outline" class="text-4xl animate-bounce"></ion-icon>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Trip in Progress</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-4">You are currently heading to <strong>{{ $activeTrip->destination }}</strong>.</p>
            
            <form action="{{ route('driver.trips.end', $activeTrip->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center space-x-3">
                    <ion-icon name="stop-circle-outline" class="text-2xl"></ion-icon>
                    <span>End Trip Now</span>
                </button>
            </form>
        @elseif($isOnCooldown)
            <div class="w-20 h-20 bg-purple-100 dark:bg-purple-900/40 rounded-full flex items-center justify-center mb-6 text-purple-600 dark:text-purple-400 shadow-inner">
                <ion-icon name="hourglass-outline" class="text-4xl animate-spin-slow"></ion-icon>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">You are in the Cooldown Queue</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">Please wait for the 5-minute cooldown period to finish before logging a new trip.</p>
            
            <button disabled class="px-8 py-4 bg-gray-400 text-white font-bold rounded-2xl shadow-lg cursor-not-allowed flex items-center space-x-3">
                <ion-icon name="lock-closed-outline" class="text-2xl"></ion-icon>
                <span>Please Wait...</span>
            </button>
        @else
            <div class="w-20 h-20 bg-primary-100 dark:bg-primary-900/40 rounded-full flex items-center justify-center mb-6 text-primary-600 dark:text-primary-400 shadow-inner">
                <ion-icon name="navigate-outline" class="text-4xl"></ion-icon>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ready to hit the road?</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">Start a new trip to record your destination, purpose, and vehicle. Your manager will be notified automatically.</p>
            
            <a href="{{ route('driver.trips.create') }}" class="px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center space-x-3 group">
                <ion-icon name="add-circle-outline" class="text-2xl group-hover:rotate-90 transition-transform duration-300"></ion-icon>
                <span>Log New Trip</span>
            </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Today's Summary</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-300 font-medium">Trips Completed</span>
                <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $todayCompleted }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
