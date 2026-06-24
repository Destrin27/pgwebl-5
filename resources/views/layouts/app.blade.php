<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SragenLandWatch – @yield('title')</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <!-- Leaflet Draw -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:      #1B5E20;
            --primary-mid:  #2E7D32;
            --secondary:    #388E3C;
            --accent:       #66BB6A;
            --accent-light: #A5D6A7;
            --bg:           #F1F8F1;
            --card-bg:      #ffffff;
            --text:         #1a2e1a;
            --text-muted:   #5a7a5a;
            --border:       #C8E6C9;
            --danger:       #C62828;
            --warning:      #F9A825;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
            box-shadow: 0 2px 20px rgba(27,94,32,0.35);
            padding: 0.75rem 0;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.3px;
            color: #fff !important;
        }
        .navbar-brand span { color: var(--accent-light); }
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            padding: 0.4rem 0.85rem !important;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.15);
        }
        .nav-badge {
            background: var(--accent);
            color: #1a2e1a;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            vertical-align: middle;
            margin-left: 4px;
        }

        /* ── MAP ── */
        #map {
            height: 580px;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(27,94,32,0.18);
            border: 2px solid var(--border);
        }

        /* ── SIDEBAR / CARDS ── */
        .sidebar-card {
            background: var(--card-bg);
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 24px;
            border: 1px solid var(--border);
        }
        .sidebar-card h5 {
            color: var(--primary);
            font-weight: 700;
            font-size: 1rem;
        }
        .sidebar-card hr { border-color: var(--border); }

        /* ── FORMS ── */
        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 0.5rem 0.8rem;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(56,142,60,0.15);
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        /* ── BUTTONS ── */
        .btn-success-custom {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1.2rem;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-success-custom:hover { opacity: 0.9; transform: translateY(-1px); color: #fff; }
        .btn-warning-custom {
            background: var(--warning);
            border: none;
            color: #1a1a1a;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-danger-custom {
            background: var(--danger);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
        }

        /* ── ALERTS ── */
        .alert { border-radius: 10px; font-size: 0.9rem; border: none; }
        .alert-success { background: #E8F5E9; color: #1B5E20; }
        .alert-warning { background: #FFF8E1; color: #5D4037; }
        .alert-danger  { background: #FFEBEE; color: #B71C1C; }
        .alert-info    { background: #E3F2FD; color: #0D47A1; }

        /* ── STAT CARDS ── */
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-card .stat-icon { font-size: 2rem; opacity: 0.8; }
        .stat-card .stat-number { font-size: 2.2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { font-size: 0.85rem; opacity: 0.85; margin-top: 4px; }

        /* ── PAGE HEADER ── */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-header h4 { margin: 0; font-weight: 700; font-size: 1.2rem; }
        .page-header p  { margin: 0; opacity: 0.8; font-size: 0.85rem; }
        .page-header .header-icon {
            width: 48px; height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }

        /* ── TABLE ── */
        .table { font-size: 0.88rem; }
        .table thead th { background: #E8F5E9; color: var(--primary); font-weight: 700; border: none; }
        .table tbody tr:hover { background: #F1F8F1; }
        .badge-lama { background: #ECEFF1; color: #455A64; }
        .badge-baru { background: #E8F5E9; color: #1B5E20; }
        .arrow-change { color: var(--secondary); }

        /* ── MAP POPUP ── */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
        }
        .popup-title { color: var(--primary); font-weight: 700; font-size: 1rem; margin-bottom: 8px; }
        .popup-row { display: flex; gap: 6px; margin-bottom: 4px; font-size: 0.85rem; }
        .popup-label { font-weight: 600; color: var(--text-muted); min-width: 80px; }
        .popup-actions { margin-top: 10px; display: flex; gap: 6px; }

        /* ── INFO KLIK ── */
        .klik-info {
            background: #FFF8E1;
            border: 1.5px dashed var(--warning);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #5D4037;
            text-align: center;
        }
        .klik-info.selected {
            background: #E8F5E9;
            border-color: var(--secondary);
            color: var(--primary);
        }

        /* ── FOOTER ── */
        footer {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
            color: rgba(255,255,255,0.85);
            padding: 1.5rem 0;
            margin-top: 3rem;
            font-size: 0.88rem;
        }
        footer strong { color: #fff; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 3px; }
    </style>

    @yield('styles')
</head>
<body>

<!-- ═══ NAVBAR ═══ -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-leaf me-2"></i>Sragen<span>Land</span>Watch
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('peta*') ? 'active' : '' }}" href="/peta">
                        <i class="fas fa-globe-americas me-1"></i>Peta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('points*') ? 'active' : '' }}" href="/points">
                        <i class="fas fa-map-marker-alt me-1"></i>Titik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('polylines*') ? 'active' : '' }}" href="/polylines">
                        <i class="fas fa-route me-1"></i>Jalur
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('polygons*') ? 'active' : '' }}" href="/polygons">
                        <i class="fas fa-draw-polygon me-1"></i>Area
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('statistik-bps*') ? 'active' : '' }}" href="/statistik-bps">
                        <i class="fas fa-chart-line me-1"></i>Statistik BPS
                    </a>
                </li>

                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" href="/dashboard">
                        <i class="fas fa-chart-bar me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('bps-manage*') ? 'active' : '' }}" href="/bps-manage">
                        <i class="fas fa-database me-1"></i>Kelola BPS
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <form action="/logout" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;border:1px solid rgba(255,255,255,0.4);border-radius:8px;font-weight:600;padding:6px 14px;">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </form>
                </li>
                @else
                <li class="nav-item ms-2">
                    <a href="/login" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;border:1px solid rgba(255,255,255,0.4);border-radius:8px;font-weight:600;padding:6px 14px;">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- ═══ MAIN ═══ -->
<main class="container py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-check-circle fa-lg"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-exclamation-circle fa-lg"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Periksa input berikut:</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @yield('content')
</main>

<!-- ═══ FOOTER ═══ -->
<footer>
    <div class="container text-center">
        <p class="mb-1">
            <i class="fas fa-leaf me-2"></i>
            <strong>SragenLandWatch</strong> – Land Use Change Monitoring Kabupaten Sragen
        </p>
        <p class="mb-0" style="font-size:0.8rem;opacity:0.6;">
            Laravel + PostGIS + Leaflet.js + GeoServer &copy; 2026 | Responsi PGWEBL
        </p>
    </div>
</footer>

<!-- ═══ SCRIPTS ═══ -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

@yield('scripts')
</body>
</html>
