@extends('layouts.app')

@section('title', 'Admin Projects')

@section('content')

    <div class="page-header page-header-action">
        <div>
            <h1 class="page-title">Pengelolaan Project</h1>
            <p class="page-subtitle">Kelola dan pantau seluruh data project portofolio (Admin).</p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
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
        <form action="{{ route('post.projects') }}" method="GET" class="projects-filter-form">
            <div class="filter-search-group">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari judul atau deskripsi..."
                    class="form-control"
                >
            </div>

            <div class="filter-select-group">
                <label for="status" class="filter-label">Status:</label>
                <select name="status" id="status" class="form-control filter-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div class="filter-select-group">
                <label for="technology" class="filter-label">Technology:</label>
                <select name="technology" id="technology" class="form-control filter-select" onchange="this.form.submit()">
                    <option value="">Semua Technology</option>
                    @foreach ($technologies as $tech)
                        <option value="{{ $tech }}" {{ $technology === $tech ? 'selected' : '' }}>
                            {{ $tech }}
                        </option>
                    @endforeach
                </select>
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
                @if ($search || $status || $technology || $perPage != 10)
                    <a href="{{ route('post.projects') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="results-info-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; font-size: 0.9rem; color: var(--color-text-muted);">
        <span>
            <strong style="color: var(--color-text);">{{ $totalResults }}</strong> hasil ditemukan
            @if ($search || $status || $technology)
                <span style="font-size: 0.82rem; opacity: 0.85;">(filter aktif)</span>
            @endif
        </span>
        @if ($search || $status || $technology || $perPage != 10)
            <a href="{{ route('post.projects') }}" class="btn btn-secondary" style="font-size: 0.82rem; padding: 0.25rem 0.65rem;">
                Reset Filter
            </a>
        @endif
    </div>

    @if ($projects->isEmpty())
        <div class="card empty-state">
            <svg class="placeholder-icon" style="width: 2.5rem; height: 2.5rem; margin: 0 auto 0.75rem auto; display: block; opacity: 0.45;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <h3 style="font-size: 1.15rem; margin-bottom: 0.35rem; color: var(--color-text);">0 hasil ditemukan</h3>
            <p class="card-text" style="color: var(--color-text-muted); margin-bottom: 1rem;">
                Tidak ada project yang sesuai dengan kata kunci atau filter status yang dipilih.
            </p>
            <div>
                <a href="{{ route('post.projects') }}" class="btn btn-primary">Reset Filter</a>
            </div>
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
                            @if ($project->technology)
                                <span class="tag" style="background-color: #f1f5f9; color: #0f172a; border-color: #cbd5e1;">{{ $project->technology }}</span>
                            @endif
                            @if ($project->project_date)
                                <span class="tag" style="font-size: 0.75rem; color: var(--color-text-muted);">{{ \Carbon\Carbon::parse($project->project_date)->format('d M Y') }}</span>
                            @endif
                        </div>
                        <h2 class="project-item-title">{{ $project->title }}</h2>
                        <p class="project-item-desc">{{ $project->description }}</p>
                    </div>
                    <div class="project-item-footer" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                            Lihat
                        </a>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary">
                            Edit
                        </a>
                        <form
                            action="{{ route('projects.destroy', $project) }}"
                            method="POST"
                            onsubmit="return confirm('Pindahkan project ini ke trash?');"
                            style="margin: 0;"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Hapus
                            </button>
                        </form>
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
