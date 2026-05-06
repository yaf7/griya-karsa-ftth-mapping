@extends('layouts.app')

@section('title', 'Tambah Line')

@section('content')
<script src="https://unpkg.com/leaflet-ant-path@1.3.0/dist/leaflet-ant-path.min.js"></script>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Tambah Line</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan!</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form action="{{ route('line.store') }}" method="POST">
                @csrf

                {{-- Pilih Optical Distribution --}}
                <div class="mb-3">
                    <label for="id_optical_distribution" class="form-label">Optical Distribution</label>
                    <select name="id_optical_distribution" id="id_optical_distribution" class="form-select" required>
                        <option value="">-- Pilih ODP/ODC/Server --</option>
                        @foreach($optical_distribution as $od)
                            <option value="{{ $od->id_optical_distribution }}">
                                {{ $od->kode }} - {{ $od->kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
                </div>

                {{-- Map --}}
                @include('components.map')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('line.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- =========================
         DAFTAR SEMUA LINE
    ========================== --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Line</h5>
            <form action="{{ route('line.create') }}" method="GET" class="d-flex me-2">
            <input type="text" name="q" class="form-control form-control-sm"
                   placeholder="Cari ID / Keterangan..."
                   value="{{ request('q') }}">
            <button class="btn btn-sm btn-outline-primary ms-1" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
            
        </div>
        <div class="card-body">
            @if($lines->isEmpty())
                <p class="text-muted">Belum ada line yang terdaftar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID Line</th>
                                <th>Optical Distribution</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Panjang</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lines as $line)
                                <tr>
                                    <td>{{ $line->id_line }}</td>
                                    <td>{{ $line->opticalDistribution->kode ?? '-' }}</td>
                                    <td>{{ $line->opticalDistribution->kategori->nama ?? '-' }}</td>
                                    <td>{{ $line->keterangan ?? '-' }}</td>
                                    <td>
                                        @php
                                            $km = '-';
                                            if ($line->kordinat && str_starts_with($line->kordinat, '[')) {
                                                try {
                                                    $coords = json_decode($line->kordinat, true);
                                                    $length = 0;
                                                    for ($i = 0; $i < count($coords)-1; $i++) {
                                                        $lat1 = $coords[$i][0]; $lng1 = $coords[$i][1];
                                                        $lat2 = $coords[$i+1][0]; $lng2 = $coords[$i+1][1];
                                                        $earthRadius = 6371; // km
                                                        $dLat = deg2rad($lat2 - $lat1);
                                                        $dLng = deg2rad($lng2 - $lng1);
                                                        $a = sin($dLat/2) * sin($dLat/2) +
                                                             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                                                             sin($dLng/2) * sin($dLng/2);
                                                        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                                                        $length += $earthRadius * $c;
                                                    }
                                                    $km = number_format($length, 2) . " km";
                                                } catch (\Throwable $e) {
                                                    $km = '-';
                                                }
                                            }
                                        @endphp
                                        {{ $km }}
                                    </td>
                                    <td>{{ $line->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('line.edit', $line->id_line) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form action="{{ route('line.destroy', $line->id_line) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus line ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
