@extends('layouts.app')

@section('title', 'Edit Line')

@section('content')
<script src="https://unpkg.com/leaflet-ant-path@1.3.0/dist/leaflet-ant-path.min.js"></script>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Edit Line</h5>
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

            <form action="{{ route('line.update', $line->id_line) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Pilih Optical Distribution --}}
                <div class="mb-3">
                    <label for="id_optical_distribution" class="form-label">Optical Distribution</label>
                    <select name="id_optical_distribution" id="id_optical_distribution" class="form-select" required>
                        <option value="">-- Pilih ODP/ODC/Server --</option>
                        @foreach($optical_distribution as $od)
                            <option value="{{ $od->id_optical_distribution }}"
                                {{ $line->id_optical_distribution == $od->id_optical_distribution ? 'selected' : '' }}>
                                {{ $od->kode }} - {{ $od->kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-control">{{ old('keterangan', $line->keterangan) }}</textarea>
                </div>

                {{-- Map --}}
                @include('components.map', ['editMode' => true, 'line' => $line])

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                    <a href="{{ route('line.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
