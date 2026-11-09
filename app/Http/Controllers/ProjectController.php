<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25], true)) {
            $perPage = 10;
        }

        $search = $request->input('search');

        $projects = Project::published()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('projects.index', compact('projects', 'perPage', 'search'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5',
            'description' => 'required|string|min:10',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }

    public function show(Project $project)
    {
        $project->load('images');

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5',
            'description' => 'required|string|min:10',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ]);

        if ($request->hasFile('thumbnail')) {
            $newThumbnail = $request->file('thumbnail')->store('projects/thumbnails', 'public');
            if ($project->thumbnail && Storage::disk('public')->exists($project->thumbnail)) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $validated['thumbnail'] = $newThumbnail;
        }

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project berhasil diperbarui.');
    }

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
        $project = Project::withTrashed()->with('images')->findOrFail($id);

        if ($project->thumbnail && Storage::disk('public')->exists($project->thumbnail)) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        foreach ($project->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $project->forceDelete();

        return redirect()
            ->route('projects.trash')
            ->with('success', 'Project berhasil dihapus permanen.');
    }
}
