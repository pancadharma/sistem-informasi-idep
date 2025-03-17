@extends('layouts.app')

@section('subtitle', __('cruds.beneficiary.list'))
@section('content_header_title')
    @can('meals_access')
        <a class="btn-success btn">Wilayah Dependency</a>
    @endcan
@endsection
@section('sub_breadcumb', __('Wilayah Dependency'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-graph-up"></i>
                Wilayah Dependency
            </h3>
            <div class="card-tools">
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <select class="form-control select2" id="provinsi" name="provinsi">
                            <option value="">Pilih Provinsi</option>
                        </select>
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
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

@endpush


