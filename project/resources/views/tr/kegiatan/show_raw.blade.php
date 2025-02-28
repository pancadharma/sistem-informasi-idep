
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
<hr>
Durasi : {{ $durationInDays ?? '' }}  Days
<hr>
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


<h1>Branch Locations</h1>
<div id="map" style="height: 400px"></div>


{{-- @push('scripts') --}}
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ&libraries=places,geometry,marker&callback=initMap"></script>
<script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -2.548926, lng: 118.0148634 }, // Default center - Indonesia
            zoom: 5, // Adjust zoom level as needed
        });

        // Get the lokasi data from the Blade variable
        const lokasiData = @json($kegiatan->lokasi);

        function addMarkers(lokasiData, map) {
        if (lokasiData && lokasiData.length > 0) {
            lokasiData.forEach(data => {
                console.log("Processing location:", data.lokasi, "Lat:", data.lat, "Long:", data.long); // Debugging line
                // Check if lat and long exist and are valid numbers
                if (data.lat && data.long && !isNaN(data.lat) && !isNaN(data.long)) {
                    const marker = new google.maps.Marker({
                        position: { lat: parseFloat(data.lat), lng: parseFloat(data.long) }, // Parse to float
                        map: map,
                        title: data.lokasi || 'Location', // Use data.lokasi or default
                    });

                    console.log("Marker created for:", data.lokasi); // Debugging line

                    // Add an InfoWindow (optional)
                    const infowindow = new google.maps.InfoWindow({
                        content: `
                            <b>${data.lokasi || 'Location'}</b><br>
                            Desa: ${data.desa?.nama || '-'} <br>
                            Kecamatan: ${data.desa?.kecamatan?.nama || '-'} <br>
                            Kabupaten: ${data.desa?.kecamatan?.kabupaten?.nama || '-'} <br>
                            Provinsi: ${data.desa?.kecamatan?.kabupaten?.provinsi?.nama || '-'} <br>
                            Negara: ${data.desa?.kecamatan?.kabupaten?.provinsi?.country?.nama || '-'}
                        `,
                    });

                    marker.addListener('click', () => {
                        infowindow.open({
                            anchor: marker,
                            map,
                        });
                    });
                } else {
                    console.error(`Invalid lat/long for location: ${data.lokasi}`);
                }
            });
        } else {
            console.warn("No location data available.");
        }
    }

        addMarkers(lokasiData, map);

    } // end initMap
</script>

{{-- <script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -2.548926, lng: 118.0148634 }, // Replace with your default center - Indonesia
            zoom: 5, // Adjust zoom level as needed
        });

        // Get the lokasi data from the Blade variable
        const lokasiData = @json($kegiatan->lokasi);

        function addMarkers(lokasiData, map) {
            if (lokasiData && lokasiData.length > 0) {
                lokasiData.forEach(data => {
                    // Check if lat and long exist and are valid numbers
                    if (data.lat && data.long && !isNaN(data.lat) && !isNaN(data.long)) {
                        const marker = new google.maps.Marker({
                            position: { lat: parseFloat(data.lat), lng: parseFloat(data.long) }, // Parse to float
                            map: map,
                            title: data.lokasi || 'Location', // Use data.lokasi or default
                        });

                        // Add an InfoWindow (optional)
                        const infowindow = new google.maps.InfoWindow({
                            content: `
                                <b>${data.lokasi || 'Location'}</b><br>
                                Desa: ${data.desa?.nama || '-'} <br>
                                Kecamatan: ${data.desa?.kecamatan?.nama || '-'} <br>
                                Kabupaten: ${data.desa?.kecamatan?.kabupaten?.nama || '-'} <br>
                                Provinsi: ${data.desa?.kecamatan?.kabupaten?.provinsi?.nama || '-'} <br>
                                Negara: ${data.desa?.kecamatan?.kabupaten?.provinsi?.country?.nama || '-'}
                            `,
                        });

                        marker.addListener('click', () => {
                            infowindow.open({
                                anchor: marker,
                                map,
                            });
                        });
                    } else {
                        console.error(`Invalid lat/long for location: ${data.lokasi}`);
                    }
                });
            } else {
                console.warn("No location data available.");
            }
        }

        addMarkers(lokasiData, map);

    } // end initMap
</script> --}}

{{-- @endpush --}}
