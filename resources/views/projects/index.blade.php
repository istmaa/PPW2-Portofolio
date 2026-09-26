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
            {{ session('success') }}
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