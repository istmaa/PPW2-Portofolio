@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Profil Saya</h1>
        <p class="page-subtitle">Informasi akun pengguna yang sedang login saat ini.</p>
    </div>

    <div class="card">
        <h2 class="card-title">Informasi Akun</h2>
        <p class="card-text" style="margin-bottom: 1rem;">
            Data autentikasi akun Anda yang tercatat di sistem:
        </p>
        <ul class="info-list">
            <li class="info-item">
                <span class="info-label">Nama Pengguna</span>
                <span class="info-value">{{ $user->name }}</span>
            </li>
            <li class="info-item">
                <span class="info-label">Alamat Email</span>
                <span class="info-value">{{ $user->email }}</span>
            </li>
            <li class="info-item">
                <span class="info-label">Role Akun</span>
                <span class="info-value">
                    <span class="tag {{ $user->role === 'admin' ? 'tag-primary' : '' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </span>
            </li>
            <li class="info-item">
                <span class="info-label">Terdaftar Sejak</span>
                <span class="info-value">{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</span>
            </li>
        </ul>
    </div>

    <div class="btn-group">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
            Lihat Projects
        </a>
    </div>

@endsection

