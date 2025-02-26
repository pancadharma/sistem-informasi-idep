
<h1>{{ $kegiatan->activity->nama ?? '-' }}</h1>
<hr>
Kode Kegiatan : {{ $kegiatan->activity->kode }}
<hr>
Fase Pelaporan : {{ $kegiatan->fasepelaporan }}
<hr>
Jenis Kegiatan : {{ $kegiatan->jenisKegiatan->nama ?? '' }}
<hr>
Sektor Kegiatan :
@foreach($kegiatan->sektor as $key => $value)
    {{ $value->nama.','  }}
@endforeach
<hr>
Tgl Mulai : {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d-m-Y') }}
<hr>
Tgl Selesai : {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d-m-Y') }}
<hr>
Created on : {{ $kegiatan->created_at->diffForHumans() }}
<hr>
Created by : {{ $kegiatan->user->nama ?? '' }}
<hr>
Status : {{ $kegiatan->status   }}
<hr>
<hr>

<h1>
    Penulis
</h1>

@foreach($kegiatan->kegiatan_penulis as $penulis)
    <li>{{ $penulis->nama }} - {{ $penulis->kegiatanPeran->nama }}</li>
@endforeach

<hr>
<hr>

<h1>Mitra</h1>
@foreach($kegiatan->mitra as $partner)
<li>{{ $partner->nama }}</li>
@endforeach


<hr>
<hr>
<h1> Deskripsi </h1>

   {!! $kegiatan->deskripsilatarbelakang ?? '-' !!}
    <hr>

    {!! $kegiatan->deskripsitujuan ?? '-' !!}
    <hr>
    {!! $kegiatan->deskripsikeluaran ?? '-' !!}

<hr>
<hr>

<h1>
    Peserta Yang Terlibat
</h1>
{{ $kegiatan->penerimamanfaattotal }}

<hr>
<h1>
    Lokasi
</h1>
    @foreach($kegiatan->lokasi as $data)
        <li>
            {!! $data->desa->nama ?? '-' !!}
        </li>
        <li>
            {!! $data->desa->kecamatan->nama ?? '-'!!}
        </li>
        <li>
            {!! $data->desa->kecamatan->kabupaten->nama ?? '-' !!}
        </li>
        <li>
            {!! $data->desa->kecamatan->kabupaten->provinsi->nama ?? '-' !!}
        </li>
        <li>
            {!! $data->desa->kecamatan->kabupaten->provinsi->country->nama ?? '-' !!}
        </li>
        <hr>
        <hr>

        <li>
            {{ $data->lat }},
            {{ $data->long }},
            {{ $data->lokasi }},
            {{ $data->desa->nama }},
            {{ $data->desa->kecamatan->nama }},
            {{ $data->desa->kecamatan->kabupaten->nama }},
            {{ $data->desa->kecamatan->kabupaten->provinsi->nama }},
            {{ $data->desa->kecamatan->kabupaten->provinsi->country->nama }}
         </li>
    @endforeach
<hr>
<hr>
