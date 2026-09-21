@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tambah Project</h1>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 15px;">
                <label for="title">Judul Project</label>
                <br>
                <input
                    type="text"
                    id="title"
                    name="title"
                    required
                    style="width: 100%; padding: 10px; margin-top: 5px;"
                >
            </div>

            <div style="margin-bottom: 15px;">
                <label for="description">Deskripsi Project</label>
                <br>
                <textarea
                    id="description"
                    name="description"
                    required
                    rows="6"
                    style="width: 100%; padding: 10px; margin-top: 5px;"
                ></textarea>
            </div>

            <button
                type="submit"
                style="padding: 10px 20px; cursor: pointer;"
            >
                Simpan Project
            </button>
        </form>
    </div>
@endsection