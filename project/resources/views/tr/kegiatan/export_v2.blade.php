# Laporan Kegiatan: {{ $kegiatan->activity?->nama ?? 'N/A' }}

## Informasi Dasar

- **Kode Program:** {{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}
- **Nama Program:** {{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}
- **Kode Kegiatan:** {{ $kegiatan->activity?->kode ?? '-' }}
- **Nama Kegiatan:** {{ $kegiatan->activity?->nama ?? '-' }}
- **Penulis Laporan:** @foreach ($kegiatan->datapenulis as $penulis){{ $penulis->nama ?? '' }}{{ !$loop->last ? ', ' : '' }}@endforeach
- **Jenis Kegiatan:** {{ $kegiatan->jenisKegiatan?->nama ?? '' }}
- **Sektor:** @foreach ($kegiatan->sektor as $key => $value){{ $value->nama ?? '' }}{{ !$loop->last ? ', ' : '' }}@endforeach
- **Fase Pelaporan:** {{ $kegiatan->fasepelaporan ?? '' }}
- **Tanggal Mulai:** {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d-m-Y') ?? '' }}
- **Tanggal Selesai:** {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d-m-Y') ?? '' }}
- **Durasi:** {{ $durationInDays ?? '-' }} hari
- **Status:** {{ $kegiatan->status ?? '' }}
- **Mitra:** @foreach ($kegiatan->mitra as $partner) {{ $partner->nama ?? '' }}{{ !$loop->last ? ', ' : '' }} @endforeach
- **Lokasi Summary:** @if ($kegiatan->lokasi->isNotEmpty()) {{ $kegiatan->lokasi->unique('kabupaten_id')->pluck('desa.kecamatan.kabupaten.nama')->implode(', ') }} @endif

## Hierarki Program

- **Program Outcome:** {{ $kegiatan->activity?->program_outcome_output?->program_outcome?->nama ?? '-' }}
- **Program Output:** {{ $kegiatan->activity?->program_outcome_output?->nama ?? '-' }}
- **Target Kegiatan:** @if($kegiatan->activity?->target_reinstra) {{ $kegiatan->activity?->target_reinstra?->target_value ?? 0 }} {{ $kegiatan->activity?->target_reinstra?->satuan?->nama ?? '' }} @else Tidak ada target @endif

## Detail Lokasi

| Nama Tempat | Longitude | Latitude |
| :--- | :--- | :--- |
@forelse ($kegiatan->lokasi as $lokasi)
| {{ $lokasi->lokasi ?? '-' }} | {{ $lokasi->long ?? '-' }} | {{ $lokasi->lat ?? '-' }} |
@empty
| Tidak ada data lokasi tersedia | - | - |
@endforelse

## Ringkasan Peserta

| Peserta | Wanita | Pria | Total |
| :--- | :---: | :---: | :---: |
| Dewasa | {{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0 }} | {{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0 }} | {{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }} |
| Lansia | {{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0 }} | {{ $kegiatan->penerimamanfaatlansialakilaki ?? 0 }} | {{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }} |
| Remaja | {{ $kegiatan->penerimamanfaatremajaperempuan ?? 0 }} | {{ $kegiatan->penerimamanfaatremajalakilaki ?? 0 }} | {{ $kegiatan->penerimamanfaatremajatotal ?? 0 }} |
| Anak-anak | {{ $kegiatan->penerimamanfaatanakperempuan ?? 0 }} | {{ $kegiatan->penerimamanfaatanaklakilaki ?? 0 }} | {{ $kegiatan->penerimamanfaatanaktotal ?? 0 }} |
| **Total** | **{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}** | **{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}** | **{{ $kegiatan->penerimamanfaattotal ?? 0 }}** |

## Ringkasan Disabilitas

| Peserta | Wanita | Pria | Total |
| :--- | :---: | :---: | :---: |
| Disabilitas | {{ $kegiatan->penerimamanfaatdisabilitasperempuan ?? 0 }} | {{ $kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0 }} | {{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }} |
| Non-Disabilitas | {{ $kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0 }} | {{ $kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0 }} | {{ $kegiatan->penerimamanfaatnondisabilitastotal ?? 0 }} |

## Deskripsi

### Latar Belakang
{!! $kegiatan->deskripsilatarbelakang ?? 'Tidak ada latar belakang.' !!}

### Tujuan
{!! $kegiatan->deskripsitujuan ?? 'Tidak ada tujuan.' !!}

### Keluaran
{!! $kegiatan->deskripsikeluaran ?? 'Tidak ada keluaran.' !!}

### Yang Dikaji
{!! $kegiatan->deskripsiyangdikaji ?? 'Tidak ada data.' !!}

## Detail Kegiatan Spesifik

@if ($kegiatan->jeniskegiatan_id == 1 && $kegiatan->assessment)
(Detail Assessment tersedia di versi web/DOCX)
@elseif ($kegiatan->jeniskegiatan_id == 2 && $kegiatan->sosialisasi)
(Detail Sosialisasi tersedia di versi web/DOCX)
@elseif ($kegiatan->jeniskegiatan_id == 3 && $kegiatan->pelatihan)
(Detail Pelatihan tersedia di versi web/DOCX)
@else
(Detail kegiatan spesifik ini mungkin lebih baik dibaca pada versi DOCX yang mendukung tabel kompleks)
@endif

## Tantangan dan Solusi

@php
    $kendala = $kegiatan->assessment?->assessmentkendala ?? $kegiatan->pelatihan?->pelatihanisu ?? $kegiatan->monitoring?->monitoringkendala ?? null;
    $solusi = $kegiatan->assessment?->assessmentpembelajaran ?? $kegiatan->pelatihan?->pelatihanpembelajaran ?? $kegiatan->monitoring?->monitoringpembelajaran ?? null;
@endphp

@if($kendala || $solusi)
**Tantangan:**
{!! $kendala ?? 'Tidak ada data' !!}

**Solusi yang Diambil Tim:**
{!! $solusi ?? 'Tidak ada data' !!}
@else
Tidak ada data tantangan dan solusi yang tersedia.
@endif

## Isu yang Perlu Diperhatikan & Rekomendasi

@php
    $isu = $kegiatan->assessment?->assessmentisu ?? $kegiatan->pelatihan?->pelatihanisu ?? $kegiatan->monitoring?->monitoringisu ?? null;
@endphp

@if($isu)
**Isu yang Perlu Diperhatikan:**
{!! $isu !!}

**Rekomendasi:**
{!! $kegiatan->assessment?->assessmentpembelajaran ?? $kegiatan->pelatihan?->pelatihanpembelajaran ?? '-' !!}
@else
Tidak ada isu yang perlu diperhatikan.
@endif

## Pembelajaran

@php
    $pembelajaran = $kegiatan->assessment?->assessmentpembelajaran
        ?? $kegiatan->pelatihan?->pelatihanpembelajaran
        ?? $kegiatan->monitoring?->monitoringpembelajaran
        ?? $kegiatan->sosialisasi?->sosialisasipembelajaran
        ?? $kegiatan->kampanye?->kampanyepembelajaran
        ?? $kegiatan->konsultasi?->konsultasipembelajaran
        ?? $kegiatan->kunjungan?->kunjunganpembelajaran
        ?? $kegiatan->pembelanjaan?->pembelanjaanpembelajaran
        ?? $kegiatan->pengembangan?->pengembanganpembelajaran
        ?? $kegiatan->pemetaan?->pemetaanpembelajaran
        ?? $kegiatan->lainnya?->lainnyapembelajaran;
@endphp

{!! $pembelajaran ?? 'Tidak ada data pembelajaran yang tersedia.' !!}

## Dokumen Pendukung

@if($kegiatan->getMedia('dokumen_pendukung')->count() > 0)
@foreach($kegiatan->getMedia('dokumen_pendukung') as $media)
- [{{ $media->name }}]({{ $media->getUrl() }}) ({{ $media->human_readable_size }})
@endforeach
@else
Tidak ada dokumen pendukung.
@endif

## Media Pendukung

@if($kegiatan->getMedia('media_pendukung')->count() > 0)
@foreach($kegiatan->getMedia('media_pendukung') as $media)
- [{{ $media->name }}]({{ $media->getUrl() }}) ({{ $media->human_readable_size }})
@endforeach
@else
Tidak ada media pendukung.
@endif

## Tanda Tangan

**Disusun oleh:** @if($kegiatan->datapenulis->first()) {{ $kegiatan->datapenulis->first()->nama }} ({{ $kegiatan->datapenulis->first()->kegiatanPeran?->nama ?? 'Staff' }}) @else Penulis Laporan @endif
**Tanggal:** {{ now()->locale('id')->isoFormat('D MMMM Y') }}

**Disetujui oleh:** @if($kegiatan->user) {{ $kegiatan->user->name }} (Program Coordinator) @else Supervisor @endif
**Tanggal:** _________________

