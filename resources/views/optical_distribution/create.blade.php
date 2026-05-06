@extends('layouts.app')

@section('title', 'Tambah Optical Distribution')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Tambah Titik ODP / ODC / SERVER</h5>
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

            <form action="{{ route('optical_distribution.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Kode --}}
                <div class="mb-3">
                    <label for="kode" class="form-label">Kode</label>
                    <input type="text" name="kode" id="kode" class="form-control" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label for="id_kategori" class="form-label">Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Inputan --}}
                <div class="mb-3">
                    <label for="inputan" class="form-label">Inputan</label>
                    <select name="inputan" id="inputan" class="form-select">
                        <option value="">-- Pilih Inputan --</option>
                        @foreach($optical_distribution as $d)
                            <option value="{{ $d->id_optical_distribution }}">
                                {{ $d->kode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Map (dipanggil dari include) --}}
                @include('components.map')

                {{-- Estimasi Redaman --}}
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="estimasi_redaman_input" class="form-label">Estimasi Redaman Input (dB)</label>
                        <input type="number" step="0.01" name="estimasi_redaman_input" id="estimasi_redaman_input" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="estimasi_redaman_output" class="form-label">Estimasi Redaman Output (dB)</label>
                        <input type="number" step="0.01" name="estimasi_redaman_output" id="estimasi_redaman_output" class="form-control">
                    </div>
                </div>

                {{-- Foto --}}
                <div class="mb-3 mt-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    {{-- =========================
         DAFTAR SEMUA OPTIC
    ========================== --}}
    <div class="card shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Optical Distribution</h5>
        <div class="d-flex align-items-center">
            <input type="text" id="searchOptik" class="form-control form-control-sm me-2" 
                   placeholder="Cari kode / kategori / keterangan...">
            <button class="btn btn-sm btn-outline-primary" type="button" id="btnSearch">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($optical_distribution->isEmpty())
            <p class="text-muted">Belum ada data Optical Distribution.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle" id="optikTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Inputan</th>
                            <th>Redaman (In/Out)</th>
                            <th>Keterangan</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($optical_distribution as $od)
                            <tr>
                                <td>{{ $od->id_optical_distribution }}</td>
                                <td>{{ $od->kode }}</td>
                                <td>{{ $od->kategori->nama ?? '-' }}</td>
                                <td>{{ $od->inputanRelation->kode ?? '-' }}</td>
                                <td>{{ $od->estimasi_redaman_input }} / {{ $od->estimasi_redaman_output }} dB</td>
                                <td>{{ $od->keterangan ?? '-' }}</td>
                                <td>{{ $od->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('optical_distribution.edit', $od->id_optical_distribution) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('optical_distribution.destroy', $od->id_optical_distribution) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
$(document).ready(function () {
    // Pencarian di tabel daftar Optical Distribution
    $("#searchOptik").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#optikTable tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

<!-- jQuery (dibutuhkan untuk Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#inputan').select2({
        placeholder: "Cari dan pilih inputan...",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush
