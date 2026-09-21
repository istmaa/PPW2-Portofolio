@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Projects</h1>

        @forelse ($projects as $project)
            <div>
                <h2>{{ $project->title }}</h2>
                <p>{{ $project->description }}</p>

                <a href="{{ route('projects.show', $project) }}">
                    Lihat Detail
                </a>
            </div>

            <hr>
        @empty
            <p>Belum ada project.</p>
        @endforelse
    </div>
@endsection