@extends('layouts.app')

@section('title', $project->title)

@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $project->title }}</h1>
        <p class="page-subtitle">Detail informasi tentang project perkuliahan.</p>
    </div>

    <div class="card">
        <h2 class="card-title">Deskripsi Project</h2>
        <p class="card-text" style="white-space: pre-line;">{{ $project->description }}</p>
    </div>

    <div class="btn-group">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Kembali ke Projects</a>
    </div>

@endsection