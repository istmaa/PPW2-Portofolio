<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectGalleryController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:min_width=400,min_height=300',
        ]);

        $currentSort = (int) $project->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $path = $file->store('projects/gallery', 'public');
            $currentSort++;
            $project->images()->create([
                'image' => $path,
                'sort_order' => $currentSort,
            ]);
        }

        return back()->with('success', 'Gambar gallery berhasil diunggah.');
    }

    public function destroy(Project $project, ProjectImage $image): RedirectResponse
    {
        abort_if($image->project_id !== $project->id, 404);

        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('success', 'Gambar gallery berhasil dihapus.');
    }
}
