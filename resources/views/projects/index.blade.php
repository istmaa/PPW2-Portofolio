@extends('layouts.app')

@section('title', 'Projects')

@section('content')

    <div class="page-header page-header-action">
        <div>
            <h1 class="page-title">Projects</h1>
            <p class="page-subtitle">Beberapa proyek yang pernah saya buat untuk tugas perkuliahan.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Tambah Project</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($projects->isEmpty())
        <div class="card empty-state">
            <p class="card-text">Belum ada project.</p>
        </div>
    @else
        <div class="card-grid">
            @foreach ($projects as $project)
                <div class="project-item">
                    <div>
                        <h2 class="project-item-title">{{ $project->title }}</h2>
                        <p class="project-item-desc">{{ $project->description }}</p>
                    </div>
                    <div>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection