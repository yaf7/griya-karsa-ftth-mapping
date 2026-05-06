@extends('layouts.app')

@section('title', 'Edit Optical Distribution')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Edit Titik ODP / ODC / SERVER</h5>
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

            <form action="{{ route('optical_distribution.update', $item->id_optical_distribution) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Kode --}}
                <div class="mb-3">
                    <label for="kode" class="form-label">Kode</label>
                    <input type="text" name="kode" id="kode" class="form-control"
                           value="{{ old('kode', $item->kode) }}" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label for="id_kategori" class="form-label">Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ old('id_kategori', $item->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Inputan --}}
                <div class="mb-3">
                    <label for="inputan" class="form-label">Inputan</label>
                    <select name="inputan" id="inputan" class="form-select">
                        <option value="">-- Pilih Inputan --</option>
                        @foreach(\App\Models\OpticalDistribution::all() as $d)
                            <option value="{{ $d->id_optical_distribution }}"
                                {{ old('inputan', $item->inputan) == $d->id_optical_distribution ? 'selected' : '' }}>
                                {{ $d->kode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Map --}}
                @include('components.map')

                {{-- Estimasi Redaman --}}
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="estimasi_redaman_input" class="form-label">Estimasi Redaman Input (dB)</label>
                        <input type="number" step="0.01" name="estimasi_redaman_input" id="estimasi_redaman_input" class="form-control"
                               value="{{ old('estimasi_redaman_input', $item->estimasi_redaman_input) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="estimasi_redaman_output" class="form-label">Estimasi Redaman Output (dB)</label>
                        <input type="number" step="0.01" name="estimasi_redaman_output" id="estimasi_redaman_output" class="form-control"
                               value="{{ old('estimasi_redaman_output', $item->estimasi_redaman_output) }}">
                    </div>
                </div>

                {{-- Foto --}}
                <div class="mb-3 mt-3">
                    <label for="foto" class="form-label">Foto</label>
                    @if($item->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="img-thumbnail" width="200">
                        </div>
                    @endif
                    <input type="file" name="foto" id="foto" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-control">{{ old('keterangan', $item->keterangan) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Update
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
