# Laporan Kegiatan: {{ $kegiatan->activity?->nama ?? 'N/A' }}

## Informasi Dasar

- **Kode Program:** {{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}
- **Nama Program:** {{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}
- **Kode Kegiatan:** {{ $kegiatan->activity?->kode ?? '-' }}
- **Nama Kegiatan:** {{ $kegiatan->activity?->nama ?? '-' }}
- **Penulis Laporan:** @foreach ($kegiatan->datapenulis as $penulis){{ $penulis->nama ?? '' }}{{ !$loop->last ? ', ' : '' }}@endforeach
- **Jenis Kegiatan:** {{ $kegiatan->jenisKegiatan?->nama ?? '' }}
- **Sektor:** @foreach ($kegiatan->sektor as $key => $value){{ $value->nama ?? '' }}{{ !$loop->last ? ', ' : '' }}@endforeach
- **Tanggal Mulai:** {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d-m-Y') ?? '' }}
- **Tanggal Selesai:** {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d-m-Y') ?? '' }}
- **Durasi:** {{ $durationInDays ?? '-' }} hari
- **Status:** {{ $kegiatan->status ?? '' }}

## Deskripsi

### Latar Belakang
{!! $kegiatan->deskripsilatarbelakang ?? 'Tidak ada latar belakang.' !!}

### Tujuan
{!! $kegiatan->deskripsitujuan ?? 'Tidak ada tujuan.' !!}

### Keluaran
{!! $kegiatan->deskripsikeluaran ?? 'Tidak ada keluaran.' !!}
