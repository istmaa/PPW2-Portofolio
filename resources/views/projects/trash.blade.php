@extends('layouts.app')

@section('title', 'Trash Projects')

@section('content')

    <div class="page-header page-header-action">
        <div>
            <h1 class="page-title">Trash Projects</h1>
            <p class="page-subtitle">Daftar project yang telah dihapus sementara (soft delete).</p>
        </div>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Kembali ke Projects</a>
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
            <p class="card-text">Trash kosong. Tidak ada project yang dihapus.</p>
        </div>
    @else
        <div class="card-grid">
            @foreach ($projects as $project)
                <div class="project-item">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.35rem;">
                            <h2 class="project-item-title" style="margin-bottom: 0;">{{ $project->title }}</h2>
                            <span class="tag {{ $project->status === 'published' ? 'tag-primary' : '' }}">{{ ucfirst($project->status) }}</span>
                        </div>
                        <p class="project-item-desc">{{ $project->description }}</p>
                    </div>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <form action="{{ route('projects.restore', $project) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-secondary">
                                Restore
                            </button>
                        </form>

                        <form
                            action="{{ route('projects.forceDelete', $project) }}"
                            method="POST"
                            onsubmit="return confirm('Apakah kamu yakin ingin menghapus project ini secara permanen?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete Permanently
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
