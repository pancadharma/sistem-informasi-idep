@extends('tr.btor.layouts.print-layout')

@section('title', 'BTOR Report - ' . $kegiatan->programOutcomeOutputActivity->nama ?? $kegiatan->id)

@section('content')
<div class="print-container">
    <div class="report-header">
        <div class="text-center">
            <h2>{{ __('btor.btor') }}</h2>
            <h3>(BTOR)</h3>
        </div>

        <div class="report-info" style="margin-top: 10px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <table class="table-print" style="font-size: 9pt;">
                            <tr>
                                <td><strong>{{ __('btor.tgl_pelaporan') }}</strong></td>
                                <td>:</td>
                                <td>{{ now()->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('btor.tahun_anggaran') }}</strong></td>
                                <td>:</td>
                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('Y') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <table class="table-print" style="font-size: 9pt;">
                            <tr>
                                <td width="35%"><strong>{{ __('btor.organization') }}</strong></td>
                                <td width="5%">:</td>
                                <td>IDEP Foundation</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('btor.departemen') }}</strong></td>
                                <td>:</td>
                                <td>Programs</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('btor.fase_pelaporan') }}</strong></td>
                                <td>:</td>
                                <td>{{ __('btor.fase') }} {{ $kegiatan->fasepelaporan ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="section">
        <h4 class="section-title">I. {{ __('btor.basic_information') }}</h4>
        <table class="table-print" style="font-size: 9pt;">
            <tr>
                <td width="28%"><strong>{{ __('btor.nama_program') }}</strong></td>
                <td width="2%">:</td>
                <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.kode_program') }}</strong></td>
                <td>:</td>
                <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.nama_kegiatan') }}</strong></td>
                <td>:</td>
                <td>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.kode_kegiatan') }}</strong></td>
                <td>:</td>
                <td>{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.jenis_kegiatan') }}</strong></td>
                <td>:</td>
                <td><strong>{{ $kegiatan->jenisKegiatan?->nama ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.periode') }}</strong></td>
                <td>:</td>
                <td>
                    @if($kegiatan->tanggalmulai && $kegiatan->tanggalselesai)
                        {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d F Y') }} -
                        {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d F Y') }}
                        ({{ $kegiatan->getDurationInDays() }} days)
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.penulis_laporan') }}</strong></td>
                <td>:</td>
                <td>
                    @if($kegiatan->datapenulis?->count() > 0)
                        {{ $kegiatan->datapenulis->pluck('nama')->filter()->implode(', ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.penulis_jabatan') }}</strong></td>
                <td>:</td>
                <td>
                    @if($kegiatan->datapenulis?->count() > 0)
                        {{ $kegiatan->kegiatan_penulis->pluck('peran.nama')->filter()->implode(', ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.sektor_kegiatan') }}</strong></td>
                <td>:</td>
                <td>
                    @if($kegiatan->sektor?->count() > 0)
                        {{ $kegiatan->sektor->pluck('nama')->implode(', ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.mitra_kegiatan') }}</strong></td>
                <td>:</td>
                <td>
                    @if($kegiatan->mitra?->count() > 0)
                        {{ $kegiatan->mitra->pluck('nama')->implode(', ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.status') }}</strong></td>
                <td>:</td>
                <td><strong>{{ strtoupper($kegiatan->status ?? 'DRAFT') }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- Activity Specific Details --}}
    <div class="section page-break">
        <h4 class="section-title">II. {{ __('btor.activity_details') }}</h4>
        @include($viewPath, ['kegiatan' => $kegiatan])
    </div>

    {{-- Description --}}
    <div class="section">
        <h4 class="section-title">III. {{ __('btor.activity_description') }}</h4>

        <div class="subsection">
            <h5>A. {{ __('btor.latar_belakang_kegiatan') }}</h5>
            <div class="content-box">
                {!! $kegiatan->deskripsilatarbelakang ?? '<em>No data</em>' !!}
            </div>
        </div>

        <div class="subsection">
            <h5>B. {{ __('btor.tujuan_kegiatan') }}</h5>
            <div class="content-box">
                {!! $kegiatan->deskripsitujuan ?? '<em>No data</em>' !!}
            </div>
        </div>

        <div class="subsection">
            <h5>C. {{ __('btor.keluaran_kegiatan') }}</h5>
            <div class="content-box">
                {!! $kegiatan->deskripsikeluaran ?? '<em>No data</em>' !!}
            </div>
        </div>

        @if($kegiatan->deskripsiyangdikaji)
            <div class="subsection">
                <h5>D. Subjects Studied</h5>
                <div class="content-box">
                    {!! $kegiatan->deskripsiyangdikaji !!}
                </div>
            </div>
        @endif
    </div>

    {{-- Location - New Page --}}
    <div class="section page-break">
        <h4 class="section-title">IV. {{ __('cruds.kegiatan.tempat') }}</h4>

        @if($kegiatan->lokasi?->count() > 0)
            <table class="table-bordered" style="font-size: 8pt;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 18%;">Location</th>
                        <th style="width: 15%;">Village</th>
                        <th style="width: 15%;">Sub-District</th>
                        <th style="width: 17%;">District</th>
                        <th style="width: 15%;">Province</th>
                        <th style="width: 15%;">Coordinates</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatan->lokasi as $index => $lokasi)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $lokasi->lokasi ?? '-' }}</td>
                            <td>{{ $lokasi->desa?->nama ?? '-' }}</td>
                            <td>{{ $lokasi->desa?->kecamatan?->nama ?? '-' }}</td>
                            <td>{{ $lokasi->desa?->kecamatan?->kabupaten?->nama ?? '-' }}</td>
                            <td>{{ $lokasi->desa?->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}</td>
                            <td class="text-center">
                                @if($lokasi->lat && $lokasi->long)
                                    {{ number_format($lokasi->lat, 4) }}, {{ number_format($lokasi->long, 4) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p><em>No location data available.</em></p>
        @endif
    </div>

    {{-- Beneficiaries --}}
    <div class="section">
        <h4 class="section-title">V. BENEFICIARIES INFORMATION</h4>

        <table class="table-bordered" style="font-size: 8pt;">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 25%; vertical-align: middle;">Category</th>
                    <th colspan="3" class="text-center">Gender Distribution</th>
                </tr>
                <tr>
                    <th class="text-center" style="width: 25%;">Female</th>
                    <th class="text-center" style="width: 25%;">Male</th>
                    <th class="text-center" style="width: 25%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Adults</strong></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Elderly</strong></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Youth</strong></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Children</strong></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</strong></td>
                </tr>
                <tr class="table-secondary">
                    <td><strong>Persons with Disabilities</strong></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdisabilitastotal ?? 0) }}</strong></td>
                </tr>
                <tr class="table-secondary">
                    <td><strong>Non-Disability</strong></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatnondisabilitastotal ?? 0) }}</strong></td>
                </tr>
                <tr class="table-secondary">
                    <td><strong>Marginalized Groups</strong></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</strong></td>
                </tr>
                <tr class="table-active">
                    <td><strong>GRAND TOTAL</strong></td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Signature Section --}}
    <div class="section" style="margin-top: 40px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    <div class="signature-box">
                        <p><strong>Prepared by:</strong></p>
                        <br><br><br>
                        <p>
                            @if($kegiatan->datapenulis?->first())
                                <strong><u>{{ $kegiatan->datapenulis->first()->nama }}</u></strong><br>
                                <em>{{ $kegiatan->datapenulis->first()->kegiatanPeran?->nama ?? 'Staff' }}</em>
                            @else
                                <strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong><br>
                                <em>Report Writer</em>
                            @endif
                        </p>
                        <p style="margin-top: 5px;"><small>Date: {{ now()->format('d F Y') }}</small></p>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    <div class="signature-box">
                        <p><strong>Approved by:</strong></p>
                        <br><br><br>
                        <p>
                            @if($kegiatan->user)
                                <strong><u>{{ $kegiatan->user->name }}</u></strong><br>
                                <em>Program Coordinator</em>
                            @else
                                <strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong><br>
                                <em>Supervisor</em>
                            @endif
                        </p>
                        <p style="margin-top: 5px;"><small>Date: _________________</small></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="report-footer">
        <table style="width: 100%; border: none; font-size: 8pt;">
            <tr>
                <td style="width: 70%;">
                    <strong>Note:</strong> This is an official report of IDEP Foundation.
                    For inquiries, please contact the Program Department.
                </td>
                <td style="width: 30%; text-align: right;">
                    Page <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
