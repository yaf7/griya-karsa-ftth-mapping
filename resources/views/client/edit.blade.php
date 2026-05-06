@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Edit Client</h5>
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

            <form action="{{ route('client.update', $client->id_client) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" name="kode" id="kode" class="form-control" 
                               value="{{ old('kode', $client->kode) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nomor" class="form-label">Nomor</label>
                        <input type="text" name="nomor" id="nomor" class="form-control" 
                               value="{{ old('nomor', $client->nomor) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" 
                               value="{{ old('nama', $client->nama) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control">{{ old('alamat', $client->alamat) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="user_pppoe" class="form-label">User PPPoE</label>
                        <input type="text" name="user_pppoe" id="user_pppoe" class="form-control" 
                               value="{{ old('user_pppoe', $client->user_pppoe) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="aktif" {{ old('status', $client->status)=='aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $client->status)=='nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tanggal_regis" class="form-label">Tanggal Registrasi</label>
                        <input type="date" name="tanggal_regis" id="tanggal_regis" class="form-control" 
                               value="{{ old('tanggal_regis', $client->tanggal_regis) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control" 
                               value="{{ old('tanggal_pembayaran', $client->tanggal_pembayaran) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_paket" class="form-label">Paket</label>
                        <select name="id_paket" id="id_paket" class="form-select select2">
                            <option value="">-- Pilih Paket --</option>
                            @foreach($pakets as $p)
                                <option value="{{ $p->id_paket }}" {{ old('id_paket', $client->id_paket)==$p->id_paket ? 'selected' : '' }}>
                                    {{ $p->nama_paket }} - {{ isset($p->harga) ? 'Rp '.number_format($p->harga,0,',','.') : '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_optical_distribution" class="form-label">Optical Distribution</label>
                        <select name="id_optical_distribution" id="id_optical_distribution" class="form-select select2">
                            <option value="">-- Pilih ODC/ODP --</option>
                            @foreach($optical_distribution as $o)
                                <option value="{{ $o->id_optical_distribution }}" {{ old('id_optical_distribution', $client->id_optical_distribution)==$o->id_optical_distribution ? 'selected' : '' }}>
                                    {{ $o->kode }} - {{ $o->kategori->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                        @if($client->foto)
                            <img src="{{ asset('storage/'.$client->foto) }}" alt="Foto Client" class="img-thumbnail mt-2" width="150">
                        @endif
                    </div>
                </div>

                {{-- Map koordinat --}}
                <div class="mb-3">
                    @include('components.map')
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    {{-- Daftar semua client --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Client</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchClient" class="form-control form-control-sm me-2" 
                       placeholder="Cari kode / nama / alamat...">
                <button class="btn btn-sm btn-outline-primary" type="button" id="btnSearchClient">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($clients->isEmpty())
                <p class="text-muted">Belum ada data client.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="clientTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nomor</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>PPPoE</th>
                                <th>Status</th>
                                <th>Paket</th>
                                <th>ODC/ODP</th>
                                <th>Tanggal Registrasi</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $c)
                                <tr>
                                    <td>{{ $c->id_client }}</td>
                                    <td>{{ $c->kode }}</td>
                                    <td>{{ $c->nomor }}</td>
                                    <td>{{ $c->nama }}</td>
                                    <td>{{ $c->alamat }}</td>
                                    <td>{{ $c->user_pppoe ?? '-' }}</td>
                                    <td>{{ ucfirst($c->status) }}</td>
                                    <td>
                                        {{ $c->paket->kecepatan ?? '-' }} - 
                                        {{ isset($c->paket->harga) ? 'Rp '.number_format($c->paket->harga,0,',','.') : '-' }}
                                    </td>
                                    <td>{{ $c->optical_distribution->kode ?? '-' }}</td>
                                    <td>{{ $c->tanggal_regis ? \Carbon\Carbon::parse($c->tanggal_regis)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $c->tanggal_pembayaran ? \Carbon\Carbon::parse($c->tanggal_pembayaran)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $c->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('client.edit', $c->id_client) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form action="{{ route('client.destroy', $c->id_client) }}" method="POST" class="d-inline"
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {
    // Select2 untuk paket dan ODC
    $('.select2').select2({
        placeholder: "Pilih...",
        allowClear: true,
        width: '100%'
    });

    // Filter pencarian client
    $("#searchClient").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#clientTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
@endpush
