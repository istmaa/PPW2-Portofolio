@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <section class="intro-box">
        <h1 class="intro-name">Haydar Istma Ulhaq</h1>
        <div class="intro-role">D4 Teknologi Rekayasa Perangkat Lunak &mdash; Universitas Gadjah Mada</div>
        <p class="intro-desc">
            Halo, selamat datang di website portfolio saya. Saya adalah mahasiswa D4 Teknologi Rekayasa Perangkat Lunak di Universitas Gadjah Mada. Saya memiliki minat pada bidang full-stack development dan saat ini sedang fokus mempelajari software development.
        </p>
        <p class="intro-desc">
            Website ini dibuat untuk memenuhi tugas praktikum pemrograman web menggunakan framework Laravel.
        </p>

        <div class="btn-group">
            <a href="{{ route('projects') }}" class="btn btn-primary">Lihat Proyek</a>
            <a href="{{ route('about') }}" class="btn btn-secondary">Tentang Saya</a>
        </div>
    </section>

    <section>
        <div class="card-grid">
            <div class="card">
                <h2 class="card-title">Pendidikan</h2>
                <div class="card-subtitle">Universitas Gadjah Mada</div>
                <p class="card-text">
                    Sedang menempuh studi di program D4 Teknologi Rekayasa Perangkat Lunak.
                </p>
            </div>

            <div class="card">
                <h2 class="card-title">Minat</h2>
                <div class="card-subtitle">Full-stack Development</div>
                <p class="card-text">
                    Tertarik pada pengembangan web dan sedang mempelajari dasar-dasar software development.
                </p>
            </div>

            <div class="card">
                <h2 class="card-title">Proyek</h2>
                <div class="card-subtitle">Tugas Perkuliahan</div>
                <p class="card-text">
                    Berisi beberapa tugas dan proyek praktikum yang pernah saya kerjakan selama kuliah.
                </p>
            </div>
        </div>
    </section>

@endsection