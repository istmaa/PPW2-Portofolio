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
                    class="form-control"
                    placeholder="Contoh: Website Portfolio Laravel"
                    required
                >
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Project</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="6"
                    placeholder="Tuliskan deskripsi ringkas tentang project, teknologi yang digunakan, atau tujuan pembuatan..."
                    required
                ></textarea>
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