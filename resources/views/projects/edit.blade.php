@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Edit Project</h1>
        <p class="page-subtitle">Perbarui data project pada portfolio perkuliahan.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">Judul Project</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $project->title) }}"
                    required
                >

                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Project</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="6"
                    required
                >{{ old('description', $project->description) }}</textarea>

                @error('description')
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
    </div>

@endsection