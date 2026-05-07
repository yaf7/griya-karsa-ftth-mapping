@extends('layouts.app')

@section('title', 'Data Client PPPoE')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-white fw-bold mb-0">Data Client PPPoE</h4>
        <a href="{{ route('client.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus"></i> Tambah Client
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif

    <div class="glass-card p-3">
        <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>User PPPoE</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $index => $client)
                            <tr>
                                <td>{{ $clients->firstItem() + $index }}</td>
                                <td>{{ $client->kode }}</td>
                                <td>{{ $client->nama }}</td>
                                <td>{{ $client->user_pppoe ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $client->status == 'aktif' ? 'success' : ($client->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($client->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('client.edit', $client->id_client ?? $client->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('client.destroy', $client->id_client ?? $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                                <td colspan="6" class="text-center">Data belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
