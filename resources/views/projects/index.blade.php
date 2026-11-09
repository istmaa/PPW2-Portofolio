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
                    @if ($project->thumbnail)
                        <a href="{{ route('projects.show', $project) }}" style="display: block;">
                            <img
                                src="{{ asset('storage/' . $project->thumbnail) }}"
                                alt="{{ $project->title }}"
                                class="project-card-thumb"
                            >
                        </a>
                    @else
                        <a href="{{ route('projects.show', $project) }}" class="project-card-thumb-empty">
                            <div class="project-thumb-placeholder">
                                <svg class="placeholder-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                <span>Belum ada thumbnail</span>
                            </div>
                        </a>
                    @endif
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
