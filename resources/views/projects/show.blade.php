@extends('layouts.app')

@section('title', $project->title)

@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $project->title }}</h1>
        <p class="page-subtitle">Detail informasi tentang project perkuliahan.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($project->thumbnail)
        <img
            src="{{ asset('storage/' . $project->thumbnail) }}"
            alt="{{ $project->title }}"
            class="project-detail-thumb"
        >
    @endif

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <h2 class="card-title" style="margin: 0;">Deskripsi Project</h2>
            <span class="tag {{ $project->status === 'published' ? 'tag-primary' : '' }}">{{ ucfirst($project->status) }}</span>
        </div>
        <p class="card-text" style="white-space: pre-line;">{{ $project->description }}</p>
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <h2 class="card-title" style="margin: 0;">Gallery Project</h2>
            <span class="tag">{{ $project->images->count() }} Gambar</span>
        </div>

        @auth
            @if (Auth::user()->role === 'admin')
                <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--color-border);">
                    <form action="{{ route('projects.gallery.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group" style="margin-bottom: 0.75rem;">
                            <label for="gallery_images" class="form-label">Upload Gambar Gallery</label>
                            <input
                                type="file"
                                id="gallery_images"
                                name="images[]"
                                class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                accept="image/*"
                                multiple
                                required
                            >
                            <small class="form-hint">Dapat memilih beberapa file sekaligus (JPG, PNG, WEBP, maksimal 4MB per file, minimal 400x300 px).</small>

                            @error('images')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @error('images.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Upload ke Gallery
                        </button>
                    </form>
                </div>
            @endif
        @endauth

        @if ($project->images->isEmpty())
            <p class="card-text">Belum ada gambar dalam gallery project ini.</p>
        @else
            <div class="gallery-grid">
                @foreach ($project->images as $img)
                    <div class="gallery-card">
                        <a href="{{ asset('storage/' . $img->image) }}" target="_blank">
                            <img
                                src="{{ asset('storage/' . $img->image) }}"
                                alt="{{ $img->caption ?: $project->title }}"
                                class="gallery-card-img"
                            >
                        </a>
                        <div class="gallery-card-body">
                            <span class="gallery-card-num">Gambar #{{ $loop->iteration }}</span>
                            @auth
                                @if (Auth::user()->role === 'admin')
                                    <form
                                        action="{{ route('projects.gallery.destroy', [$project, $img]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus gambar ini dari gallery?');"
                                        style="margin: 0;"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.55rem; font-size: 0.78rem;">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="btn-group">
        <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary">
            Edit Project
        </a>

        <form
            action="{{ route('projects.destroy', $project) }}"
            method="POST"
            onsubmit="return confirm('Apakah kamu yakin ingin menghapus project ini?');"
        >
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">
                Hapus Project
            </button>
        </form>

        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
            Kembali ke Projects
        </a>
    </div>

@endsection
