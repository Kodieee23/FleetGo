@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Trip History</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">View and filter your past and active trips.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
    <form method="GET" action="{{ route('manager.trips') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <div class="relative">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Date</label>
            <input type="text" name="date" value="{{ request('date') }}" id="date-picker" placeholder="Select date..." class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 cursor-pointer">
            <ion-icon name="calendar-outline" class="absolute right-4 top-[38px] text-gray-400 pointer-events-none"></ion-icon>
        </div>

        <!-- Purpose Filter -->
        <div x-data="{ open: false, selected: '{{ request('purpose') }}', text: 'All Purposes' }" class="relative z-20">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Purpose</label>
            <input type="hidden" name="purpose" :value="selected">
            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 rounded-xl text-left flex justify-between items-center focus:ring-2 focus:ring-primary-500">
                <span x-text="text" class="text-gray-900 dark:text-white"></span>
                <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></ion-icon>
            </button>
            <div x-show="open" x-transition.opacity class="absolute w-full mt-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl z-50">
                <div @click="selected = ''; text = 'All Purposes'; open = false" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">All Purposes</div>
                @foreach($purposes as $purpose)
                    <div @click="selected = '{{ $purpose->id }}'; text = '{{ addslashes($purpose->name) }}'; open = false" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50 last:border-0" x-init="'{{ request('purpose') }}' === '{{ $purpose->id }}' ? text = '{{ addslashes($purpose->name) }}' : null">
                        {{ $purpose->name }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Status Filter -->
        <div x-data="{ open: false, selected: '{{ request('status') }}', text: 'All Statuses' }" class="relative z-10" x-init="if('{{ request('status') }}' === 'active') text = 'Active (On Trip)'; if('{{ request('status') }}' === 'completed') text = 'Completed';">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status</label>
            <input type="hidden" name="status" :value="selected">
            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 rounded-xl text-left flex justify-between items-center focus:ring-2 focus:ring-primary-500">
                <span x-text="text" class="text-gray-900 dark:text-white"></span>
                <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></ion-icon>
            </button>
            <div x-show="open" x-transition.opacity class="absolute w-full mt-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl z-50">
                <div @click="selected = ''; text = 'All Statuses'; open = false" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">All Statuses</div>
                <div @click="selected = 'active'; text = 'Active (On Trip)'; open = false" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">Active (On Trip)</div>
                <div @click="selected = 'completed'; text = 'Completed'; open = false" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">Completed</div>
            </div>
        </div>

        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white font-bold rounded-xl shadow-sm transition-colors">
                Apply Filters
            </button>
            <a href="{{ route('manager.trips') }}" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl shadow-sm transition-colors flex items-center justify-center">
                <ion-icon name="refresh-outline" class="text-xl"></ion-icon>
            </a>
        </div>
    </form>
</div>

<!-- Trip List -->
<div class="space-y-4">
    @forelse($trips as $trip)
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between hover:shadow-md transition-shadow">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 {{ $trip->time_returned ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 animate-pulse' }}">
                    <ion-icon name="{{ $trip->time_returned ? 'checkmark-done-circle' : 'car' }}" class="text-2xl"></ion-icon>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $trip->driver->first_name ?? 'Unknown' }} <span class="text-gray-400 font-normal">to</span> {{ $trip->destination }}</h3>
                    <div class="flex flex-wrap items-center text-sm text-gray-500 dark:text-gray-400 mt-2 gap-y-2 gap-x-3">
                        <div class="flex items-center bg-gray-50 dark:bg-gray-700/50 px-2.5 py-1 rounded-md border border-gray-100 dark:border-gray-600">
                            <ion-icon name="calendar-outline" class="mr-1.5"></ion-icon> {{ $trip->created_at->format('M d, Y g:i A') }}
                        </div>
                        <div class="flex items-center bg-gray-50 dark:bg-gray-700/50 px-2.5 py-1 rounded-md border border-gray-100 dark:border-gray-600">
                            <ion-icon name="bus-outline" class="mr-1.5"></ion-icon> {{ $trip->vehicle->name ?? 'Unknown' }}
                        </div>
                        <div class="flex items-center bg-gray-50 dark:bg-gray-700/50 px-2.5 py-1 rounded-md border border-gray-100 dark:border-gray-600">
                            <ion-icon name="briefcase-outline" class="mr-1.5"></ion-icon> {{ $trip->display_purpose }}
                        </div>
                    </div>
                    
                    @if($trip->stops->count() > 0)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($trip->stops as $stop)
                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300 rounded-lg">
                                    {{ $stop->destination }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 md:mt-0 flex flex-col items-end">
                @if($trip->time_returned)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                        Completed at {{ \Carbon\Carbon::parse($trip->time_returned)->format('g:i A') }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span> In Progress
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700">
            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <ion-icon name="document-text-outline" class="text-4xl"></ion-icon>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No trips found</h3>
            <p class="text-gray-500 dark:text-gray-400">There are no trips matching these filters.</p>
        </div>
    @endforelse
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#date-picker", {
            dateFormat: "Y-m-d",
            allowInput: true,
            theme: document.documentElement.classList.contains('dark') ? "dark" : "light"
        });
    });
</script>
@endsection
