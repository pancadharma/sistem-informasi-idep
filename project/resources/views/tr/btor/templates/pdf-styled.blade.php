<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BTOR - {{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'N/A' }}</title>
    <style>
        @page {
            margin: 15mm 20mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
        }
        .header-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .header-info {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .header-info p {
            margin: 3px 0;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            color: #000;
        }
        .section-content {
            text-align: justify;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        ul, ol {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        li {
            margin-bottom: 5px;
        }
        .footer-org {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            text-align: center;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header-title">BACK TO OFFICE REPORT</div>
    
    <div class="header-info">
        <p><strong>Department :</strong> Program</p>
        <p><strong>Program :</strong> {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program->nama ?? 'N/A' }}</p>
        <p><strong>Nama Kegiatan :</strong> {{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'N/A' }}</p>
        <p><strong>Kode budget :</strong> {{ $kegiatan->programOutcomeOutputActivity?->kode ?? 'N/A' }}</p>
        <p><strong>Penulis laporan :</strong> {{ $kegiatan->kegiatan_penulis->map(fn($p) => $p->user->nama)->join(', ') }}</p>
        <p><strong>Jabatan :</strong> {{ $kegiatan->kegiatan_penulis->map(fn($p) => $p->peran->nama)->join(', ') }}</p>
    </div>

    {{-- A. Latar Belakang --}}
    <div class="section-title">A. Latar Belakang Kegiatan</div>
    <div class="section-content">
        {!! nl2br(e($kegiatan->deskripsilatarbelakang ?? 'Tidak ada deskripsi latar belakang.')) !!}
    </div>

    {{-- B. Tujuan --}}
    <div class="section-title">B. Tujuan Kegiatan</div>
    <div class="section-content">
        {!! nl2br(e($kegiatan->deskripsitujuan ?? 'Tidak ada deskripsi tujuan.')) !!}
    </div>

    {{-- C. Detail Kegiatan --}}
    <div class="section-title">C. Detail Kegiatan</div>
    <div class="section-content">
        <p><strong>a. Hari, tanggal :</strong> 
            {{ $kegiatan->tanggalmulai ? \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d F Y') : 'N/A' }} - 
            {{ $kegiatan->tanggalselesai ? \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d F Y') : 'N/A' }}
        </p>
        <p><strong>b. Tempat :</strong> 
            {{ $kegiatan->lokasi_kegiatan->map(fn($l) => $l->desa->nama ?? 'N/A')->join(', ') }}
        </p>
        @if($kegiatan->mitra && $kegiatan->mitra->count() > 0)
        <p><strong>c. Pihak yang terlibat :</strong></p>
        <ul>
            @foreach($kegiatan->mitra as $mitra)
                <li>{{ $mitra->nama }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    {{-- D. Hasil Kegiatan --}}
    <div class="section-title">D. Hasil Kegiatan</div>
    
    <p><strong>a. Jumlah partisipan yang terlibat dan disagregat</strong></p>
    
    {{-- Age Group Table --}}
    <table>
        <thead>
            <tr>
                <th>Penerima Manfaat</th>
                <th class="text-center">Perempuan</th>
                <th class="text-center">Laki-laki</th>
                <th class="text-center">Lainnya</th>
                <th class="text-center">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Dewasa (umur 25 sampai 59 tahun)</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }}</td>
            </tr>
            <tr>
                <td>Lansia (umur 60 ke atas)</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatlansialakilaki ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }}</td>
            </tr>
            <tr>
                <td>Remaja (umur 18 - 24 tahun)</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatremajaperempuan ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatremajalakilaki ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatremajatotal ?? 0 }}</td>
            </tr>
            <tr>
                <td>Anak (umur 18 ke bawah)</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatanakperempuan ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatanaklakilaki ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatanaktotal ?? 0 }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td>Grand Total</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaattotal ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Disability/Marginal Group Table --}}
    <table>
        <thead>
            <tr>
                <th>Penerima Manfaat</th>
                <th class="text-center">Perempuan</th>
                <th class="text-center">Laki-laki</th>
                <th class="text-center">Lainnya</th>
                <th class="text-center">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Penyandang disabilitas</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatdisabilitasperempuan ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }}</td>
            </tr>
            <tr>
                <td>Non-disabilitas</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatnondisabilitastotal ?? 0 }}</td>
            </tr>
            <tr>
                <td>Kelompok marjinal lainnya</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatmarjinalperempuan ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatmarjinallakilaki ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatmarjinaltotal ?? 0 }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td>Grand Total</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}</td>
                <td class="text-center">0</td>
                <td class="text-center">{{ $kegiatan->penerimamanfaattotal ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>b. Hasil pertemuan</strong></p>
    <div class="section-content">
        {!! nl2br(e($kegiatan->deskripsikeluaran ?? 'Tidak ada deskripsi hasil pertemuan.')) !!}
    </div>

    {{-- Include specific jenis kegiatan content --}}
    @if($viewPath && View::exists($viewPath))
        @include($viewPath)
    @endif

    {{-- E. Tantangan dan Solusi --}}
    <div class="section-title">E. Tantangan dan Solusi</div>
    <div class="section-content">
        @php
            $specificData = null;
            switch($kegiatan->jeniskegiatan_id) {
                case 1: $specificData = $kegiatan->assessment; break;
                case 2: $specificData = $kegiatan->sosialisasi; break;
                case 3: $specificData = $kegiatan->pelatihan; break;
                case 4: $specificData = $kegiatan->pembelanjaan; break;
                case 8: $specificData = $kegiatan->monitoring; break;
                case 9: $specificData = $kegiatan->kunjungan; break;
                case 10: $specificData = $kegiatan->konsultasi; break;
                default: $specificData = $kegiatan->lainnya; break;
            }
        @endphp
        {!! nl2br(e($specificData ? ($specificData->assessmentkendala ?? $specificData->sosialisasikendala ?? 
            $specificData->pelatihanunggahan ?? $specificData->pembelanjaankendala ?? 
            $specificData->monitoringkendala ?? $specificData->kunjungankendala ?? 
            $specificData->konsultasikendala ?? $specificData->lainnyakendala ?? 'Tidak ada data tantangan.') : 'Tidak ada data tantangan.')) !!}
    </div>

    {{-- F. Isu yang Perlu Diperhatikan & Rekomendasi --}}
    <div class="section-title">F. Isu yang Perlu Diperhatikan & Rekomendasi</div>
    <div class="section-content">
        {!! nl2br(e($specificData ? ($specificData->assessmentisu ?? $specificData->sosialisasiisu ?? 
            $specificData->pelatihanisu ?? $specificData->pembelanjaanisu ?? 
            $specificData->monitoringisu ?? $specificData->kunjunganisu ?? 
            $specificData->konsultasiisu ?? $specificData->lainnyaisu ?? 'Tidak ada data isu.') : 'Tidak ada data isu.')) !!}
    </div>

    {{-- G. Pembelajaran --}}
    <div class="section-title">G. Pembelajaran</div>
    <div class="section-content">
        {!! nl2br(e($specificData ? ($specificData->assessmentpembelajaran ?? $specificData->sosialisasipembelajaran ?? 
            $specificData->pelatihanpembelajaran ?? $specificData->pembelanjaanpembelajaran ?? 
            $specificData->monitoringpembelajaran ?? $specificData->kunjunganpembelajaran ?? 
            $specificData->konsultasipembelajaran ?? $specificData->lainnyapembelajaran ?? 'Tidak ada data pembelajaran.') : 'Tidak ada data pembelajaran.')) !!}
    </div>

    {{-- H. Dokumen Pendukung --}}
    <div class="section-title">H. Dokumen Pendukung</div>
    <div class="section-content">
        @if($kegiatan->getDokumenPendukung() && $kegiatan->getDokumenPendukung()->count() > 0)
            <ul>
                @foreach($kegiatan->getDokumenPendukung() as $doc)
                    <li>{{ $doc->file_name }}</li>
                @endforeach
            </ul>
        @else
            <p>Tidak ada dokumen pendukung.</p>
        @endif
    </div>

    {{-- I. Catatan Penulis Laporan --}}
    <div class="section-title">I. Catatan Penulis Laporan</div>
    <div class="section-content">
        <p>-</p>
    </div>

    {{-- Footer with organization info --}}
    <div class="footer-org">
        <p><strong>Yayasan IDEP Selaras Alam</strong></p>
        <p>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</p>
        <p>Telp/Fax +62-361-908-2983 / +62-812 4658 5137</p>
    </div>
</body>
</html>