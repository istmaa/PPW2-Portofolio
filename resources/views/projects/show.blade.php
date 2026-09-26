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

    <div class="card">
        <h2 class="card-title">Deskripsi Project</h2>
        <p class="card-text" style="white-space: pre-line;">{{ $project->description }}</p>
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