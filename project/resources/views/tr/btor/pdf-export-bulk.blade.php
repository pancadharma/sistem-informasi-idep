<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>BTOR REPORT EXPORTS</title>
    <style>
        /* Apply Figtree font as default */
        body, html, * {
            font-family: 'Figtree', sans-serif !important;
        }
        
        /* Preserve Font Awesome and Material Icons */
        .fa, .fas, .far, .fal, .fab, .fad, .material-symbols-outlined, .material-symbols-sharp,
        [class^="fa-"], [class*=" fa-"],
        [class^="icon-"], [class*=" icon-"],
        .bi, [class^="bi-"], [class*=" bi-"] {
            font-family: 'Font Awesome 5 Free', 'Font Awesome 5 Brands', 'FontAwesome', 'Material Symbols Outlined', 'Material Symbols Sharp', 'bootstrap-icons' !important;
        }

        @page {
            margin: 2.5cm 2.5cm 2.5cm 2.5cm;
        }   

        body {
            font-family: 'Figtree', sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000000;
        }

        /** HEADER & FOOTER **/
        header {
            position: fixed;
            top: -2cm;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -2.5cm;
            left: 0;
            right: 0;
            height: 80px;
            font-size: 8pt;
            text-align: center;
            color: #526d4e;
        }

        .footer-line {
            border-top: 1px double #000000;
            margin-bottom: 5px;
            width: 100%;
        }

        .footer-bold {
            font-weight: bold;
            color: #526d4e;
        }

        /** HEADINGS **/
        h1, h2, h3, h4, h5 {
            font-family: 'Figtree', sans-serif;
            color: #000;
            margin: 0;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15pt;
            margin-bottom: 5pt;
            text-transform: none;
            border: none;
            text-transform: uppercase;
            background-color: #526d4e;
            color: white;
        }

        /** TABLES **/
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        /* Info Tables (No Borders) */
        .table-print td {
            padding: 2px 4px;
            vertical-align: top;
            border: none;
        }
        
        .label-col { width: 25%; font-weight: bold; }
        .sep-col { width: 2%; text-align: center; }
        .val-col { width: 73%; }

        /* Data Tables (Borders) */
        .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important;
            padding: 4px;
            vertical-align: middle;
        }

        /* GREEN HEADERS */
        .table-bordered thead th, 
        .table-bordered th {
            background-color: #385623 !important;
            color: #FFFFFF !important;
            font-weight: bold;
            text-align: center;
        }

        /** UTILS **/
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .mt-2 { margin-top: 10px; }
        .mb-2 { margin-bottom: 10px; }

        /** HTML CONTENT STYLING FIX **/
        .html-content { text-align: justify; }
        .html-content p {
            margin: 0 0 5px 0;
        }
        .html-content ul, .html-content ol {
            margin-top: 0;
            margin-bottom: 5px;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <header>
        @if(file_exists(public_path('images/uploads/header.png')))
            <img src="{{ public_path('images/uploads/header.png') }}" style="height: 38px; width: auto;">
        @else
            <div style="font-size: 14pt; font-weight: bold;">IDEP SELARAS ALAM</div>
        @endif
    </header>

    <footer>
        <div class="footer-line"></div>
        <div class="footer">
            <strong>Office</strong>: Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia | Telp/Fax: +62-361 9082983 | www.idepfoundation.org
        </div>
        {{-- 
        <div class="footer-bold">Yayasan IDEP Selaras Alam</div>
        <div>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali, Indonesia</div>
        <div>Telp/Fax : +62-361-908-2983 / +62-812 4658 5137</div> --}}
        {{-- <div>{{ __('btor.generated_at') }}: {{ date('d-m-Y H:i:s') }}</div> --}}
    </footer>

    <main>
        @foreach($dataList as $index => $item)
            @php
                $kegiatan = $item->kegiatan;
                $specific = $item->specific;
            @endphp

            <div class="{{ !$loop->last ? 'page-break' : '' }}">
                
                {{-- Metadata Table --}}
                <div style="border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0; margin-top: 20px;">
                    <table class="table-print">
                        <tr>
                            <td class="label-col">{{ __('btor.departemen') }}</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ __('btor.program') }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">{{ __('btor.program') }}</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">{{ __('btor.nama_kegiatan') }}</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">{{ __('btor.kode_budget') }}</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">{{ __('btor.penulis_laporan') }}</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">
                                {{ $kegiatan->kegiatan_penulis->map(fn($p) => $p->user->nama ?? '')->filter()->implode(', ') ?: '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">{{ __('btor.penulis_jabatan') }}</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">
                                {{ $kegiatan->kegiatan_penulis->map(fn($p) => $p->peran->nama ?? '')->filter()->implode(', ') ?: '-' }}
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- A. Latar Belakang --}}
                <div class="section-title">A. {{ __('btor.latar_belakang_kegiatan') }}</div>
                <div class="html-content">{!! $kegiatan->deskripsilatarbelakang ?? '-' !!}</div>

                {{-- B. Tujuan --}}
                <div class="section-title">B. {{ __('btor.tujuan_kegiatan') }}</div>
                <div class="html-content">{!! $kegiatan->deskripsitujuan ?? '-' !!}</div>

                {{-- C. Detail Kegiatan --}}
                <div class="section-title">C. {{ __('btor.detail_kegiatan') }}</div>
                <table class="table-print">
                    <tr>
                        <td class="label-col">{{ __('btor.tanggal_mulai') }}</td>
                        <td class="sep-col">:</td>
                        <td class="val-col">
                            @php
                                $start = $kegiatan->tanggalmulai ? \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') : '-';
                                $end = $kegiatan->tanggalselesai ? \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') : null;
                                $durasi = $kegiatan->getDurationInDays();
                            @endphp
                            {{ $start }} 
                            @if($end && $start != $end) - {{ $end }} @endif
                            @if($durasi > 0) ({{ $durasi }} {{ __('btor.hari') }}) @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">{{ __('btor.tempat') }}</td>
                        <td class="sep-col">:</td>
                        <td class="val-col">
                            @if($kegiatan->lokasi?->count() > 0)
                                <ul style="margin: 0; padding-left: 15px;">
                                    @foreach($kegiatan->lokasi as $lok)
                                        <li>{{ $lok->lokasi }}, {{ $lok->desa?->nama }}, {{ $lok->desa?->kecamatan?->nama }}</li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">{{ __('cruds.partner.title') }} / Partner</td>
                        <td class="sep-col">:</td>
                        <td class="val-col">
                            {{ $kegiatan->mitra->map(fn($m) => $m->nama)->filter()->implode(', ') ?: '-' }}
                        </td>
                    </tr>
                </table>

                @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
                    <div class="text-bold mt-2 mb-2">{{ __('btor.tabel_lokasi') }}</div>
                    @include('tr.btor.partials.location', ['kegiatan' => $kegiatan])
                @endif

                {{-- D. Hasil Kegiatan --}}
                <div class="section-title">D. {{ __('btor.hasil.label') }}</div>
                
                {{-- Dynamic Activity Content --}}
                @include('tr.btor.partials.hasil-dinamis-print', ['kegiatan' => $kegiatan])

                <div class="text-bold mt-2">a. {{ __('btor.partisipan_disagregat') }}</div>

                @if($kegiatan->penerimamanfaattotal > 0)
                    <table class="table-bordered" style="margin-top: 5px;">
                        <thead>
                            <tr>
                                <th width="40%">{{ __('btor.penerima_manfaat') }}</th>
                                <th width="15%">{{ __('btor.perempuan') }}</th>
                                <th width="15%">{{ __('btor.laki_laki') }}</th>
                                <th width="15%">{{ __('btor.lainnya') }}</th>
                                <th width="15%">{{ __('btor.sub_total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ __('btor.umur_25_59') }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasaperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasalakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasalainnya ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasatotal }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('btor.umur_60_ke_atas') }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiaperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansialakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansialainnya ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiatotal }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('btor.umur_18_24') }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajaperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajalakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajalainnya ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajatotal }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('btor.umur_18_kebawah') }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanakperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaklakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaklainnya ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaktotal }}</td>
                            </tr>
                            <tr style="font-weight: bold; background-color: #f2f2f2;">
                                <td>{{ strtoupper(__('btor.grand_total')) }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatperempuantotal }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlakilakitotal }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlainnyatotal ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaattotal }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-bold mt-2">{{ __('btor.table_kelompok_khusus') }}</div>
                    <table class="table-bordered" style="margin-top: 5px;">
                        <thead>
                            <tr>
                                <th width="40%">{{ __('btor.penerima_manfaat') }}</th>
                                <th width="15%">{{ __('btor.perempuan') }}</th>
                                <th width="15%">{{ __('btor.laki_laki') }}</th>
                                <th width="15%">{{ __('btor.lainnya') }}</th>
                                <th width="15%">{{ __('btor.sub_total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ __('btor.penyandang_disabilitas') }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitasperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitaslakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitaslainnya ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitastotal }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('btor.non_disabilitas') }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitasperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitaslakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitaslainnya ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitastotal }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('btor.kelompok_marjinal_lainnya') }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinalperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinallakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinallainnya ?? 0 }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinaltotal }}</td>
                            </tr>
                            <tr style="font-weight: bold; background-color: #f2f2f2;">
                                <td>{{ strtoupper(__('btor.grand_total')) }}</td>
                                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</td>
                                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</td>
                                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlainnyatotal ?? 0) }}</td>
                                <td class="text-center">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p>{{ __('btor.no_data_participants') }}</p>
                @endif

                <div class="text-bold mt-2">b. {{ __('cruds.kegiatan.description.deskripsikeluaran') }}</div>
                <div class="html-content">{!! $kegiatan->deskripsikeluaran ?? '-' !!}</div>

                {{-- E. Tantangan --}}
                <div class="section-title">E. {{ __('btor.tantangan_solusi') }}</div>
                <div class="html-content">{!! $specific['kendala'] ?? __('btor.no_data_tantang_solusi') !!}</div>

                {{-- F. Isu --}}
                <div class="section-title">F. {{ __('btor.hasil.assessmentisu') }}</div>
                <div class="html-content">{!! $specific['isu'] ?? __('global.no_results') !!}</div>

                {{-- G. Pembelajaran --}}
                <div class="section-title">G. {{ __('btor.hasil.assessmentpembelajaran') }}</div>
                <div class="html-content">{!! $specific['pembelajaran'] ?? __('btor.no_data_pembelajaran') !!}</div>

                {{-- H. Dokumen --}}
                <div class="section-title">H. {{ __('btor.dokumen_pendukung') }}</div>
                @include('tr.btor.partials.dokumen', ['kegiatan' => $kegiatan])

                {{-- I. Catatan Penulis --}}
                <div class="section-title">I. {{ __('btor.catatan_penulis_laporan') }}</div>
                <div class="html-content">{!! $kegiatan->catatan_penulis ?? '-' !!}</div>

                {{-- J. Indikasi Perubahan --}}
                <div class="section-title">J. {{ __('btor.indikasi_perubahan') }}</div>
                <div class="html-content">{!! $kegiatan->indikasi_perubahan ?? '-' !!}</div>

            </div>
        @endforeach
    </main>
</body>
</html>