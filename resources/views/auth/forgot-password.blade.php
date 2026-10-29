<x-guest-layout>
    <x-slot:title>Lupa Kata Sandi</x-slot>

    <div class="page-header" style="margin-bottom: 1.5rem;">
        <h1 class="page-title">Lupa Kata Sandi?</h1>
        <p class="page-subtitle">Masukkan alamat email akunmu untuk meminta tautan pengaturan ulang kata sandi.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success" style="margin-bottom: 1.25rem;">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="form-card">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="nama@example.com"
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="btn-group" style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    Kembali ke Login
                </a>
                <button type="submit" class="btn btn-primary">
                    Kirim Tautan Reset
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
