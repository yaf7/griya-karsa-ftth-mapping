@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('navbar-right')
    <a href="#" class="btn btn-sm btn-outline-primary">Bantuan</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted fw-semibold mb-1 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Total Client</div>
                        <div class="display-4 fw-bold text-white">{{ $totalClient ?? 0 }}</div>
                    </div>
                    <div class="fs-1 p-3 rounded-circle" style="background: rgba(56, 189, 248, 0.1); color: var(--accent-color);">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted fw-semibold mb-1 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Total Perangkat (OD)</div>
                        <div class="display-4 fw-bold text-white">{{ $totalOD ?? 0 }}</div>
                    </div>
                    <div class="fs-1 p-3 rounded-circle" style="background: rgba(139, 92, 246, 0.1); color: #a78bfa;">
                        <i class="bi bi-router"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="glass-card p-4 h-100">
                <h2 class="h6 text-muted fw-semibold text-uppercase mb-3" style="letter-spacing: 1px; font-size: 0.8rem;">Aktivitas Terbaru</h2>
                <ul class="list-unstyled mb-0">
                    @forelse(($logs ?? []) as $log)
                        <li class="mb-2 pb-2 border-bottom border-secondary small">{{ $log }}</li>
                    @empty
                        <li class="small text-muted fst-italic">Belum ada aktivitas.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div class="text-muted small">© {{ date('Y') }} — My App</div>
@endsection
