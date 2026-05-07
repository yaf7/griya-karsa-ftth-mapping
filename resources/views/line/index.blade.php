@extends('layouts.app')

@section('title', 'Data Jalur (Line)')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Jalur (Line)</h4>
        <a href="{{ route('line.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> Tambah Jalur
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Optical Distribution</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lines as $index => $line)
                            <tr>
                                <td>{{ $lines->firstItem() + $index }}</td>
                                <td>{{ $line->opticalDistribution->kode ?? '-' }} ({{ $line->opticalDistribution->kategori->nama ?? '-' }})</td>
                                <td>{{ $line->keterangan ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('line.edit', $line->id_line ?? $line->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('line.destroy', $line->id_line ?? $line->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $lines->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
