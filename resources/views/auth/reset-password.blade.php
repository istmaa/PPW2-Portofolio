<x-guest-layout>
    <x-slot:title>Atur Ulang Kata Sandi</x-slot>

    <div class="page-header" style="margin-bottom: 1.5rem;">
        <h1 class="page-title">Atur Ulang Kata Sandi</h1>
        <p class="page-subtitle">Masukkan kata sandi baru untuk akun Anda.</p>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi Baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >
                @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="btn-group" style="margin-top: 1.25rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Simpan Kata Sandi Baru
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
