<div style="font-family: 'Times New Roman', serif; font-size: 11px;">
    <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000;">
        <h1 style="font-size: 18px; font-weight: bold; margin: 0;">BACK TO OFFICE REPORT</h1>
        <p style="font-size: 12px; margin-top: 5px;">Laporan Kegiatan</p>
    </div>

    <h3 style="font-size: 14px; font-weight: bold; margin-top: 20px; border-bottom: 1px solid #000;">Informasi Dasar</h3>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
        <tr>
            <td style="width: 30%; border: 1px solid #000; padding: 5px; background-color: #f5f5f5; font-weight: bold;">Kode Program</td>
            <td style="border: 1px solid #000; padding: 5px;">{{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px; background-color: #f5f5f5; font-weight: bold;">Nama Program</td>
            <td style="border: 1px solid #000; padding: 5px;">{{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px; background-color: #f5f5f5; font-weight: bold;">Nama Kegiatan</td>
            <td style="border: 1px solid #000; padding: 5px;">{{ $kegiatan->activity?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px; background-color: #f5f5f5; font-weight: bold;">Penulis</td>
            <td style="border: 1px solid #000; padding: 5px;">
                @foreach ($kegiatan->datapenulis as $penulis)
                    {{ $penulis->nama ?? '' }}{{ !$loop->last ? ',' : '' }}
                @endforeach
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px; background-color: #f5f5f5; font-weight: bold;">Lokasi</td>
            <td style="border: 1px solid #000; padding: 5px;">
                 @if ($kegiatan->lokasi->isNotEmpty())
                    {{ $kegiatan->lokasi->unique('kabupaten_id')->pluck('desa.kecamatan.kabupaten.nama')->implode(', ') }}
                @endif
            </td>
        </tr>
    </table>

    <h3 style="font-size: 14px; font-weight: bold; margin-top: 20px; border-bottom: 1px solid #000;">Deskripsi</h3>
    
    <p style="font-weight: bold; margin-bottom: 5px;">Latar Belakang</p>
    <div>{!! $kegiatan->deskripsilatarbelakang ?? '-' !!}</div>

    <p style="font-weight: bold; margin-top: 10px; margin-bottom: 5px;">Tujuan</p>
    <div>{!! $kegiatan->deskripsitujuan ?? '-' !!}</div>

    <p style="font-weight: bold; margin-top: 10px; margin-bottom: 5px;">Keluaran</p>
    <div>{!! $kegiatan->deskripsikeluaran ?? '-' !!}</div>

    <h3 style="font-size: 14px; font-weight: bold; margin-top: 20px; border-bottom: 1px solid #000;">Detail Kegiatan</h3>
    @if ($kegiatan->jeniskegiatan_id == 1 && $kegiatan->assessment)
        @include('tr.kegiatan.export_assessment')
    @elseif ($kegiatan->jeniskegiatan_id == 3 && $kegiatan->pelatihan)
        @include('tr.kegiatan.export_pelatihan')
    @else
        <p>Detail untuk jenis kegiatan ini tersedia di web.</p>
    @endif

    <h3 style="font-size: 14px; font-weight: bold; margin-top: 20px; border-bottom: 1px solid #000;">Tantangan & Solusi</h3>
    @php
        $kendala = $kegiatan->assessment?->assessmentkendala ?? $kegiatan->pelatihan?->pelatihanisu ?? null;
        $solusi = $kegiatan->assessment?->assessmentpembelajaran ?? $kegiatan->pelatihan?->pelatihanpembelajaran ?? null;
    @endphp
    @if($kendala || $solusi)
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
        <tr>
            <th style="border: 1px solid #000; padding: 5px; background-color: #f5f5f5;">Tantangan</th>
            <th style="border: 1px solid #000; padding: 5px; background-color: #f5f5f5;">Solusi</th>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;">{!! $kendala ?? '-' !!}</td>
            <td style="border: 1px solid #000; padding: 5px;">{!! $solusi ?? '-' !!}</td>
        </tr>
    </table>
    @endif
</div>