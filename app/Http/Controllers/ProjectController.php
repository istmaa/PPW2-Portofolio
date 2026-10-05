<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::published()
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5',
            'description' => 'required|string|min:10',
            'status' => 'required|in:draft,published',
        ]);

        $project = new Project();

        $project->title = $validated['title'];
        $project->description = $validated['description'];
        $project->status = $validated['status'];

        $project->save();

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5',
            'description' => 'required|string|min:10',
            'status' => 'required|in:draft,published',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function trash()
    {
        $projects = Project::onlyTrashed()
            ->latest()
            ->get();

        return view('projects.trash', compact('projects'));
    }

    public function restore($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $project->restore();

        return redirect()
            ->route('projects.trash')
            ->with('success', 'Project berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $project->forceDelete();

        return redirect()
            ->route('projects.trash')
            ->with('success', 'Project berhasil dihapus permanen.');
    }
}
