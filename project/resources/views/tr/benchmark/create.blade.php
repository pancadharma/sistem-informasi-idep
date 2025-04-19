@extends("layouts.app")

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('subtitle', 'Tambah Benchmark')

@section('content_header_title', 'Tambah Benchmark')

@section('content_body')
<div class="card card-outline card-primary">
    <div class="card-body">
        <form id="formBenchmark">
            <div class="row">
                {{-- Kiri --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Program</label>
                        <select id="program_id" name="program_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kegiatan</label>
                        <input type="text" class="form-control" name="jenis_kegiatan">
                    </div>
                    <div class="form-group">
                        <label>Kegiatan</label>
                        <input type="text" class="form-control" name="nama_kegiatan">
                    </div>
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" class="form-control" name="provinsi">
                    </div>
                    <div class="form-group">
                        <label>Kabupaten</label>
                        <input type="text" class="form-control" name="kabupaten">
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" class="form-control" name="kecamatan">
                    </div>
                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" class="form-control" name="desa">
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Implementasi</label>
                        <input type="date" class="form-control" name="tanggal_implementasi">
                    </div>
                    <div class="form-group">
                        <label>Handler</label>
                        <input type="text" class="form-control" name="handler">
                    </div>
                    <div class="form-group">
                        <label>Compiler</label>
                        <input type="text" class="form-control" name="compiler">
                    </div>
                    <div class="form-group">
                        <label>Score</label>
                        <input type="number" step="0.01" class="form-control" name="score">
                    </div>
                    <div class="form-group">
                        <label>Catatan Evaluasi</label>
                        <textarea class="form-control" name="catatan_evaluasi" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Area Peningkatan</label>
                        <textarea class="form-control" name="area_peningkatan" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="text-right mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
@endpush
