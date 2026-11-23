@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')

    <div class="page-header page-header-action">
        <div>
            <h1 class="page-title">{{ $contact->subject ?: '(Tanpa Subjek)' }}</h1>
            <p class="page-subtitle">Detail pesan masuk dari {{ $contact->name }}.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <a href="{{ route('post.contacts') }}" class="btn btn-secondary">Kembali ke Messages</a>
        </div>
    </div>

    <div class="card">
        <ul class="info-list" style="margin-bottom: 1.5rem;">
            <li class="info-item">
                <span class="info-label">Pengirim</span>
                <span class="info-value">{{ $contact->name }}</span>
            </li>
            <li class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $contact->email }}</span>
            </li>
            <li class="info-item">
                <span class="info-label">Subjek</span>
                <span class="info-value">{{ $contact->subject ?: '(Tanpa Subjek)' }}</span>
            </li>
            <li class="info-item">
                <span class="info-label">Waktu Kirim</span>
                <span class="info-value">{{ $contact->created_at->format('d M Y, H:i') }}</span>
            </li>
            <li class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value">
                    @if ($contact->is_read)
                        <span class="badge-status badge-status-published">Sudah dibaca</span>
                    @else
                        <span class="badge-status badge-status-unread">Belum dibaca</span>
                    @endif
                </span>
            </li>
        </ul>

        <div>
            <h2 class="card-title" style="font-size: 0.95rem; margin-bottom: 0.5rem;">Isi Pesan</h2>
            <div class="contact-message-body">{{ $contact->message }}</div>
        </div>
    </div>

@endsection
