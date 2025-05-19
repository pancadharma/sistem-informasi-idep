@extends("layouts.app")


@section('subtitle', __('cruds.benchmark.edit'))
@section('content_header_title') <strong>{{ __('cruds.benchmark.edit') }}</strong>  @endsection
@section('sub_breadcumb')<a href="{{ route('benchmark.index') }}" title="{{ __('cruds.benchmark.list') }}"> {{ __('cruds.benchmark.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.benchmark.edit') }}">{{ __('cruds.benchmark.edit') }}</span> @endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('subtitle', 'Edit Benchmark')

@section('content_body')
<div class="card card-outline card-primary">
    <div class="card-body">
        <form id="benchmarkForm" method="PUT" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Kiri --}}
                <div class="col-md-6">
                    <div class="form-group row">
                        <!-- nama program-->
                        <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
                            <label for="kode_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
                        <!-- id program -->
                            <input type="hidden" name="program_id" id="program_id" disabled>
                            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id ?? '' }}" title="{{ auth()->user()->nama ?? '' }}">
                        <!-- kode program -->
                            <input type="text" class="form-control" id="kode_program" placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}" name="kode_program"
                            data-toggle="modal" data-target="#ModalDaftarProgram">
                        </div>
                        <!-- nama program-->
                        <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
                            <label for="nama_program" class="input-group col-form-label">
                                {{ __('cruds.kegiatan.basic.program_nama') }}
                            </label>
                            <input type="text" class="form-control" id="nama_program" readonly placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}" name="nama_program">
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label for="jeniskegiatan_id">Jenis Kegiatan</label>
                        <select id="jeniskegiatan_id" name="jeniskegiatan_id" class="form-control" required>
                            <option value="">Pilih Jenis Kegiatan</option>
                        </select>
                    </div>

                    <div class="form-group row">
                        <!-- kode kegiatan-->
                        <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
                            <label for="kode_kegiatan" class="input-group col-form-label">
                                {{ __('cruds.kegiatan.basic.kode') }}
                            </label>
                            <input type="hidden" class="form-control" id="kegiatan_id" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kegiatan_id">
                            <input type="text" class="form-control" id="kode_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan"
                            data-toggle="modal" data-target="#ModalDaftarProgramActivity">
                        </div>
                        <!-- nama kegiatan-->
                        <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
                            <label for="nama_kegiatan" class="input-group col-form-label">
                                {{ __('cruds.kegiatan.basic.nama') }}
                            </label>
                            <input type="text" class="form-control" id="nama_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Provinsi</label>
                        <select id="provinsi_id" name="provinsi_id" class="form-control" required>
                            <option>
                                
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kabupaten</label>
                        <select id="kabupaten_id" name="kabupaten_id" class="form-control" required>
                            <option>

                            </option>
                        </select>
                    </div>        
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select id="kecamatan_id" name="kecamatan_id" class="form-control" required>
                            <option value="">

                            </option>
                        </select>    
                    </div>
                    <div class="form-group">
                        <label>Desa</label>
                        <select id="desa_id" name="desa_id" class="form-control" required>
                            <option value="">
                                
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggalimplementasi">Tanggal Implementasi</label>
                        <input type="date" id="tanggalimplementasi" name="tanggalimplementasi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="handler">Handler</label>
                        <input type="text" id="handler" name="handler" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="usercompiler_id">Compiler</label>
                        <select id="usercompiler_id" name="usercompiler_id" class="form-control" required>
                            <option value="">-- Pilih Compiler --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Score</label>
                        <input type="number" step="0.01" class="form-control" name="score">
                    </div>
                    <div class="form-group">
                        <label for="catatan_evaluasi">Catatan Evaluasi</label>
                        <textarea id="catatan_evaluasi" name="catatan_evaluasi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Area Peningkatan</label>
                        <textarea class="form-control" name="area_peningkatan" rows="3"></textarea>
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
