<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        if (!$from || !$to) {
            return response()->json(['error' => 'Date range "from" and "to" are required.'], 422);
        }

        // Filter by client
        if ($request->input('client_id')) {
            $clientId = $request->input('client_id');
            $projects = Project::where('client_id', $clientId)->pluck('id');
    
            $logs = TimeLog::whereIn('project_id', $projects)
                ->whereBetween('start_time', [$from, $to])
                ->get();

            $totalHours = $logs->sum('hours');

            return response()->json([
                'client_id' => $clientId,
                'from' => $from,
                'to' => $to,
                'total_hours' => round($totalHours, 2),
            ]);
        }

        // Per Project
        if ($request->input('project_id')) {
            $projectId = $request->input('project_id');
            $logs = TimeLog::where('project_id', $projectId)
                ->whereBetween('start_time', [$from, $to])
                ->get();
            
            $totalHours = $logs->sum('hours');
            
            return response()->json([
                'project_id' => $projectId,
                'total_hours' => round($totalHours, 2),
            ]);
        }

        // Per Day
        if ($request->input('per_day') === 'true') {
            $logs = TimeLog::whereBetween('start_time', [$from, $to])
                ->get()
                ->groupBy(function ($log) {
                    return Carbon::parse($log->start_time)->toDateString();
                });

            $result = [];
            foreach ($logs as $date => $items) {
                $result[] = [
                    'date' => $date,
                    'total_hours' => round($items->sum('hours'), 2),
                ];
            }

            return response()->json($result);
        }

        return response()->json(['error' => 'Invalid Data'], 400);
    }
}
