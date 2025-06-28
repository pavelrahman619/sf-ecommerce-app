<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');

        $query = JournalEntry::query();

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $totals = $query->select('type', DB::raw('SUM(amount) as total'))
                        ->groupBy('type')
                        ->orderBy('type')
                        ->get();

        return view('reports.index', compact('totals', 'from', 'to'));
    }
}
