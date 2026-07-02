{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Koperasi Anureksa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('image assets/SMKN 3.jpg') }}') no-repeat center center fixed; background-size: cover; min-height: 100vh;"
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

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-medium small">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control border-start-0 @error('email') is-invalid @enderror"
                            placeholder="email@koperasi.com" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" id="passwordInput"
                            class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                            placeholder="••••••••" required>
                        <button type="button" class="input-group-text bg-light" onclick="togglePwd()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>

                <div class="text-center">
                    <small class="text-muted">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-medium">Daftar di sini</a>
                    </small>
                </div>
            </form>
        </div>

        <div class="card-footer text-center bg-transparent border-top py-3">
            <small class="text-muted">© {{ date('Y') }} Koperasi Anureksa<small>
        </div>
    </div>

    <script>
        function togglePwd() {
            const inp = document.getElementById('passwordInput');
            const ico = document.getElementById('eyeIcon');
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
