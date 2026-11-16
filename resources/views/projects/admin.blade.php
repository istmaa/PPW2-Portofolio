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
            <div class="filter-group filter-group-search">
                <label for="search" class="filter-label">Pencarian</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari judul atau deskripsi..."
                    class="form-control"
                >
            </div>

            <div class="filter-group filter-group-select">
                <label for="status" class="filter-label">Status</label>
                <select name="status" id="status" class="form-control filter-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div class="filter-group filter-group-select">
                <label for="technology" class="filter-label">Technology</label>
                <select name="technology" id="technology" class="form-control filter-select" onchange="this.form.submit()">
                    <option value="">Semua Technology</option>
                    @foreach ($technologies as $tech)
                        <option value="{{ $tech }}" {{ $technology === $tech ? 'selected' : '' }}>
                            {{ $tech }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group filter-group-sm">
                <label for="per_page" class="filter-label">Tampilkan</label>
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

    <div class="results-info-bar">
        <span>
            <strong>{{ $totalResults }}</strong> hasil ditemukan
            @if ($search || $status || $technology)
                <span class="badge-filter-active">filter aktif</span>
            @endif
        </span>
        @if ($search || $status || $technology || $perPage != 10)
            <a href="{{ route('post.projects') }}" class="btn btn-secondary btn-sm">
                Reset Filter
            </a>
        @endif
    </div>

    @if ($projects->isEmpty())
        <div class="card empty-state">
            <svg class="placeholder-icon" style="width: 2.5rem; height: 2.5rem; margin: 0 auto 0.75rem auto; display: block; opacity: 0.45;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <h3 style="font-size: 1.15rem; margin-bottom: 0.35rem; color: var(--color-text-main);">0 hasil ditemukan</h3>
            <p class="card-text" style="color: var(--color-text-muted); margin-bottom: 1rem;">
                Tidak ada project yang sesuai dengan kata kunci atau filter status yang dipilih.
            </p>
            <div>
                <a href="{{ route('post.projects') }}" class="btn btn-primary">Reset Filter</a>
            </div>
        </div>
    @else
        <div class="admin-table-card">
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="col-num">#</th>
                            <th class="col-project">Nama Proyek</th>
                            <th class="col-tech">Technology</th>
                            <th class="col-date">Project Date</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td class="col-num">
                                    <span class="admin-row-num">{{ $projects->firstItem() + $loop->index }}</span>
                                </td>
                                <td class="col-project">
                                    <div class="admin-project-cell">
                                        @if ($project->thumbnail)
                                            <a href="{{ route('projects.show', $project) }}" style="display: block; flex-shrink: 0;">
                                                <img
                                                    src="{{ asset('storage/' . $project->thumbnail) }}"
                                                    alt="{{ $project->title }}"
                                                    class="admin-thumb-mini"
                                                >
                                            </a>
                                        @else
                                            <div class="admin-thumb-mini admin-thumb-mini-empty">
                                                <svg class="admin-thumb-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="admin-project-info">
                                            <a href="{{ route('projects.show', $project) }}" class="admin-project-title">
                                                {{ $project->title }}
                                            </a>
                                            <p class="admin-project-desc">
                                                {{ \Illuminate\Support\Str::limit($project->description, 60) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-tech">
                                    @if ($project->technology)
                                        <span class="tag tech-badge">{{ $project->technology }}</span>
                                    @else
                                        <span class="text-muted-dash">&mdash;</span>
                                    @endif
                                </td>
                                <td class="col-date">
                                    @if ($project->project_date)
                                        <span class="project-date">{{ \Carbon\Carbon::parse($project->project_date)->format('d M Y') }}</span>
                                    @else
                                        <span class="text-muted-dash">&mdash;</span>
                                    @endif
                                </td>
                                <td class="col-status">
                                    @if ($project->status === 'published')
                                        <span class="badge-status badge-status-published">Published</span>
                                    @else
                                        <span class="badge-status badge-status-draft">Draft</span>
                                    @endif
                                </td>
                                <td class="col-actions">
                                    <div class="admin-actions-cell">
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary btn-sm" title="Lihat">
                                            Lihat
                                        </a>
                                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary btn-sm" title="Edit">
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('projects.destroy', $project) }}"
                                            method="POST"
                                            onsubmit="return confirm('Pindahkan project ini ke trash?');"
                                            style="margin: 0; display: inline;"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
