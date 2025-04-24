{{-- @php --}}
{{-- Variabel $perPage dan $perPageOptions tidak lagi diperlukan untuk DataTables --}}
{{-- $perPage = request()->input('per_page', 10); --}}
{{-- $perPageOptions = [10, 25, 50, 100]; --}}
{{-- @endphp --}}

@extends('layouts.app') {{-- Menggunakan layout utama --}}

{{-- Mengadopsi section dari template tim --}}
@section('subtitle', __('Daftar Feedback & Response')) {{-- Judul Halaman / Subtitle --}}

@section('content_header_title')
    {{-- Tombol Tambah FRM - Diubah menjadi Link ke Halaman Create --}}
    {{-- Hapus atribut data-bs-toggle & data-bs-target --}}
    <a href="{{ route('feedback.create') }}" class="btn btn-success" title="{{ __('Tambah Feedback Baru') }}">
         {{-- <i class="fas fa-plus me-1"></i> --}}
        {{ __('Tambah FRM') }}
    </a>
@endsection

@section('sub_breadcumb')
    {{ __('Feedback & Response') }}
@endsection

@section('preloader') {{-- Menambahkan preloader seperti contoh tim --}}
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body') {{-- Konten utama masuk ke section ini --}}
    <div class="card card-outline card-primary"> {{-- Menggunakan gaya card tim --}}
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table me-1"></i> {{-- Menggunakan ikon seperti template tim --}}
                {{ __('Daftar Feedback') }}
            </h3>
            <div class="card-tools">
                {{-- Tombol Filter (opsional): Jika ingin tetap ada, fungsinya diubah di JS --}}
                {{-- <button class="btn btn-tool" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse" title="{{ __('Filter Program') }}">
                    <i class="fas fa-filter"></i>
                </button> --}}
                {{-- Tombol Refresh DataTables (opsional) --}}
                 <button type="button" class="btn btn-tool" onclick="$('#feedbackTable').DataTable().ajax.reload();" title="{{ __('Refresh Data') }}">
                     <i class="fas fa-sync-alt"></i>
                 </button>
            </div>
        </div>

        {{-- Area Filter (Collapse) - DIHAPUS atau DIMODIFIKASI --}}
        {{-- Jika dipertahankan, form action dihapus, tombol submit trigger JS table.draw() --}}
        {{-- <div class="collapse" id="filterCollapse"> ... form filter lama ... </div> --}}

        <div class="card-body table-responsive"> {{-- Tambahkan kelas table-responsive di sini --}}
            <!-- {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif -->

            {{-- Tabel Data - Sekarang hanya kerangka --}}
            <table class="table table-bordered table-hover" id="feedbackTable"> {{-- Tambahkan ID unik --}}
                {{-- THEAD dan TBODY akan di-generate oleh JavaScript DataTables --}}
            </table>

            {{-- Kontrol Pagination Bawah Laravel - DIHAPUS --}}
            {{-- <div class="d-flex ..."> ... form perPage dan links() ... </div> --}}

        </div> {{-- End card-body --}}
    </div> {{-- End card --}}


@stop {{-- Menggunakan @stop seperti template tim --}}

{{-- Menggunakan @push untuk JS dan CSS seperti template tim --}}
@push('css')
{{-- Jika layout/plugin system tidak otomatis load CSS DataTables, tambahkan di sini --}}
{{-- Contoh: <link rel="stylesheet" href="path/to/datatables.bootstrap5.min.css"> --}}
@endpush

{{-- resources/views/tr/feedback/index.blade.php --}}

{{-- ... (bagian atas file sampai @stop) ... --}}

@push('css')
{{-- Jika layout/plugin system tidak otomatis load CSS DataTables Buttons, tambahkan di sini --}}
{{-- Contoh: <link rel="stylesheet" href="path/to/buttons.bootstrap5.min.css"> --}}
@endpush

@push('js')
{{-- Aktifkan plugin via @section jika layout mendukungnya --}}
{{-- PASTIKAN plugin ini memuat DataTables core + Buttons extension + Adapters --}}
@section('plugins.DatatablesNew', true)
@section('plugins.Sweetalert2', true)

{{-- Jika tidak pakai sistem plugin, pastikan SEMUA file JS yang diperlukan sudah di-load SEBELUM @include ini --}}
{{-- Termasuk: jQuery, DataTables core, DT Bootstrap adapter, DT Buttons, Buttons Bootstrap adapter, JSZip, pdfmake, Buttons HTML5, Buttons Print, Buttons ColVis, SweetAlert2 --}}
{{-- Script untuk meneruskan pesan session ke JS --}}
    @if (session('success'))
    <script>
        window.serverMessage = { type: 'success', message: @json(session('success')) };
    </script>
    @endif
    @if (session('error'))
    <script>
        window.serverMessage = { type: 'error', message: @json(session('error')) };
    </script>
    @endif
    {{-- / Script untuk meneruskan pesan session ke JS --}}


{{-- Include file JavaScript DataTables yang baru dibuat --}}
@include('tr.feedback.js.index')

@endpush