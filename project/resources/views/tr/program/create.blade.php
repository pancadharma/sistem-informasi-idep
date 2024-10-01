@extends('layouts.app')

@section('subtitle', __('global.create') . ' ' . __('cruds.program.title_singular'))
@section('content_header_title', __('global.create') . ' ' . __('cruds.program.title_singular'))
@section('sub_breadcumb', __('cruds.program.title'))

@section('content_body')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary collapsed-card">
                <div class="card-header">
                    <h6>{{ __('global.create') . ' ' . __('cruds.program.title_singular') }}</h6>
                </div>
            </div>
        </div>
    </div>
    {{-- <form action="">
    </form> --}}

    {{-- Informasi Dasar --}}
    <div class="row">
        <div class="col-md-12">
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
                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama">{{ __('cruds.program.form.nama') }}</label>
                                <input type="text" id="nama_program" name="nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kode">{{ __('cruds.program.form.kode') }}</label>
                                <input type="text" id="kode_program" name="kode" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tanggalselesai">{{ __('cruds.program.form.tgl_mulai') }}</label>
                                <input type="date" id="tanggalselesai" name="tanggalselesai" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tanggalmulai">{{ __('cruds.program.form.tgl_selesai') }}</label>
                                <input type="date" id="tanggalmulai" name="tanggalmulai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="totalnilai">{{ __('cruds.program.form.total_nilai') }}</label>
                                <input type="number" id="totalnilai" name="totalnilai" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Ekspektasi Penerima Manfaat --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <strong>
                        {{ __('cruds.program.expektasi') }}
                    </strong>
                </div>
                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pria">{{ __('cruds.program.form.pria') }}</label>
                                <input type="text" id="pria" name="ekspektasipenerimamanfaatman"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="wanita">{{ __('cruds.program.form.wanita') }}</label>
                                <input type="text" id="wanita" name="ekspektasipenerimamanfaatwoman"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="laki">{{ __('cruds.program.form.laki') }}</label>
                                <input type="text" id="laki" name="ekspektasipenerimamanfaatboy"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="perempuan">{{ __('cruds.program.form.perempuan') }}</label>
                                <input type="text" id="perempuan" name="ekspektasipenerimamanfaatgirl"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="total">{{ __('cruds.program.form.total') }}</label>
                                <input type="number" id="total" name="total" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    {{-- @section('plugins.DatatablesNew', true) --}}
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
@include('tr.program.js')

<script>
    //SCRIPT FOR CREATE PROGRAM FORM
    $(document).ready(function() {




    });
</script>
@endpush
