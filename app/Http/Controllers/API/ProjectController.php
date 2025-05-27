<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('client')->get();
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:active,completed',
            'deadline' => 'required|date',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'status' => $request->status,
            'deadline' => $request->deadline
        ]);

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::find($id);
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:active,completed',
            'deadline' => 'required|date',
        ]);

        $project = Project::findOrFail($id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'status' => $request->status,
            'deadline' => $request->deadline
        ]);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);
        $project->delete();
        return response()->noContent();
    }
}
