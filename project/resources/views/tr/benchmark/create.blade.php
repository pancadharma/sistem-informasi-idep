@extends("layouts.app")


@section('subtitle', __('cruds.benchmark.add'))
@section('content_header_title') <strong>{{ __('cruds.benchmark.add') }}</strong>  @endsection
@section('sub_breadcumb')<a href="{{ route('benchmark.index') }}" title="{{ __('cruds.benchmark.list') }}"> {{ __('cruds.benchmark.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.benchmark.add') }}">{{ __('cruds.benchmark.add') }}</span> @endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('subtitle', 'Tambah Benchmark')

@section('content_body')
<div class="card card-outline card-primary">
    <div class="card-body">
        <form id="benchmarkForm" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row">
                {{-- Kolom Kiri --}}
                <div class="col-md-6">
                    <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id ?? '' }}" title="{{ auth()->user()->nama ?? '' }}">

                    <div class="form-group row">
                        <label for="kode_program" class="col-md-3 col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kode_program" placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}" name="kode_program" data-toggle="modal" data-target="#ModalDaftarProgram" required>
                                <input type="text" class="form-control" id="nama_program" readonly placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}" name="nama_program" style="background-color: #e9ecef;">
                            </div>
                            <input type="hidden" name="program_id" id="program_id">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jeniskegiatan_id" class="col-md-3 col-form-label">Jenis Kegiatan</label>
                        <div class="col-md-9">
                            <select id="jeniskegiatan_id" name="jeniskegiatan_id" class="form-control" required>
                                <option value="">Pilih Jenis Kegiatan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kode_kegiatan" class="col-md-3 col-form-label">{{ __('cruds.kegiatan.basic.kode') }}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kode_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan" data-toggle="modal" data-target="#ModalDaftarProgramActivity" required>
                                <input type="text" class="form-control" id="nama_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan" readonly style="background-color: #e9ecef;">
                            </div>
                            <input type="hidden" id="kegiatan_id" name="kegiatan_id">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provinsi_id" class="col-md-3 col-form-label">Provinsi</label>
                        <div class="col-md-9">
                            <select id="provinsi_id" name="provinsi_id" class="form-control" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kabupaten_id" class="col-md-3 col-form-label">Kabupaten</label>
                        <div class="col-md-9">
                            <select id="kabupaten_id" name="kabupaten_id" class="form-control" required>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kecamatan_id" class="col-md-3 col-form-label">Kecamatan</label>
                        <div class="col-md-9">
                            <select id="kecamatan_id" name="kecamatan_id" class="form-control" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="desa_id" class="col-md-3 col-form-label">Desa</label>
                        <div class="col-md-9">
                            <select id="desa_id" name="desa_id" class="form-control" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="tanggalimplementasi" class="col-md-3 col-form-label">Tanggal Implementasi</label>
                        <div class="col-md-9">
                            <input type="date" id="tanggalimplementasi" name="tanggalimplementasi" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="handler" class="col-md-3 col-form-label">Handler</label>
                        <div class="col-md-9">
                            <input type="text" id="handler" name="handler" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="usercompiler_id" class="col-md-3 col-form-label">Compiler</label>
                        <div class="col-md-9">
                            <select id="usercompiler_id" name="usercompiler_id" class="form-control" required>
                                <option value="">-- Pilih Compiler --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="score" class="col-md-3 col-form-label">Score</label>
                        <div class="col-md-9">
                            <input type="number" step="0.01" class="form-control" id="score" name="score">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="catatanevaluasi" class="col-md-3 col-form-label">Catatan Evaluasi</label>
                        <div class="col-md-9">
                            <textarea id="catatanevaluasi" name="catatanevaluasi" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="area" class="col-md-3 col-form-label">Area Peningkatan</label>
                        <div class="col-md-9">
                            <textarea id="area" class="form-control" name="area" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right mt-4">
                <button type="submit" id="submit" class="btn btn-primary">Simpan</button>
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

<script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>

@include('tr.benchmark.js.create')
@include('tr.benchmark.program-modal')
@include('tr.benchmark.kegiatan-modal')
@endpush
