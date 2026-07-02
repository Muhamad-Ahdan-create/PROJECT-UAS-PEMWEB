{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Koperasi Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('image assets/SMKN 3.jpg') }}') no-repeat center center fixed; background-size: cover; min-height: 100vh;"
    class="d-flex align-items-center justify-content-center"></body>

<div class="card shadow-lg border-0" style="width: 100%; max-width: 420px; border-radius: 16px;">
    <div class="card-body p-5">

        {{-- Logo / Brand --}}
        <div class="text-center mb-4">
            <img src="{{ asset('image assets/Logo.png') }}" alt="KP-RI Anureksa Kuningan"
                style="width: 90px; height: 90px; object-fit: contain;" class="mb-3">
            <h4 class="fw-bold mb-0">Koperasi Anureksa</h4>
            <p class="text-muted small">Masuk ke akun Anda</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label fw-medium small">Nama</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="form-control border-start-0 @error('name') is-invalid @enderror"
                        placeholder="Nama lengkap" required autofocus autocomplete="name">
                </div>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label fw-medium small">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                        placeholder="email@koperasi.com" required autocomplete="username">
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label fw-medium small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="passwordInput"
                        class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                        placeholder="••••••••" required autocomplete="new-password">
                    <button type="button" class="input-group-text bg-light"
                        onclick="togglePwd('passwordInput', 'eyeIcon1')">
                        <i class="bi bi-eye" id="eyeIcon1"></i>
                    </button>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-4">
                <label class="form-label fw-medium small">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock-fill text-muted"></i>
                    </span>
                    <input type="password" name="password_confirmation" id="passwordConfirmInput"
                        class="form-control border-start-0 border-end-0" placeholder="••••••••" required
                        autocomplete="new-password">
                    <button type="button" class="input-group-text bg-light"
                        onclick="togglePwd('passwordConfirmInput', 'eyeIcon2')">
                        <i class="bi bi-eye" id="eyeIcon2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                <i class="bi bi-person-plus me-2"></i>Daftar
            </button>

            <div class="text-center">
                <small class="text-muted">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Masuk di sini</a>
                </small>
            </div>
        </form>
    </div>

    <div class="card-footer text-center bg-transparent border-top py-3">
        <small class="text-muted">© {{ date('Y') }} Koperasi Anureksa</small>
    </div>
</div>

<script>
    function togglePwd(inputId, iconId) {
        const inp = document.getElementById(inputId);
        const ico = document.getElementById(iconId);
        if (inp.type === 'password') {
            inp.type = 'text';
            ico.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password';
            ico.className = 'bi bi-eye';
        }
    }
</script>

</body>

</html>
