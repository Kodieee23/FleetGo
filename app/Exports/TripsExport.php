<?php

namespace App\Exports;

use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->with(['driver', 'vehicle', 'purpose', 'department', 'stops'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date Logged',
            'Driver Name',
            'Vehicle',
            'Department',
            'Purpose',
            'Final Destination',
            'Stops',
            'Time Out',
            'Time Returned',
            'Status'
        ];
    }

    public function map($trip): array
    {
        $stops = $trip->stops->pluck('location')->implode(', ');
        $status = $trip->time_returned ? 'Completed' : 'On Trip';

        return [
            $trip->id,
            $trip->created_at->format('Y-m-d H:i:s'),
            $trip->driver->first_name ?? 'Unknown',
            $trip->vehicle->name ?? 'Unknown',
            $trip->department->name ?? 'Unknown',
            $trip->display_purpose,
            $trip->destination,
            $stops,
            \Carbon\Carbon::parse($trip->time_out)->format('H:i:s'),
            $trip->time_returned ? \Carbon\Carbon::parse($trip->time_returned)->format('H:i:s') : 'N/A',
            $status,
        ];
    }
}
