@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>SPP</h1>
            <p>Saudara Plafon PVC Meteseh</p>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" required autofocus>
                @error('username')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input-group">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    <button type="button" class="btn-toggle-password" onclick="togglePassword()"><i class="icon-eye"></i></button>
                </div>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-check">
                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Ingat saya</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const btn = document.querySelector('.btn-toggle-password');
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="icon-eye-off"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="icon-eye"></i>';
    }
}
</script>
@endsection
