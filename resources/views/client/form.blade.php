@extends('layouts.app')

@section('title', 'Tambah Client')

@section('content')
<div class="container">
    <h4 class="mb-4">Tambah Client Baru</h4>

    <form action="{{ route('client.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Kode (unik) --}}
        <div class="mb-3">
            <label for="kode" class="form-label">Kode Client</label>
            <input type="text" name="kode" id="kode" class="form-control"
                   value="{{ old('kode') }}" required>
        </div>

        {{-- Nomor --}}
        <div class="mb-3">
            <label for="nomor" class="form-label">Nomor</label>
            <input type="text" name="nomor" id="nomor" class="form-control"
                   value="{{ old('nomor') }}" required>
        </div>

        {{-- Nama Client --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Client</label>
            <input type="text" name="nama" id="nama" class="form-control"
                   value="{{ old('nama') }}" required>
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3" class="form-control" required>{{ old('alamat') }}</textarea>
        </div>

        {{-- Pilih Paket --}}
        <div class="mb-3">
            <label for="id_paket" class="form-label">Paket</label>
            <select name="id_paket" id="id_paket" class="form-control select2" required>
                <option value="">-- Pilih Paket --</option>
                @foreach($pakets as $paket)
                    <option value="{{ $paket->id_paket }}"
                        {{ old('id_paket') == $paket->id_paket ? 'selected' : '' }}>
                        {{ $paket->nama_paket }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Optical Distribution --}}
        <div class="mb-3">
            <label for="id_optical_distribution" class="form-label">Optical Distribution</label>
            <select name="id_optical_distribution" id="id_optical_distribution" class="form-control select2" required>
                <option value="">-- Pilih Optical Distribution --</option>
                @foreach($optical_distribution as $optical)
                    <option value="{{ $optical->id_optical_distribution }}"
                        {{ old('id_optical_distribution') == $optical->id_optical_distribution ? 'selected' : '' }}>
                        {{ $optical->kode }} - {{ $optical->kategori->nama ?? '' }} - {{ $optical->keterangan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Koordinat + Map --}}
        @include('components.map', [
            'mode' => 'create_point',
            'kordinat' => old('kordinat') ?? null,
            'lines' => $lines ?? [],
            'optical_distribution' => $optical_distribution ?? []
        ])

        {{-- Upload Foto --}}
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        </div>

        {{-- User PPPoE --}}
        <div class="mb-3">
            <label for="user_pppoe" class="form-label">User PPPoE</label>
            <input type="text" name="user_pppoe" id="user_pppoe" class="form-control"
                   value="{{ old('user_pppoe') }}" required>
        </div>

        {{-- Tanggal Registrasi --}}
        <div class="mb-3">
            <label for="tanggal_regis" class="form-label">Tanggal Registrasi</label>
            <input type="date" name="tanggal_regis" id="tanggal_regis" class="form-control"
                   value="{{ old('tanggal_regis') ?? date('Y-m-d') }}" required>
        </div>

        {{-- Tanggal Pembayaran --}}
        <div class="mb-3">
            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control"
                   value="{{ old('tanggal_pembayaran') }}">
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="belum_bayar" {{ old('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="putus" {{ old('status') == 'putus' ? 'selected' : '' }}>Putus</option>
            </select>
        </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>

</div>
@endsection

@push('scripts')
<!-- jQuery (dibutuhkan untuk Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#id_paket, #id_optical_distribution').select2({
        placeholder: "Pilih...",
        allowClear: true,
        width: '100%'
    });
    // Tambah style minimalis biru pada select2
    $(".select2-selection").css({
        'border-radius': '6px',
        'border': '1px solid #b3c6e0',
        'min-height': '38px',
        'background': '#f4f8fb',
        'color': '#1565c0'
    });
});
</script>
@endpush
