@extends('layouts.app')

@section('subtitle', __('global.update') . ' ' . __('cruds.program.title_singular'))
@section('content_header_title', __('global.update') . ' ' . __('cruds.program.title_singular'))
@section('sub_breadcumb', __('cruds.program.title'))

@section('content_body')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info collapsed-card">
                <div class="card-header">
                    <h6>{{ __('global.update') . ' ' . __('cruds.program.title_singular') }}</h6>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" id="editProgram" action="{{ route('program.update', [$program->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id">
        {{-- Informasi Dasar --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
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
                                    <label for="nama_program"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.nama') }}</label>
                                    <input type="text" id="nama_program" name="nama"
                                        class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                        value="{{ old('nama', $program->nama) }}">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="kode_program"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.kode') }}</label>
                                    <input type="text" id="kode_program" name="kode"
                                        class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}"
                                        value="{{ old('kode', $program->kode) }}">
                                </div>
                                @if ($errors->has('kode'))
                                    <span class="text-danger">{{ $errors->first('kode') }}</span>
                                @endif
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalmulai"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.tgl_mulai') }}</label>
                                    <input type="date" id="tanggalmulai" name="tanggalmulai"
                                        class="form-control date {{ $errors->has('tanggalmulai') ? 'is-invalid' : '' }}"
                                        value="{{ old('tanggalmulai', \Carbon\Carbon::parse($program->tanggalmulai)->format('Y-m-d')) }}">


                                    @if ($errors->has('tanggalmulai'))
                                        <span class="text-danger">{{ $errors->first('tanggalmulai') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalselesai"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.tgl_selesai') }}</label>
                                    {{-- <input type="date" id="tanggalselesai" name="tanggalselesai"
                                        class="form-control date {{ $errors->has('tanggalselesai') ? 'is-invalid' : '' }}"
                                        value="{{ old('tanggalselesai', $program->tanggalselesai) }}"> --}}

                                    <input type="date" id="tanggalselesai" name="tanggalselesai"
                                        class="form-control date {{ $errors->has('tanggalselesai') ? 'is-invalid' : '' }}"
                                        value="{{ old('tanggalselesai', \Carbon\Carbon::parse($program->tanggalselesai)->format('Y-m-d')) }}">

                                    @if ($errors->has('tanggalselesai'))
                                        <span class="text-danger">{{ $errors->first('tanggalselesai') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="totalnilai"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.total_nilai') }}</label>
                                    <input type="text" id="totalnilai" name="totalnilai"
                                        class="form-control currency {{ $errors->has('totalnilai') ? 'is-invalid' : '' }}"
                                        minlength="0" value="{{ old('totalnilai', $program->totalnilai) }}"
                                        step="0.001">

                                    @if ($errors->has('totalnilai'))
                                        <span class="text-danger">{{ $errors->first('totalnilai') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Ekspektasi Penerima Manfaat --}}
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaat"
                                        class="control-label mb-0 small">{{ __('cruds.program.expektasi') }}</label>
                                    <input type="number" id="ekspektasipenerimamanfaat" maxlength="1000000"
                                        name="ekspektasipenerimamanfaat"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaat') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaat', $program->ekspektasipenerimamanfaat) }}"
                                        placeholder="{{ __('cruds.program.expektasi') }}"
                                        oninput="this.value = Math.max(0, this.value)">

                                    @if ($errors->has('ekspektasipenerimamanfaat'))
                                        <span class="text-danger">{{ $errors->first('ekspektasipenerimamanfaat') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="pria"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.pria') }}</strong></label>
                                    <input type="number" id="pria" name="ekspektasipenerimamanfaatman"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatman') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatman', $program->ekspektasipenerimamanfaatman) }}"
                                        oninput="this.value = Math.max(0, this.value)">

                                    @if ($errors->has('ekspektasipenerimamanfaatman'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatman') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="wanita"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.wanita') }}</strong></label>
                                    <input type="number" id="wanita" name="ekspektasipenerimamanfaatwoman"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatwoman') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatwoman', $program->ekspektasipenerimamanfaatwoman) }}"
                                        oninput="this.value = Math.max(0, this.value)">

                                    @if ($errors->has('ekspektasipenerimamanfaatwoman'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatwoman') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="laki"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.laki') }}</strong></label>
                                    <input type="number" id="laki" name="ekspektasipenerimamanfaatboy"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatboy') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatboy', $program->ekspektasipenerimamanfaatboy) }}"
                                        oninput="this.value = Math.max(0, this.value)">

                                    @if ($errors->has('ekspektasipenerimamanfaatboy'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatboy') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="perempuan"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.perempuan') }}</strong></label>
                                    <input type="number" id="perempuan" name="ekspektasipenerimamanfaatgirl"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatgirl') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatgirl', $program->ekspektasipenerimamanfaatgirl) }}"
                                        oninput="this.value = Math.max(0, this.value)">
                                    @if ($errors->has('ekspektasipenerimamanfaatgirl'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatgirl') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="total"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.ex_indirect') }}</strong></label>
                                    <input type="number" id="total" name="ekspektasipenerimamanfaattidaklangsung"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaattidaklangsung') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaattidaklangsung', $program->ekspektasipenerimamanfaattidaklangsung) }}"
                                        oninput="this.value = Math.max(0, this.value)">
                                    @if ($errors->has('ekspektasipenerimamanfaattidaklangsung'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaattidaklangsung') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kelompok Marjinal --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kelompokmarjinal" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.marjinal.list') }}
                                        </strong>
                                    </label>
                                    <div class="select2-purple">
                                        <select
                                            class="form-control select2 {{ $errors->has('kelompokmarjinal') ? 'is-invalid' : '' }}"
                                            name="kelompokmarjinal[]" id="kelompokmarjinal" multiple="multiple">
                                        </select>
                                    </div>
                                    @if ($errors->has('kelompokmarjinal'))
                                        <span class="text-danger">{{ $errors->first('kelompokmarjinal') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Target Reinstra --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="targetreinstra" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.list_reinstra') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select
                                            class="form-control select2-hidden-accessible  {{ $errors->has('targetreinstra') ? 'is-invalid' : '' }}"
                                            name="targetreinstra[]" id="targetreinstra" multiple="multiple" required>
                                        </select>
                                    </div>
                                    @if ($errors->has('targetreinstra'))
                                        <span class="text-danger">{{ $errors->first('targetreinstra') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kaitan SDG --}}
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kaitansdg" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.list_sdg') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select
                                            class="form-control select2  {{ $errors->has('kaitansdg') ? 'is-invalid' : '' }}"
                                            name="kaitansdg[]" id="kaitansdg" multiple="multiple" required>
                                        </select>
                                    </div>
                                    @if ($errors->has('kaitansdg'))
                                        <span class="text-danger">{{ $errors->first('kaitansdg') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Deskripsi Program --}}
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.deskripsi') }}
                                        </strong>
                                    </label>
                                    <textarea id="deskripsi" name="deskripsiprojek" cols="30" rows="5" class="form-control"
                                        placeholder="{{ __('cruds.program.deskripsi') }}" maxlength="500">{{ old('deskripsiprojek', $program->deskripsiprojek) }}</textarea>

                                    @if ($errors->has('deskripsi'))
                                        <span class="text-danger">{{ $errors->first('deskripsiprojek') }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- Analisis Program --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.analisis') }}
                                        </strong>
                                    </label>
                                    <textarea id="analisis" name="analisamasalah" cols="30" rows="5" class="form-control"
                                        placeholder="{{ __('cruds.program.analisis') }}" maxlength="500">{{ old('analisamasalah', $program->analisamasalah) }}</textarea>
                                    @if ($errors->has('analisamasalah'))
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
                                <label for="file_pendukung" class="control-label mb-0 small">
                                    <strong>
                                        {{ __('cruds.program.upload') }}
                                    </strong>
                                    <span class="text-red">
                                        ( {{ __('allowed file: .jpg .png .pdf .docx | max: 4MB') }} )
                                    </span>
                                    <div class="small">{{ __('cruds.program.edit_file') }}</div>
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
                                    <label for="status" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.status.title') }}
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <select
                                            class="form-control select2 {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                            name="status" id="status">
                                            <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>
                                                {{ trans('global.pleaseSelect') }}</option>
                                            @foreach (App\Models\Program::STATUS_SELECT as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('status', $program->status) === (string) $key ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label mb-0 small">
                                        <strong>
                                            Program Created / Updated by
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <input type="text" class="form-control"
                                            value="{{ old('nama', $program->users->nama) }}" id="user_id"
                                            name="user_id" readonly>
                                        <input type="hidden" class="form-control" value="{{ auth()->user()->id }}"
                                            name="user_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Submit Update Button --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-info  btn-block float-right">
                                        {{ __('global.update') . ' ' . __('cruds.program.title_singular') }}
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

@include('tr.program.js.edit')
@endpush
