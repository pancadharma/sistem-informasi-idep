@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.edit'))
@section('content_header_title') <strong>{{ __('cruds.kegiatan.edit') }}</strong>@endsection
@section('sub_breadcumb')<a href="{{ route('kegiatan.index') }}" title="{{ __('cruds.kegiatan.list') }}"> {{ __('cruds.kegiatan.list') }}</a>@endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.kegiatan.edit') }}">{{ __('cruds.kegiatan.edit') }}</span>@endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')

    <h1>Edit Kegiatan: {{ $kegiatan->nama }}</h1>
    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#basic" data-toggle="tab">Basic</a></li>
            <li class="nav-item"><a class="nav-link" href="#description" data-toggle="tab">Description</a></li>
            <li class="nav-item"><a class="nav-link" href="#file-uploads" data-toggle="tab">File Uploads</a></li>
            <li class="nav-item"><a class="nav-link" href="#hasil" data-toggle="tab">Hasil</a></li>
            <li class="nav-item"><a class="nav-link" href="#penulis" data-toggle="tab">Penulis</a></li>
            <li class="nav-item"><a class="nav-link" href="#lokasi" data-toggle="tab">Lokasi</a></li>
            <li class="nav-item"><a class="nav-link" href="#maps" data-toggle="tab">Maps</a></li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Basic Tab -->
            <div id="basic" class="tab-pane active">
                @include('tr.kegiatan.tabs.basic', [
                    'kegiatan' => $kegiatan,
                    'program' => $program,
                    'statusOptions' => $statusOptions,
                    'programoutcomeoutputactivities' => $programoutcomeoutputactivities
                ])

            </div>

            <!-- Description Tab -->
            <div id="description" class="tab-pane">
                @include('tr.kegiatan.tabs.description', ['kegiatan' => $kegiatan])
            </div>

            <!-- File Uploads Tab -->
            <div id="file-uploads" class="tab-pane">
                @include('tr.kegiatan.tabs.file-uploads', [
                    'dokumenPendukung' => $dokumenPendukung,
                    'mediaPendukung' => $mediaPendukung
                ])
            </div>

            <!-- Hasil Tab -->
            <div id="hasil" class="tab-pane">
                @include('tr.kegiatan.tabs.hasil', [
                    'kegiatan' => $kegiatan,
                    'relatedData' => $relatedData
                ])
            </div>

            <!-- Penulis Tab -->
            <div id="penulis" class="tab-pane">
                @include('tr.kegiatan.tabs.penulis', [
                    'kegiatanPenulis' => $kegiatanPenulis
                ])
            </div>

            <!-- Lokasi Tab -->
            <div id="lokasi" class="tab-pane">
                {{-- @include('tr.kegiatan.tabs.lokasi', ['lokasi' => $kegiatan->lokasi]) --}}
            </div>

            <!-- Maps Tab -->
            <div id="maps" class="tab-pane">
                {{-- @include('tr.kegiatan.tabs.maps') --}}
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Kegiatan</button>
    </form>

@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
    <style>
        .card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
            position: sticky;
            z-index: 1030;
            top: 0;
        }

        .select2-selection.is-invalid-select2 {
            border-color: #dc3545 !important;
        }
        .select2-selection.is-valid-select2 {
            border-color: #28a745 !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single.is-invalid-select2 {
            border-color: #a7dc35 !important;
        }
    </style>
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

    <script>
        $(document).ready(function() {
            // Initialize Select2 for dropdowns
            $('#jeniskegiatan_id, #sektor_id, #mitra_id, #provinsi_id, #kabupaten_id, #kecamatan_id, #kelurahan_id').select2({
                ajax: {
                    url: function() {
                        return $(this).data('url');
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { search: params.term, page: params.page || 1 };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results || data.data,
                            pagination: { more: data.pagination ? data.pagination.more : false }
                        };
                    }
                },
                minimumInputLength: 2
            });

            // Pre-select Jenis Kegiatan
            @if($kegiatan->jeniskegiatan_id)
                $('#jeniskegiatan_id').select2('trigger', 'select', {
                    data: { id: {{ $kegiatan->jeniskegiatan_id }}, text: '{{ $kegiatan->jenisKegiatan->nama }}' }
                });
            @endif

            // Pre-select Sektor
            @foreach($kegiatan->sektor as $sektor)
                $('#sektor_id').select2('trigger', 'select', {
                    data: { id: {{ $sektor->id }}, text: '{{ $sektor->nama }}' }
                });
            @endforeach

            // Pre-select Mitra
            @foreach($kegiatan->mitra as $mitra)
                $('#mitra_id').select2('trigger', 'select', {
                    data: { id: {{ $mitra->id }}, text: '{{ $mitra->nama }}' }
                });
            @endforeach

            // Pre-select Penulis
            @foreach($kegiatanPenulis as $penulis)
                $('#penulis').select2('trigger', 'select', {
                    data: { id: {{ $penulis['id'] }}, text: '{{ $penulis['text'] }}' }
                });
                $('#jabatan').select2('trigger', 'select', {
                    data: { id: {{ $penulis['peran_id'] }}, text: '{{ $penulis['peran_nama'] }}' }
                });
            @endforeach

            // Initialize location dropdowns with pre-selected values
            @foreach($kegiatan->lokasi as $lokasi)
                $('#kelurahan_id').select2('trigger', 'select', {
                    data: { id: {{ $lokasi->desa_id }}, text: '{{ $lokasi->desa->nama }}' }
                });
            @endforeach
        });


        $(document).ready(function() {
            let locationIndex = {{ $kegiatan->lokasi->count() }};

            $('#add-location').click(function() {
                let row = `
                    <div class="location-row">
                        <select name="lokasi[${locationIndex}][desa_id]" class="form-control kelurahan_id" data-url="{{ route('api.kegiatan.kelurahan') }}"></select>
                        <input name="lokasi[${locationIndex}][lokasi]" class="form-control">
                        <input name="lokasi[${locationIndex}][lat]" class="form-control">
                        <input name="lokasi[${locationIndex}][long]" class="form-control">
                        <button type="button" class="btn btn-danger remove-location">Remove</button>
                    </div>`;
                $('#location-container').append(row);
                $('.kelurahan_id').last().select2({ /* same config as above */ });
                locationIndex++;
            });

            $(document).on('click', '.remove-location', function() {
                $(this).closest('.location-row').remove();
            });
        });
    </script>
@endpush
