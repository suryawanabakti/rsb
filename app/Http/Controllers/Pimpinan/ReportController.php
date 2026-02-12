<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use App\Models\LabResult;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'letter_requests');
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $data = [];
        if ($type === 'letter_requests') {
            $data = LetterRequest::with(['patient.user', 'letterType'])
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->latest()
                ->paginate(20);
        } else {
            $data = LabResult::with(['patient.user', 'validator'])
                ->whereBetween('test_date', [$startDate, $endDate])
                ->latest()
                ->paginate(20);
        }

        return view('pimpinan.reports.index', compact('data', 'type', 'startDate', 'endDate'));
    }
}
