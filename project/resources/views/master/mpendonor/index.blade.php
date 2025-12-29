@extends('layouts.app')

@section('subtitle', __('cruds.mpendonor.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.mpendonor.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.mpendonor.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="card card-primary collapsed-card" >
            @can('pendonor_create')
            <div class="card-header">
                {{ __('global.create')}} {{__('cruds.mpendonor.title')}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            @endcan
                <div class="card-body">
                <form action="{{ route('pendonor.store')}}" method="POST" class="resettable-form" id="mpendonorForm" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="form-group"> {{--  id kategori pendonor --}}
                        <label for="kategoripendonor_add">{{ __('cruds.kategoripendonor.nama') }} {{ __('cruds.kategoripendonor.title') }}</label>
                        <div class="form-group">
                            <select id="kategoripendonor_add" required name="mpendonorkategori_id" class="form-control select2 kategoripendonor-data" style="width: 100%">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama">{{ __('cruds.mpendonor.nama') }} {{ __('cruds.mpendonor.title') }}</label>
                        <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="pic">{{ __('cruds.mpendonor.pic') }} {{ __('cruds.mpendonor.title') }}</label>
                        <input type="text" id="pic" name="pic" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('cruds.mpendonor.email') }} {{ __('cruds.mpendonor.title') }}</label>
                        <input type="text" id="email" name="email" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="phone">{{ __('cruds.mpendonor.phone') }} {{ __('cruds.mpendonor.title') }}</label>
                        <input type="text" id="phone" name="phone" class="form-control" required maxlength="20">
                    </div>
                    <div class="form-group">
                    <strong>{{ __('cruds.status.title') }} {{ __('cruds.mpendonor.title') }}</strong>
                        <div class="icheck-primary">
                            <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                            <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-right btn-add-mpendonor" data-toggle="tooltip" data-placement="top" title="{{ __('global.submit') }}"><i class="fas fa-save"></i> {{ __('global.submit') }}</button>
                </form>


            </div>
        </div>
        <div class="card card-outline card-primary">
            <div class="card-body">
                <div class="row responsive listdonor">
                    <div class="col-12 table-responsive">
                        <table id="mpendonor" class="table table-sm table-hover display text-nowrap table-bordered cell-border ajaxTable datatable-mpendonor dataTable" style="width:100%">
                            {{-- table table-sm table-bordered table-hover datatable-kegiatan display text-nowrap --}}
                            <thead>
                                <tr>
                                    <th class="center">No.</th>
                                    <th>{{ __('cruds.kategoripendonor.title') }}</th>
                                    <th>{{ __('cruds.mpendonor.title') }}</th>
                                    <th>{{ __('cruds.mpendonor.pic') }}</th>
                                    <th>{{ __('cruds.mpendonor.email') }}</th>
                                    <th>{{ __('cruds.mpendonor.phone') }}</th>
                                    <th>{{ __('cruds.mpendonor.jumlah_donasi') }}</th>
                                    <th>{{ __('cruds.mpendonor.total_nilai') }}</th>
                                    <th>{{ __('cruds.status.title') }}</th>
                                    <th>{{ __('cruds.status.action') }}</th>  
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   
    {{-- @include('master.mpendonor.create') --}}
    @include('master.mpendonor.edit')
    @include('master.mpendonor.show')
    @stop
    
    @push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @endpush
    
@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)

    @include('master.mpendonor.js')
    
    
    {{-- Mempersiapkan isian dari select2
    harus disini ternyata script ne wkwk 
    @Gedeadi sesat wkwkwk --}}
    <script>
        $.ajax({
                url:  '{{ route('pendonor.create') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response){
                    let data = response.map(function(item) {
                        return {
                            id: item.id,
                            text: item.nama,
                        };
                    });
                    
                    $('#kategoripendonor_add').select2({
                        placeholder: "{{ __('global.pleaseSelect') }} {{ __('cruds.kategoripendonor.title')}}",
                        allowClear: true,
                        delay: 250,
                        data : data,
                    });
                    $(document).on('select2:open', function() {
                        setTimeout(function() {
                            document.querySelector('.select2-search__field').focus();
                        }, 100);
                    });
                },error: function(jqXHR, textStatus, errorThrown) {
                    const errorData = JSON.parse(jqXHR.responseText);
                    const errors = errorData.errors; // Access the error object
                    let errorMessage = "";
                    for (const field in errors) {
                        errors[field].forEach(error => {
                            errorMessage +=
                            `* ${error}\n`; // Build a formatted error message string
                        });
                    }
                    Swal.fire({
                        title: jqXHR.statusText,
                        text: errorMessage,
                        icon: 'error'
                    });
                }
            });
    </script>
@endpush
