@extends('layouts.app')
@section('title', 'Login')

@section('styles')
<style>
.auth-wrapper {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.auth-card {
    background: #fff;
    border-radius: 20px;
    padding: 2.5rem;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 8px 40px rgba(27,94,32,0.13);
    border: 1.5px solid var(--border);
}
.auth-logo {
    width: 64px; height: 64px;
    background: linear-gradient(135deg,var(--primary),var(--secondary));
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: #fff;
    margin: 0 auto 1.25rem;
}
.auth-title { text-align:center; font-weight:800; color:var(--primary); font-size:1.5rem; }
.auth-sub   { text-align:center; color:var(--text-muted); font-size:0.875rem; margin-bottom:1.75rem; }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo"><i class="fas fa-leaf"></i></div>
        <h2 class="auth-title">Masuk ke SragenLandWatch</h2>
        <p class="auth-sub">Masukkan email dan password Anda</p>

        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       required placeholder="••••••••">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success-custom w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>
        </form>

        <p class="text-center mt-3 mb-0" style="font-size:0.875rem;color:var(--text-muted);">
            Belum punya akun?
            <a href="/register" style="color:var(--secondary);font-weight:600;">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
