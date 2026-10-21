@extends('layouts.app')

@section('title', 'Projects')

@section('content')

    <div class="page-header page-header-action">
        <div>
            <h1 class="page-title">Projects</h1>
            <p class="page-subtitle">Beberapa proyek yang pernah saya buat untuk tugas perkuliahan.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <a href="{{ route('projects.trash') }}" class="btn btn-secondary">Trash</a>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Tambah Project</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="projects-toolbar">
        <form action="{{ route('projects.index') }}" method="GET" class="projects-filter-form">
            <div class="filter-search-group">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari judul project..."
                    class="form-control"
                >
            </div>

            <div class="filter-select-group">
                <label for="per_page" class="filter-label">Tampilkan:</label>
                <select name="per_page" id="per_page" class="form-control filter-select" onchange="this.form.submit()">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if ($search || $perPage != 10)
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    @if ($projects->isEmpty())
        <div class="card empty-state">
            @if ($search)
                <p class="card-text">Tidak ada project yang sesuai dengan kata kunci "<strong>{{ $search }}</strong>".</p>
                <div style="margin-top: 1rem;">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Reset Pencarian</a>
                </div>
            @else
                <p class="card-text">Belum ada project.</p>
            @endif
        </div>
    @else
        <div class="card-grid">
            @foreach ($projects as $project)
                <div class="project-item">
                    <div class="project-item-content">
                        <div class="project-item-header">
                            <span class="tag">#{{ $projects->firstItem() + $loop->index }}</span>
                            <span class="tag {{ $project->status === 'published' ? 'tag-primary' : '' }}">{{ ucfirst($project->status) }}</span>
                        </div>
                        <h2 class="project-item-title">{{ $project->title }}</h2>
                        <p class="project-item-desc">{{ $project->description }}</p>
                    </div>
                    <div class="project-item-footer">
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            <div class="pagination-summary">
                <span>
                    Menampilkan <strong>{{ $projects->firstItem() }}</strong> &ndash; <strong>{{ $projects->lastItem() }}</strong> dari <strong>{{ $projects->total() }}</strong> project
                </span>
            </div>
            {{ $projects->links() }}
        </div>
    @endif

@endsection