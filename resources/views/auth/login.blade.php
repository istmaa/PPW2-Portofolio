<x-guest-layout>
    <x-slot:title>Masuk ke Akun</x-slot>

    <div class="page-header" style="margin-bottom: 1.5rem;">
        <h1 class="page-title">Masuk ke Akun</h1>
        <p class="page-subtitle">Masuk untuk mengakses fitur yang memerlukan autentikasi.</p>
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
        <form method="POST" action="{{ route('login') }}">
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
                    autocomplete="username"
                    placeholder="nama@example.com"
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; font-size: 0.88rem;">
                <label for="remember_me" style="display: inline-flex; align-items: center; gap: 0.45rem; cursor: pointer; color: var(--color-text-main); user-select: none;">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        style="width: 1rem; height: 1rem; cursor: pointer;"
                    >
                    <span>Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: var(--color-primary); text-decoration: none; font-weight: 500;">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <div class="btn-group" style="margin-top: 0;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
