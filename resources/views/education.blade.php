@extends('layouts.app')

@section('title', 'Education')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Education</h1>
        <p class="page-subtitle">Riwayat pendidikan formal yang telah dan sedang saya tempuh.</p>
    </div>

    <div class="education-item">
        <div class="education-year">2024 &mdash; Sekarang</div>
        <h2 class="card-title">Universitas Gadjah Mada</h2>
        <div class="card-subtitle">D4 Teknologi Rekayasa Perangkat Lunak</div>
        <p class="card-text">
            Saat ini sedang menempuh perkuliahan di program studi D4 Teknologi Rekayasa Perangkat Lunak. Fokus pembelajaran meliputi dasar-dasar pemrograman, struktur data, basis data, dan pengembangan aplikasi web.
        </p>
    </div>

    <div class="education-item">
        <div class="education-year">2021 &mdash; 2024</div>
        <h2 class="card-title">SMA Negeri 1 Pekalongan</h2>
        <div class="card-subtitle" style="color: var(--color-text-muted);">Kurikulum Merdeka</div>
        <p class="card-text">
            Menyelesaikan pendidikan menengah atas dengan Kurikulum Merdeka, dengan fokus peminatan yang mengarah ke bidang sains/IPA.
        </p>
    </div>

@endsection