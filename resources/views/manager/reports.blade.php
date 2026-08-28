@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">System Reports</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Generate and export trip data.</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 mb-8 relative overflow-hidden">
    <!-- Decorative element -->
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-2xl pointer-events-none"></div>

    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 relative z-10">Export Trip Data</h3>
    
    <form method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">From Date</label>
            <input type="text" id="date-picker-1" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
        </div>
        
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">To Date</label>
            <input type="text" id="date-picker-2" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
        </div>

        <div class="flex space-x-3 md:col-span-2">
            <button type="submit" formaction="{{ route(auth()->user()->role . '.reports.export.excel') }}" class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                <ion-icon name="document-text-outline" class="mr-2 text-xl"></ion-icon> Excel (.xlsx)
            </button>
            <button type="submit" formaction="{{ route(auth()->user()->role . '.reports.export.pdf') }}" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                <ion-icon name="document-outline" class="mr-2 text-xl"></ion-icon> PDF
            </button>
        </div>
    </form>
</div>

<!-- Preview Table -->
<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Data Preview</h3>
    </div>
    
    <div class="p-6">
        @if($trips->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-3 px-4">Date</th>
                        <th class="pb-3 px-4">Driver</th>
                        <th class="pb-3 px-4">Destination</th>
                        <th class="pb-3 px-4">Vehicle</th>
                        <th class="pb-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($trips as $trip)
                    <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-4 px-4 text-gray-600 dark:text-gray-300">{{ $trip->created_at->format('M d, Y') }}</td>
                        <td class="py-4 px-4 font-bold text-gray-900 dark:text-white">{{ $trip->driver->first_name ?? 'Unknown' }}</td>
                        <td class="py-4 px-4 text-gray-600 dark:text-gray-300">{{ Str::limit($trip->destination, 20) }}</td>
                        <td class="py-4 px-4 text-gray-600 dark:text-gray-300">{{ $trip->vehicle->name ?? 'N/A' }}</td>
                        <td class="py-4 px-4">
                            @if($trip->time_returned)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Completed</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">On Trip</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $trips->links() }}
        </div>
        @else
        <div class="text-center py-10">
            <ion-icon name="document-text-outline" class="text-5xl text-gray-300 dark:text-gray-600 mb-4"></ion-icon>
            <p class="text-gray-500 dark:text-gray-400">No trips found for the selected date range.</p>
        </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("input[type=text][name=date_from]", { dateFormat: "Y-m-d", allowInput: true });
        flatpickr("input[type=text][name=date_to]", { dateFormat: "Y-m-d", allowInput: true });
    });
</script>
@endsection
