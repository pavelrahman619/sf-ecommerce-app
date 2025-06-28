@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Financial Report</h2>

    <form method="GET" action="{{ route('report.index') }}" class="flex items-end gap-4 mb-6">
        <div>
            <label class="block text-sm mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </form>

    @if($totals->count())
        <table class="min-w-full border text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-3 border">Type</th>
                    <th class="py-2 px-3 border">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totals as $entry)
                    <tr>
                        <td class="py-2 px-3 border capitalize">{{ $entry->type }}</td>
                        <td class="py-2 px-3 border">৳ {{ number_format($entry->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No data found for the selected period.</p>
    @endif
</div>
@endsection
