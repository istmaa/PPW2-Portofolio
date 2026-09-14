@extends('layouts.app')

@section('title', 'About')

@section('content')

    <div class="page-header">
        <h1 class="page-title">About</h1>
        <p class="page-subtitle">Informasi singkat tentang diri saya dan latar belakang studi.</p>
    </div>

    <div class="card">
        <h2 class="card-title">Profil Singkat</h2>
        <p class="card-text" style="margin-bottom: 0.75rem;">
            Perkenalkan, nama saya Haydar Istma Ulhaq. Saat ini saya adalah mahasiswa di program studi D4 Teknologi Rekayasa Perangkat Lunak, Universitas Gadjah Mada.
        </p>
        <p class="card-text">
            Saya memiliki ketertarikan di bidang full-stack development dan saat ini aktif mempelajari software development melalui mata kuliah dan tugas praktikum perkuliahan.
        </p>
    </div>

    <div class="card">
        <h2 class="card-title">Skills & Tools</h2>
        <p class="card-text" style="margin-bottom: 0.75rem;">
            Beberapa teknologi dan tools yang pernah dan sedang saya gunakan dalam perkuliahan:
        </p>
        <div class="tag-list">
            <span class="tag tag-primary">PHP</span>
            <span class="tag tag-primary">Laravel</span>
            <span class="tag">MySQL</span>
            <span class="tag">Java</span>
            <span class="tag">HTML & CSS</span>
            <span class="tag">Git & GitHub</span>
            <span class="tag">VS Code</span>
        </div>
    </div>

    <div class="card">
        <h2 class="card-title">Informasi Akademik</h2>
        <ul class="info-list">
            <li class="info-item">
                <span class="info-label">Nama</span>
                <span class="info-value">Haydar Istma Ulhaq</span>
            </li>
            <li class="info-item">
                <span class="info-label">Perguruan Tinggi</span>
                <span class="info-value">Universitas Gadjah Mada</span>
            </li>
            <li class="info-item">
                <span class="info-label">Program Studi</span>
                <span class="info-value">D4 Teknologi Rekayasa Perangkat Lunak</span>
            </li>
            <li class="info-item">
                <span class="info-label">Minat</span>
                <span class="info-value">Full-stack & Software Development</span>
            </li>
        </ul>
    </div>

@endsection