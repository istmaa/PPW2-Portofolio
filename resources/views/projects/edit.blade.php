@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Edit Project</h1>
        <p class="page-subtitle">Perbarui data project pada portfolio perkuliahan.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">Judul Project</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $project->title) }}"
                    required
                >
                <small class="form-hint">Minimal 5 karakter</small>

                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Project</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="6"
                    required
                >{{ old('description', $project->description) }}</textarea>
                <small class="form-hint">Minimal 10 karakter</small>

                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @if ($project->thumbnail)
                <div class="form-group">
                    <label class="form-label">Thumbnail Saat Ini</label>
                    <div style="margin-bottom: 0.5rem;">
                        <img
                            src="{{ asset('storage/' . $project->thumbnail) }}"
                            alt="{{ $project->title }}"
                            style="max-width: 240px; height: auto; border-radius: var(--radius-sm); border: 1px solid var(--color-border); display: block;"
                        >
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="thumbnail" class="form-label">{{ $project->thumbnail ? 'Ganti Thumbnail' : 'Upload Thumbnail' }}</label>
                <input
                    type="file"
                    id="thumbnail"
                    name="thumbnail"
                    class="form-control @error('thumbnail') is-invalid @enderror"
                    accept="image/*"
                >
                <small class="form-hint">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB, dimensi minimal 400x300 px. Biarkan kosong jika tidak ingin mengubah thumbnail.</small>

                @error('thumbnail')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select
                    id="status"
                    name="status"
                    class="form-control @error('status') is-invalid @enderror"
                    required
                >
                    <option value="draft" {{ old('status', $project->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $project->status) === 'published' ? 'selected' : '' }}>Published</option>
                </select>

                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>

        <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <span style="font-weight: 600; font-size: 0.92rem; color: var(--color-text);">Gallery Project</span>
                <p style="margin: 0.2rem 0 0 0; font-size: 0.82rem; color: var(--color-text-muted);">
                    Project ini memiliki {{ $project->images->count() }} gambar dalam gallery.
                </p>
            </div>
            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary" style="font-size: 0.85rem;">
                Buka Detail & Kelola Gallery
            </a>
        </div>
    </div>

@endsection
