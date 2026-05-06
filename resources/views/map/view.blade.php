@extends('layouts.app')

@section('title', 'Tambah Optical Distribution')

@section('content')

                {{-- Map (dipanggil dari include) --}}
                @include('components.map')

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
