@extends('layouts.app')

@section('title', 'Messages - Admin Contacts')

@section('content')

    <div class="page-header page-header-action">
        <div>
            <h1 class="page-title">Messages</h1>
            <p class="page-subtitle">Daftar pesan yang masuk melalui halaman kontak.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('post.dashboard') }}" class="btn btn-secondary">Dashboard</a>
            <a href="{{ route('contact') }}" class="btn btn-secondary">Form Kontak</a>
        </div>
    </div>

    <div class="results-info-bar">
        <span>
            Total <strong>{{ $contacts->total() }}</strong> pesan
            @if ($unreadCount > 0)
                <span class="badge-filter-active">{{ $unreadCount }} belum dibaca</span>
            @endif
        </span>
    </div>

    @if ($contacts->isEmpty())
        <div class="card empty-state">
            <h3 style="font-size: 1.15rem; margin-bottom: 0.35rem; color: var(--color-text-main);">Belum ada pesan masuk</h3>
            <p class="card-text" style="color: var(--color-text-muted);">
                Pesan yang dikirim melalui halaman <code>/contact</code> akan muncul di sini.
            </p>
        </div>
    @else
        <div class="admin-table-card">
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="col-num">#</th>
                            <th>Pengirim</th>
                            <th>Subjek</th>
                            <th class="col-date">Waktu Kirim</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr class="{{ ! $contact->is_read ? 'row-unread' : '' }}">
                                <td class="col-num">
                                    <span class="admin-row-num">{{ $contacts->firstItem() + $loop->index }}</span>
                                </td>
                                <td>
                                    <div class="admin-project-info">
                                        <a href="{{ route('post.contacts.show', $contact) }}" class="admin-project-title">
                                            {{ $contact->name }}
                                        </a>
                                        <span class="admin-project-desc">{{ $contact->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span>{{ $contact->subject ?: '(Tanpa Subjek)' }}</span>
                                </td>
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
                                    <a href="{{ route('post.contacts.show', $contact) }}" class="btn btn-secondary btn-sm">
                                        Buka
                                    </a>
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
                    Menampilkan <strong>{{ $contacts->firstItem() }}</strong> &ndash; <strong>{{ $contacts->lastItem() }}</strong> dari <strong>{{ $contacts->total() }}</strong> pesan
                </span>
            </div>
            {{ $contacts->links() }}
        </div>
    @endif

@endsection
