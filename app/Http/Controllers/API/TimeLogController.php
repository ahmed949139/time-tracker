<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeLogController extends Controller
{
    public function startTimeLog(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'description' => 'required|string',
        ]);

        $log = TimeLog::create([
            'project_id' => $request->project_id,
            'start_time' => $request->start_time,
            'description' => $request->description
        ]);

        return response()->json($log, 201);
    }

    public function endTimeLog($id)
    {
        $log = TimeLog::findOrFail($id)->update([
            'end_time' => now()
        ]);

        return response()->json($log);        
    }

    public function timeLogs(Request $request)
    {
        $user = auth()->user();
    
        $query = TimeLog::whereHas('project.client', function ($q) use ($user) {
            $q->where('contact_person', $user->id);
        });
    
        if ($request->has('date')) {
            $date = Carbon::parse($request->input('date'));
            $query->whereDate('start_time', $date);
        }
    
        if ($request->has('week')) {
            $date = Carbon::parse($request->input('week'));
            $query->whereBetween('start_time', [
                $date->startOfWeek(),
                $date->endOfWeek()
            ]);
        }
    
        $logs = $query->with('project.client')->get();
    
        return response()->json($logs);
    }
    
    public function exportPdf()
    {
        $logs = TimeLog::with('project.client')->get();
    
        $pdf = Pdf::loadView('pdf.time_logs', compact('logs'));
        return $pdf->download('time_logs.pdf');
    }    
}
