@extends('layouts.app')

@section('title', 'Tambah Project')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Tambah Project</h1>
        <p class="page-subtitle">Tambahkan data project baru ke dalam portfolio perkuliahan.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title" class="form-label">Judul Project</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="Contoh: Website Portfolio Laravel"
                    value="{{ old('title') }}"
                    required
                >
                <small class="form-hint">Minimal 5 karakter</small>

                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Project</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="6"
                    placeholder="Tuliskan deskripsi ringkas tentang project, teknologi yang digunakan, atau tujuan pembuatan..."
                    required
                >{{ old('description') }}</textarea>
                <small class="form-hint">Minimal 10 karakter</small>

                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    Simpan Project
                </button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                    Kembali ke Projects
                </a>
            </div>
        </form>
    </div>

@endsection