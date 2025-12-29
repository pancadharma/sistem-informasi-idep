<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>BTOR Export</title>
    <style>
        @page {
            margin: 2.5cm 2.5cm 2.5cm 2.5cm;
        }

        body {
            font-family: 'Tahoma', sans-serif;
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
            color: #0F7001;
        }

        .footer-line {
            border-top: 1px double #000000;
            margin-bottom: 5px;
            width: 100%;
        }

        .footer-bold {
            font-weight: bold;
            color: #0D654D;
        }

        /** HEADINGS **/
        h1 {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 15pt;
            margin-bottom: 5pt;
        }

        /** TABLES **/
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table th, .data-table td {
            border: 1px solid #000000;
            padding: 4px;
            vertical-align: middle;
            font-size: 10pt;
        }

        .data-table th {
            background-color: #385623;
            color: #FFFFFF;
            font-weight: bold;
            text-align: center;
        }

        .no-border-table td {
            border: none;
            padding: 2px;
            vertical-align: top;
        }
        
        .label-col { width: 25%; font-weight: bold; }
        .sep-col { width: 2%; text-align: center; }
        .val-col { width: 73%; }

        /** UTILS **/
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .mt-2 { margin-top: 10px; }

        /** HTML CONTENT STYLING FIX **/
        /* This ensures paragraphs inside your data don't add too much spacing */
        .html-content p {
            margin: 0 0 5px 0;
            text-align: justify;
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
            <div style="font-size: 14pt; font-weight: bold;">YAYASAN IDEP</div>
        @endif
    </header>

    <footer>
        <div class="footer-line"></div>
        <div class="footer-bold">Yayasan IDEP Selaras Alam</div>
        <div>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali, Indonesia</div>
        <div>Telp/Fax : +62-361-908-2983 / +62-812 4658 5137</div>
        <div>Dihasilkan pada: {{ date('d-m-Y H:i:s') }}</div>
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
                    <table class="no-border-table">
                        <tr>
                            <td class="label-col">Departemen</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">Program</td>
                        </tr>
                        <tr>
                            <td class="label-col">Program</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Nama Kegiatan</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Kode Budget</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Penulis Laporan</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">
                                {{ $kegiatan->kegiatan_penulis->map(fn($p) => $p->user->nama ?? '')->filter()->implode(', ') ?: '-' }}
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- A. Latar Belakang --}}
                <h1>A. Latar Belakang Kegiatan</h1>
                {{-- FIX: Removed e() and nl2br() --}}
                <div class="html-content">{!! $kegiatan->deskripsilatarbelakang !!}</div>

                {{-- B. Tujuan --}}
                <h1>B. Tujuan Kegiatan</h1>
                {{-- FIX: Removed e() and nl2br() --}}
                <div class="html-content">{!! $kegiatan->deskripsitujuan !!}</div>

                {{-- C. Detail Kegiatan --}}
                <h1>C. Detail Kegiatan</h1>
                <table class="no-border-table">
                    <tr>
                        <td class="label-col">Hari, Tanggal</td>
                        <td class="sep-col">:</td>
                        <td class="val-col">
                            @php
                                $start = $kegiatan->tanggalmulai ? \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y') : 'Tidak ditentukan';
                                $end = $kegiatan->tanggalselesai ? \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y') : null;
                                $durasi = method_exists($kegiatan, 'getDurationInDays') ? $kegiatan->getDurationInDays() : 0;
                            @endphp
                            {{ $start }} 
                            @if($end && $start != $end) - {{ $end }} @endif
                            @if($durasi > 0) ({{ $durasi }} hari) @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">Tempat</td>
                        <td class="sep-col">:</td>
                        <td class="val-col">
                            {{ $kegiatan->lokasi->map(fn($l) => $l->lokasi)->filter()->implode(', ') ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">Pihak yang terlibat</td>
                        <td class="sep-col">:</td>
                        <td class="val-col">
                            {{ $kegiatan->mitra->map(fn($m) => $m->nama)->filter()->implode(', ') ?: '-' }}
                        </td>
                    </tr>
                </table>

                @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
                    <div class="text-bold mt-2">Tabel Lokasi</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Lokasi</th>
                                <th>Desa</th>
                                <th>Kecamatan</th>
                                <th>Kabupaten</th>
                                <th>Provinsi</th>
                                <th>Koordinat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kegiatan->lokasi as $idx => $loc)
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>{{ $loc->lokasi }}</td>
                                    <td>{{ $loc->desa?->nama }}</td>
                                    <td>{{ $loc->desa?->kecamatan?->nama }}</td>
                                    <td>{{ $loc->desa?->kecamatan?->kabupaten?->nama }}</td>
                                    <td>{{ $loc->desa?->kecamatan?->kabupaten?->provinsi?->nama }}</td>
                                    <td class="text-center">
                                        @if($loc->lat && $loc->long)
                                            {{ number_format($loc->lat, 6) }}, {{ number_format($loc->long, 6) }}
                                        @else - @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- D. Hasil Kegiatan --}}
                <h1>D. Hasil Kegiatan</h1>
                <div class="text-bold">a. Jumlah partisipan yang terlibat dan disagregat</div>
                <p>Silakan mengisi tabel berikut:</p>

                @if($kegiatan->penerimamanfaattotal > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="40%">Kategori</th>
                                <th width="20%">Perempuan</th>
                                <th width="20%">Laki-laki</th>
                                <th width="20%">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dewasa (25-59 tahun)</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasaperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasalakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasatotal }}</td>
                            </tr>
                            <tr>
                                <td>Lansia (60+ tahun)</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiaperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansialakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiatotal }}</td>
                            </tr>
                            <tr>
                                <td>Remaja (18-24 tahun)</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajaperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajalakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajatotal }}</td>
                            </tr>
                            <tr>
                                <td>Anak (< 18 tahun)</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanakperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaklakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaktotal }}</td>
                            </tr>
                            <tr style="font-weight: bold;">
                                <td>GRAND TOTAL</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatperempuantotal }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlakilakitotal }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaattotal }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p>Tidak ada data penerima manfaat.</p>
                @endif

                <div class="text-bold mt-2">b. Hasil pertemuan</div>
                {{-- FIX: Removed e() and nl2br() --}}
                <div class="html-content">{!! $kegiatan->deskripsikeluaran !!}</div>

                {{-- E. Tantangan --}}
                <h1>E. Tantangan dan Solusi</h1>
                {{-- FIX: Removed e() and nl2br() --}}
                <div class="html-content">
                    {!! $specific['kendala'] ?? 'Tidak ada data tantangan.' !!}
                </div>

                {{-- F. Isu --}}
                <h1>F. Isu yang Perlu Diperhatikan / Rekomendasi</h1>
                {{-- FIX: Removed e() and nl2br() --}}
                <div class="html-content">
                    {!! $specific['isu'] ?? 'Tidak ada data isu.' !!}
                </div>

                {{-- G. Pembelajaran --}}
                <h1>G. Pembelajaran</h1>
                {{-- FIX: Removed e() and nl2br() --}}
                <div class="html-content">
                    {!! $specific['pembelajaran'] ?? 'Tidak ada data pembelajaran.' !!}
                </div>

                {{-- H. Dokumen --}}
                <h1>H. Dokumen Pendukung</h1>
                @php
                    $docs = $kegiatan->getDokumenPendukung();
                    $media = $kegiatan->getMediaPendukung();
                @endphp
                
                @if(($docs && $docs->count() > 0) || ($media && $media->count() > 0))
                    @if($docs && $docs->count() > 0)
                        <div class="text-bold">Dokumen:</div>
                        <ul>
                            @foreach($docs as $d)
                                <li>{{ $d->file_name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    
                    @if($media && $media->count() > 0)
                        <div class="text-bold">Media:</div>
                        <ul>
                            @foreach($media as $m)
                                <li>{{ $m->file_name }}</li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p>Tidak ada dokumen pendukung.</p>
                @endif

            </div>
        @endforeach
    </main>
</body>
</html>