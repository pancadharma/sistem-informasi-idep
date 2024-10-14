@extends('layouts.app')

@section('subtitle', __('global.edit') . ' ' . __('cruds.program.title_singular'))
@section('content_header_title', __('global.create') . ' ' . __('cruds.program.title_singular'))
@section('sub_breadcumb', __('cruds.program.title_singular'))

@section('content_body')
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card card-primary collapsed-card">
                <div class="card-header">
                    <h5>{{ __('global.create') . ' ' . __('cruds.program.title_singular') }}</h5>
                </div>
            </div>
        </div>
    </div> --}}
    <form id="createProgram" method="POST" class="resettable-form" data-toggle="validator" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('POST')

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.info_dasar') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    {{-- Informasi Dasar --}}
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="nama"
                                        class="control-label small mb-0">{{ __('cruds.program.nama') }}</label>
                                    <input type="text" id="nama" name="nama" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="kode"
                                        class="control-label small mb-0">{{ __('cruds.program.form.kode') }}</label>
                                    <input type="text" id="kode" name="kode" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalmulai"
                                        class="control-label small mb-0">{{ __('cruds.program.form.tgl_mulai') }}</label>
                                    <input type="date" id="tanggalmulai" name="tanggalmulai" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalselesai"
                                        class="control-label small mb-0">{{ __('cruds.program.form.tgl_selesai') }}</label>
                                    <input type="date" id="tanggalselesai" name="tanggalselesai" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="totalnilai"
                                        class="control-label small mb-0">{{ __('cruds.program.form.total_nilai') }}</label>
                                    <input type="text" id="totalnilai" name="totalnilai" class="form-control currency"
                                        minlength="0" step=".01",>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Ekspektasi Penerima Manfaat --}}
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaat"
                                        class="control-label small mb-0">{{ __('cruds.program.expektasi') }}</label>
                                    <input type="number" id="ekspektasipenerimamanfaat" name="ekspektasipenerimamanfaat"
                                        class="form-control" placeholder="{{ __('cruds.program.expektasi') }}"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatman"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.pria') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatman"
                                        name="ekspektasipenerimamanfaatman" class="form-control"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatwoman"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.wanita') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatwoman"
                                        name="ekspektasipenerimamanfaatwoman" class="form-control"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatboy"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.laki') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatboy"
                                        name="ekspektasipenerimamanfaatboy" class="form-control"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatgirl"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.perempuan') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatgirl"
                                        name="ekspektasipenerimamanfaatgirl" class="form-control"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaattidaklangsung"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.ex_indirect') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaattidaklangsung"
                                        name="ekspektasipenerimamanfaattidaklangsung" class="form-control"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kelompok Marjinal --}}
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kelompokmarjinal" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.marjinal.list') }}
                                        </strong>
                                    </label>
                                    <div class="select2-purple">
                                        <select class="form-control select2" name="kelompokmarjinal[]"
                                            id="kelompokmarjinal" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Target Reinstra --}}
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="targetreinstra" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.list_reinstra') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select class="form-control select2-hidden-accessible" name="targetreinstra[]"
                                            id="targetreinstra" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kaitan SDG --}}
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kaitansdg" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.list_sdg') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select class="form-control select2" name="kaitansdg[]" id="kaitansdg"
                                            multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi Program --}}
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="deskripsiprojek" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.deskripsi') }}
                                        </strong>
                                    </label>
                                    <textarea id="deskripsiprojek" name="deskripsiprojek" cols="30" rows="5"
                                        class="form-control {{ $errors->has('deskripsiprojek') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('cruds.program.deskripsi') }}" maxlength="500"></textarea>
                                    @if ($errors->has('deskripsiprojek'))
                                        <span class="text-danger">{{ $errors->first('deskripsiprojek') }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- Analisis Program --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="analisamasalah" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.analisis') }}
                                        </strong>
                                    </label>
                                    <textarea id="analisamasalah" name="analisamasalah" cols="30" rows="5"
                                        class="form-control {{ $errors->has('analisamasalah') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('cruds.program.analisis') }}" maxlength="500"></textarea>

                                    @if ($errors->has('deskripsiprojek'))
                                        <span class="text-danger">{{ $errors->first('analisamasalah') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- File Uploads --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="file_pendukung" class="control-label small mb-0">
                                    <strong>
                                        {{ __('cruds.program.upload') }}
                                    </strong>
                                    <span class="text-red">
                                        ( {{ __('allowed file: .jpg .png .pdf .docx | max: 4MB') }} )
                                    </span>
                                </label>
                                <div class="form-group file-loading">
                                    <input id="file_pendukung" name="file_pendukung[]" type="file"
                                        class="form-control" multiple data-show-upload="false" data-show-caption="true">
                                </div>
                                <div id="captions-container"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Status --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.status.title') }}
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <select class="form-control select2" name="status" id="status" required>
                                            <optgroup label="Status Progran">
                                                <option value="draft">Draft</option>
                                                <option value="running" disabled>Running</option>
                                                <option value="submit" disabled>Submit</option>
                                                <option value="completed" disabled>Completed</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label small mb-0">
                                        <strong>
                                            User Program
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <input type="text" class="form-control" value="{{ auth()->user()->nama }}"
                                            id="user_id" name="user_id" readonly>
                                        <input type="hidden" class="form-control" value="{{ auth()->user()->id }}"
                                            name="user_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    @include('tr.program.detail.create')
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Submit Button --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        {{ __('global.add') . ' ' . __('cruds.program.title_singular') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
@endpush

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

@include('tr.program.js.create')
@include('tr.program.js.donor')
@endpush
