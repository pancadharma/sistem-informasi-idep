<div class="location-section">
    @if($kegiatan->lokasi?->count() > 0)
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="20%">Location Name</th>
                    <th width="15%">Village/Ward</th>
                    <th width="15%">Sub-District</th>
                    <th width="15%">District</th>
                    <th width="15%">Province</th>
                    <th width="15%">Coordinates</th>
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
                        <td>
                            @if($lokasi->lat && $lokasi->long)
                                <a href="https://www.google.com/maps?q={{ $lokasi->lat }},{{ $lokasi->long }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-map-marker-alt"></i> Map
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ number_format($lokasi->lat, 6) }}, {{ number_format($lokasi->long, 6) }}
                                </small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="alert alert-secondary mt-3">
            <i class="fas fa-map-marked-alt"></i>
            <strong>Location Summary:</strong>
            @php
                $provinces = $kegiatan->lokasi
                    ->pluck('desa.kecamatan.kabupaten.provinsi.nama')
                    ->filter()
                    ->unique()
                    ->values();

                $districts = $kegiatan->lokasi
                    ->pluck('desa.kecamatan.kabupaten.nama')
                    ->filter()
                    ->unique()
                    ->values();
            @endphp

            {{ $kegiatan->lokasi->count() }} location(s) in
            {{ $districts->count() }} district(s) across
            {{ $provinces->count() }} province(s)

            @if($provinces->count() > 0)
                <br>
                <small>Provinces: {{ $provinces->implode(', ') }}</small>
            @endif
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No location data available for this activity.
        </div>
    @endif
</div>
