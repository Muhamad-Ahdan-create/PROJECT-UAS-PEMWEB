{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin — Koperasi Sekolah' }}</title>
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/koperasi.css') }}">
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('image assets/Logo.png') }}" alt="KP-RI Anureksa"
                style="width: 32px; height: 32px; object-fit: contain;" class="me-2">
            Koperasi Anureksa
        </div>
        <nav class="flex-grow-1 py-2">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.anggota.index') }}"
                class="nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Anggota
            </a>
            <a href="{{ route('admin.pinjaman.index') }}"
                class="nav-link {{ request()->routeIs('admin.pinjaman.*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i> Pinjaman
            </a>
            <a href="{{ route('admin.pengajuan.index') }}"
                class="nav-link {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Pengajuan
            </a>
            <a href="{{ route('admin.rat.index') }}"
                class="nav-link {{ request()->routeIs('admin.rat.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> RAT
            </a>
            <a href="{{ route('admin.siswa-baru.index') }}"
                class="nav-link {{ request()->routeIs('admin.siswa-baru.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard"></i> Siswa Baru
            </a>
            <a href="{{ route('admin.seragam.index') }}"
                class="nav-link {{ request()->routeIs('admin.seragam.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i> Seragam
            </a>
            <a href="{{ route('admin.item-seragam.index') }}"
                class="nav-link {{ request()->routeIs('admin.item-seragam.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Item Seragam
            </a>
        </nav>
        <div class="p-3 border-top border-secondary">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light w-100">
                    <i class="bi bi-box-arrow-left me-1"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <div class="topbar">
            <button class="btn btn-sm btn-light d-lg-none" id="toggleSidebar">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="fw-semibold text-secondary d-none d-lg-block" style="font-size:.85rem">
                {{ $pageTitle ?? '' }}
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:.82rem">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                </span>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="page-content">

            {{-- ALERT FLASH --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4"
                    role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4"
                    role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        document.getElementById('toggleSidebar')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>