@extends('layouts.app')

@section('title', 'Projects')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Projects</h1>
        <p class="page-subtitle">Beberapa proyek yang pernah saya buat untuk tugas perkuliahan.</p>
    </div>

    <div class="card-grid">
        <div class="project-item">
            <div>
                <h2 class="project-item-title">Steam Rent</h2>
                <div class="card-subtitle">Web Project</div>
                <p class="project-item-desc">
                    Proyek website rental game yang dibuat menggunakan PHP dan MySQL untuk tugas praktikum.
                </p>
            </div>
            <div class="tag-list">
                <span class="tag">PHP</span>
                <span class="tag">MySQL</span>
                <span class="tag">HTML & CSS</span>
            </div>
        </div>

        <div class="project-item">
            <div>
                <h2 class="project-item-title">Fakultas Sihir UGM</h2>
                <div class="card-subtitle">Java Game Project</div>
                <p class="project-item-desc">
                    Proyek game berbasis Java yang dibuat untuk tugas perkuliahan Pemrograman Berorientasi Objek.
                </p>
            </div>
            <div class="tag-list">
                <span class="tag">Java</span>
                <span class="tag">OOP</span>
            </div>
        </div>

        <div class="project-item">
            <div>
                <h2 class="project-item-title">Website Portfolio</h2>
                <div class="card-subtitle">Laravel Project</div>
                <p class="project-item-desc">
                    Website portfolio sederhana yang dibuat menggunakan Laravel dan Blade untuk tugas praktikum pemrograman web.
                </p>
            </div>
            <div class="tag-list">
                <span class="tag tag-primary">Laravel</span>
                <span class="tag">Blade</span>
                <span class="tag">Vite</span>
            </div>
        </div>
    </div>

@endsection