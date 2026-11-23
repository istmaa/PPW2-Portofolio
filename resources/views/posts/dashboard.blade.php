@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="page-header page-header-action">
        <div>
            <h1 class="page-title">Dashboard Admin</h1>
            <p class="page-subtitle">Ringkasan data proyek, kategori, dan pesan masuk portofolio.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('post.projects') }}" class="btn btn-secondary">Manage Projects</a>
            <a href="{{ route('post.contacts') }}" class="btn btn-primary">Lihat Messages</a>
        </div>
    </div>

    <div class="stat-grid">
        <x-stat-card
            title="Total Projects"
            :value="$totalProjects"
            subtitle="Seluruh data proyek"
            color="blue"
            :href="route('post.projects')"
        />

        <x-stat-card
            title="Published"
            :value="$publishedProjects"
            subtitle="Tampil di halaman publik"
            color="green"
            :href="route('post.projects', ['status' => 'published'])"
        />

        <x-stat-card
            title="Draft"
            :value="$draftProjects"
            subtitle="Belum dipublikasikan"
            color="amber"
            :href="route('post.projects', ['status' => 'draft'])"
        />

        <x-stat-card
            title="Categories"
            :value="$totalCategories"
            subtitle="Kategori proyek API"
            color="slate"
        />

        <x-stat-card
            title="Messages"
            :value="$totalMessages"
            subtitle="Total pesan masuk"
            color="indigo"
            :href="route('post.contacts')"
        />

        <x-stat-card
            title="Unread Messages"
            :value="$unreadMessages"
            subtitle="Pesan belum dibaca"
            color="rose"
            :href="route('post.contacts')"
        />
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; gap: 0.75rem; flex-wrap: wrap;">
            <div>
                <h2 class="card-title" style="margin-bottom: 0.15rem;">Pesan Terbaru</h2>
                <p class="card-text" style="font-size: 0.85rem;">Lima pesan terakhir yang dikirim melalui formulir kontak.</p>
            </div>
            <a href="{{ route('post.contacts') }}" class="btn btn-secondary btn-sm">Semua Pesan</a>
        </div>

        @if ($recentContacts->isEmpty())
            <p class="card-text" style="color: var(--color-text-muted);">Belum ada pesan masuk.</p>
        @else
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Pengirim</th>
                            <th>Subjek</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th class="col-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentContacts as $contact)
                            <tr>
                                <td>
                                    <div class="admin-project-info">
                                        <span class="admin-project-title">{{ $contact->name }}</span>
                                        <span class="admin-project-desc">{{ $contact->email }}</span>
                                    </div>
                                </td>
                                <td>{{ $contact->subject ?: '(Tanpa Subjek)' }}</td>
                                <td class="col-date">
                                    <span class="project-date">{{ $contact->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="col-status">
                                    @if ($contact->is_read)
                                        <span class="badge-status badge-status-published">Sudah dibaca</span>
                                    @else
                                        <span class="badge-status badge-status-unread">Belum dibaca</span>
                                    @endif
                                </td>
                                <td class="col-actions">
                                    <a href="{{ route('post.contacts.show', $contact) }}" class="btn btn-secondary btn-sm">Buka</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
