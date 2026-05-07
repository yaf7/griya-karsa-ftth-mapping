<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Mikrotik App')</title>

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Leaflet (untuk map) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-measure/3.3.0/leaflet-measure.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-measure/3.3.0/leaflet-measure.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-ant-path/dist/leaflet-ant-path.css"/>
<script src="https://unpkg.com/leaflet-ant-path/dist/leaflet-ant-path.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- tambahkan plugin antPath -->
<script src="https://unpkg.com/leaflet-ant-path/dist/leaflet-ant-path.min.js"></script>

    <style>
        :root {
            --bg-color: #0f172a; 
            --bg-glass: rgba(15, 23, 42, 0.75); 
            --bg-card: rgba(30, 41, 59, 0.5);
            --border-glass: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc; 
            --text-muted: #94a3b8; 
            --accent-glow: 0 0 15px rgba(56, 189, 248, 0.4);
            --accent-color: #38bdf8; 
        }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Inter', Arial, sans-serif;
            background: var(--bg-color);
            background-image: radial-gradient(circle at 15% 50%, rgba(14, 165, 233, 0.15) 0%, transparent 25%),
                              radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.15) 0%, transparent 25%);
            background-attachment: fixed;
            color: var(--text-primary);
        }

        .glass {
            background: var(--bg-glass) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: var(--bg-card) !important;
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            color: var(--text-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), var(--accent-glow);
            border-color: rgba(56, 189, 248, 0.3);
        }

        .table { color: var(--text-primary); border-color: var(--border-glass); }
        .table-light { background-color: rgba(255,255,255,0.05) !important; color: var(--text-primary) !important; }
        .table-light th { background-color: transparent !important; color: var(--text-primary) !important; border-bottom: 2px solid var(--border-glass); }
        .table th, .table td { background-color: transparent !important; border-color: var(--border-glass) !important; color: var(--text-primary); padding: 12px 16px; }
        .table-striped>tbody>tr:nth-of-type(odd)>* { background-color: rgba(255, 255, 255, 0.02) !important; color: var(--text-primary); }
        .table-hover>tbody>tr:hover>* { background-color: rgba(56, 189, 248, 0.1) !important; color: var(--text-primary); }

        .btn-primary { background-color: var(--accent-color); border-color: var(--accent-color); color: #0f172a; font-weight: 600; box-shadow: 0 0 10px rgba(56, 189, 248, 0.3); transition: all 0.2s; }
        .btn-primary:hover { background-color: #7dd3fc; border-color: #7dd3fc; box-shadow: var(--accent-glow); color: #0f172a; transform: translateY(-1px); }
        
        .form-control, .form-select { background-color: rgba(15, 23, 42, 0.6) !important; border: 1px solid var(--border-glass); color: var(--text-primary) !important; border-radius: 8px; }
        .form-control:focus, .form-select:focus { background-color: rgba(15, 23, 42, 0.9) !important; border-color: var(--accent-color); box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25); }
        .form-control::placeholder { color: var(--text-muted); }
        
        .modal-content { background-color: #1e293b; border: 1px solid var(--border-glass); color: var(--text-primary); border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .modal-header, .modal-footer { border-color: var(--border-glass); }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        .nav-link { color: var(--text-muted); transition: all 0.2s ease; border-radius: 8px; margin-bottom: 2px; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--text-primary); background: rgba(255,255,255,0.08); box-shadow: inset 3px 0 0 var(--accent-color); }
        .nav-link i { color: var(--accent-color); opacity: 0.8; transition: all 0.2s; }
        .nav-link:hover i { opacity: 1; filter: drop-shadow(0 0 5px var(--accent-color)); }

        #main-content {
            margin-left: 160px;          
            width: calc(100% - 160px);   
            padding: 80px 24px 24px;               
            min-height: 100vh;           
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 100px;
                padding: 80px 12px 12px;
            }
        }
        
        .navbar-brand img { filter: drop-shadow(0 0 8px rgba(255,255,255,0.8)); }
        .text-muted { color: var(--text-muted) !important; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3) !important; }
        .alert { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); border: 1px solid var(--border-glass); color: var(--text-primary); }
        .alert-success { border-left: 4px solid #10b981; }
        .alert-danger { border-left: 4px solid #ef4444; }
    </style>
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-dark glass fixed-top" style="z-index:1040; height:56px; border-bottom:1px solid rgba(255,255,255,0.1)!important;">
        <div class="container-fluid px-4">
            <a href="/" class="navbar-brand mb-0 p-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="36" style="object-fit:contain;" />
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-md-inline text-muted small">{{ Auth::user()->name ?? 'Deyafa Arsetya' }}</span>
                <i class="bi bi-person-circle fs-4 text-secondary"></i>
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Konten --}}
    <div id="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
