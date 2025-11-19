<div class="location-section">
    @if($kegiatan->lokasi?->count() > 0)
        <table class="table-bordered" style="font-size: 8pt; width: 100%;">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 18%;">Location Name</th>
                    <th style="width: 15%;">Village/Ward</th>
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
                                {{ number_format($lokasi->lat, 4) }},{{ number_format($lokasi->long, 4) }}
                            @else
                                -
                            @endif

                            @if ($lokasi->lat && $lokasi->long)
                                <a href="https://www.google.com/maps?q={{ $lokasi->lat }},{{ $lokasi->long }}" target="_blank">
                                    {{ ucwords(strtolower($lokasi->lokasi ?? 'Maps')) }}
                                </a>
                            @else
                                {{ $lokasi->lokasi ?? '—' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div style="margin-top: 10px; padding: 8px; background-color: #f0f0f0; border: 1px solid #ccc; font-size: 9pt;">
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
        <p><em>No location data available for this activity.</em></p>
    @endif
</div>
