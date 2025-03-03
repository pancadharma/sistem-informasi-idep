@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.kegiatan.label'))
{{-- @section('content_header_title', __('global.details') . ' ' . __('cruds.kegiatan.label')) --}}
@section('content_header_title')
    <button type="button" class="btn btn-secondary btn-sm "  title="{{ __('global.print') .' '. __('cruds.kegiatan.label') }}">
        <!-- icon fa print-->
        <i class="fa fa-print"></i>
    </button>
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))

@section('content_body')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h1 class="card-title">
            {{-- {{ $kegiatan->activity->kode }} | {{ $kegiatan->activity->nama }} --}}
            {{ __('BACK TO OFFICE REPORT') }}
        </h1>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('kegiatan.index') }}`"
                title="{{ __('global.back') }}">
                <i class="fa fa-arrow-left"></i>
            </button>
        </div>
    </div>

    <div class="card-body m-0 p-0">
        <div class="detail">
            <table class= "table table-sm table-hover kegiatan-table-data">
                <tbody >
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.program_kode') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $kegiatan->activity->program_outcome_output->program_outcome->program->kode ?? '-' }}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.program_nama') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? '-'}}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.kode') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $kegiatan->activity->kode ?? '-' }}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.nama') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $kegiatan->activity->nama ?? '-'}}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.penulis.laporan') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            @foreach($kegiatan->kegiatan_penulis as $penulis)
                                {{ $penulis->nama ?? ''}},
                            @endforeach
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.penulis.jabatan') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            @foreach($kegiatan->kegiatan_penulis as $penulis)
                                {{ $penulis->kegiatanPeran->nama.',' ?? ''}}
                            @endforeach
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.jenis_kegiatan') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $kegiatan->jenisKegiatan->nama ?? '' }}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.penulis.jabatan') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            @foreach($kegiatan->sektor as $key => $value)
                                {{ $value->nama.',' ?? ''  }}
                            @endforeach
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.fase_pelaporan') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $kegiatan->fasepelaporan ?? ''}}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.tanggalmulai') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d-m-Y') ?? ''}}
                            ({{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->diffForHumans() ?? ''}})
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.tanggalselesai') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d-m-Y') ?? ''}}
                            ({{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->diffForHumans() ?? ''}})
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.durasi') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $durationInDays ?? '-' }}  {{ __('cruds.kegiatan.days') }}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.basic.nama_mitra') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            @foreach($kegiatan->mitra as $partner)
                                {{ $partner->nama.',' ?? '' }}
                            @endforeach
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.status') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            {{ $kegiatan->status ?? '' }}
                        </td>
                    </tr>
                    <tr class="align-middle">
                        <th class="tb-header mr-0 pr-0 align-middle">
                            {{ __('cruds.kegiatan.tempat') }}
                        </th>
                        <td class="separator ml-0 pl-0 text-center align-middle">:</td>
                        <td class="tb-value ml-0 pl-0 align-middle">
                            @if($kegiatan->lokasi->isNotEmpty())
                                {{ $kegiatan->lokasi->unique('kabupaten_id')->pluck('desa.kecamatan.kabupaten.nama')->implode(', ') }}
                                @if ($kegiatan->lokasi->unique('provinsi_id')->count() == 1)
                                     , {{ $kegiatan->lokasi->first()->desa->kecamatan->kabupaten->provinsi->nama ?? '' }}
                                @endif

                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-body">
        <!-- deskripsi kegiatan -->
        <div class="form-group row">
            <div class="col-sm col-md col-lg self-center">
                <label for="deskripsilatarbelakang" class="input-group">
                    {{ __('cruds.kegiatan.description.latar_belakang') }}
                    <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.description.latar_belakang_helper') }}"></i>
                </label>
                {!!  $kegiatan->deskripsilatarbelakang ?? '' !!}
            </div>
        </div>
        <!-- tujuan kegiatan -->
        <div class="form-group row">
            <div class="col-sm col-md col-lg self-center">
                <label for="deskripsitujuan" class="mb-0 input-group">
                    {{ __('cruds.kegiatan.description.tujuan') }}
                    <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.description.tujuan_helper') }}"></i>
                </label>
                {!!  $kegiatan->deskripsitujuan ?? '' !!}
            </div>
        </div>
        <!-- siapa deskripsi keluaran kegiatan -->
        <div class="form-group row">
            <div class="col-sm col-md col-lg self-center">
                <label for="deskripsikeluaran" class="mb-0 input-group">
                    {{ __('cruds.kegiatan.description.deskripsikeluaran') }}
                    <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.description.keluaran_helper') }}"></i>
                </label>

                {!!  $kegiatan->deskripsikeluaran ?? '' !!}
            </div>
        </div>
        <!-- Peserta yang terlibat -->
        <div class="form-group row mb-0">
            <div class="col-sm col-md col-lg self-center">
                <label class="mb-0 self-center input-group">
                    {{ __('cruds.kegiatan.peserta.label') }}
                    <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.peserta.helper') }}"></i>
                </label>
            </div>
        </div>
        <!-- jumlah peserta kegiatan -->
        <div class="form-group row">
            <div class="col-sm col-md col-lg self-center">
                <div class="card-body table-responsive p-0">
                    <table id="peserta_kegiatan_summary" class="table table-sm table-borderless table-info mb-0 table-custom" width="100%">
                        <thead style="background-color: #11bd7e !important">
                            <tr class="align-middle text-center text-nowrap">
                                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary"><label>{{ __('cruds.kegiatan.peserta.peserta') }}</label></th>
                                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary"><label>{{ __('cruds.kegiatan.peserta.wanita') }}</label></th>
                                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary"><label>{{ __('cruds.kegiatan.peserta.pria') }}</label></th>
                                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary"><label>{{ __('cruds.kegiatan.peserta.total') }}</label></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--dewasa row-->
                            <tr>
                                <td class="pl-1">
                                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatdewasaperempuan" name="penerimamanfaatdewasaperempuan" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0}}" >
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatdewasalakilaki" name="penerimamanfaatdewasalakilaki" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0}}" >
                                </td>
                                <td class="pl-1 pr-1">
                                    <input type="number" readonly id="penerimamanfaatdewasatotal" name="penerimamanfaatdewasatotal" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatdewasatotal ?? 0}}" >
                                </td>
                            </tr>
                            <!--lansia row-->
                            <tr>
                                <td class="pl-1">
                                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatlansiaperempuan" name="penerimamanfaatlansiaperempuan" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0}}" >
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatlansialakilaki" name="penerimamanfaatlansialakilaki" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatlansialakilaki ?? 0}}" >
                                </td>
                                <td class="pl-1 pr-1">
                                    <input type="number" readonly id="penerimamanfaatlansiatotal" name="penerimamanfaatlansiatotal" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatlansiatotal ?? 0}}" >
                                </td>
                            </tr>
                            <!--remaja row-->
                            <tr>
                                <td class="pl-1">
                                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.remaja') }}</label>
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatremajaperempuan" name="penerimamanfaatremajaperempuan" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatremajaperempuan ?? 0}}" >
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatremajalakilaki" name="penerimamanfaatremajalakilaki" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatremajalakilaki ?? 0}}" >
                                </td>
                                <td class="pl-1 pr-1">
                                    <input type="number" readonly id="penerimamanfaatperempuantotal" name="penerimamanfaatperempuantotal" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatremajatotal ?? 0 }}" >
                                </td>
                            </tr>
                            <!--anak-anak row-->
                            <tr>
                                <td class="pl-1">
                                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.anak') }}</label>
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatanakperempuan" name="penerimamanfaatanakperempuan" class="calculate form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatanakperempuan ?? 0}}" >
                                </td>
                                <td class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatanaklakilaki" name="penerimamanfaatanaklakilaki" class="calculate form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatanaklakilaki ?? 0}}">
                                </td>
                                <td class="pl-1 pr-1">
                                    <input type="number" readonly id="penerimamanfaatanaktotal" name="penerimamanfaatanaktotal" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatanaktotal ?? 0}}">
                                </td>
                            </tr>
                            <tr class="align-middle text-center text-nowrap">
                                <th class="pl-1 text-left">{{ __('cruds.kegiatan.peserta.total') }}</th>
                                <th class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatperempuantotal" name="penerimamanfaatperempuantotal" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatperempuantotal ?? 0}}" >
                                </th>
                                <th class="pl-1">
                                    <input type="number" readonly id="penerimamanfaatlakilakitotal" name="penerimamanfaatlakilakitotal" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0}}" >
                                </th>
                                <th class="pl-1 pr-1">
                                    <input type="number" readonly id="penerimamanfaattotal" name="penerimamanfaattotal" class="form-control-border border-width-2 form-control form-control-sm text-center" value="{{ $kegiatan->penerimamanfaattotal ?? 0}}" >
                                </th>
                            </tr>
                        </tbody>
                        <tfoot class="pl-1 pr-1">
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">

<style>
    .kegiatan-table-data .tb-header {
    width: 20%;
    }

    .kegiatan-table-data .separator {
        width: 1%;
    }

    .kegiatan-table-data .tb-value {
        width: 79%;
    }

    /* Optional: If you want to disable Bootstrap's responsive table behavior for this specific table */
    .kegiatan-table-data {
        width: 100% !important; /* Force the table to take full width */
        table-layout: fixed;  /* Crucial for fixed column widths */
    }
</style>

@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

@endpush
