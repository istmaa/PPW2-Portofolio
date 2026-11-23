@extends('layouts.app')

@section('title', 'Kontak')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Hubungi Saya</h1>
        <p class="page-subtitle">Kirimkan pesan, pertanyaan, atau tawaran kolaborasi melalui formulir di bawah ini.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('contact.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Nama lengkap Anda"
                    value="{{ old('name') }}"
                    required
                >
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="nama@example.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="subject" class="form-label">Subjek <span style="color: var(--color-text-muted); font-weight: 400;">(opsional)</span></label>
                <input
                    type="text"
                    id="subject"
                    name="subject"
                    class="form-control @error('subject') is-invalid @enderror"
                    placeholder="Topik pesan"
                    value="{{ old('subject') }}"
                >
                @error('subject')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="message" class="form-label">Pesan</label>
                <textarea
                    id="message"
                    name="message"
                    rows="5"
                    class="form-control @error('message') is-invalid @enderror"
                    placeholder="Tuliskan pesan Anda (minimal 10 karakter)..."
                    required
                >{{ old('message') }}</textarea>
                <small class="form-hint">Minimal 10 karakter</small>
                @error('message')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </div>
        </form>
    </div>

@endsection
