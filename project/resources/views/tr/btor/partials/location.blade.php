{{-- resources/views/tr/btor/partials/location.blade.php --}}
<div class="location-section">
    @if($kegiatan->lokasi?->count() > 0)
        {{-- Use table-bordered to inherit the green header style from print.blade.php --}}
        <table class="table-bordered" style="font-size: 9pt; width: 100%; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 20%;">{{ __('btor.lokasi') }}</th>
                    <th style="width: 15%;">{{ __('btor.desa') }}</th>
                    <th style="width: 15%;">{{ __('btor.kecamatan') }}</th>
                    <th style="width: 15%;">{{ __('btor.kabupaten') }}</th>
                    <th style="width: 15%;">{{ __('btor.provinsi') }}</th>
                    <th style="width: 15%;" class="text-center">{{ __('btor.koordinat') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatan->lokasi as $index => $lokasi)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{-- Google Maps Link Logic --}}
                            @if ($lokasi->lat && $lokasi->long)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $lokasi->lat }},{{ $lokasi->long }}" target="_blank" style="text-decoration: none; color: inherit;">
                                    {{ $lokasi->lokasi ?? 'Lokasi ' . ($index + 1) }} 
                                    <span class="no-print" style="font-size: 0.8em; color: #007bff;"><i class="fas fa-map-marker-alt"></i></span>
                                </a>
                            @else
                                {{ $lokasi->lokasi ?? '-' }}
                            @endif
                        </td>
                        <td>{{ $lokasi->desa?->nama ?? '-' }}</td>
                        <td>{{ $lokasi->desa?->kecamatan?->nama ?? '-' }}</td>
                        <td>{{ $lokasi->desa?->kecamatan?->kabupaten?->nama ?? '-' }}</td>
                        <td>{{ $lokasi->desa?->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}</td>
                        <td class="text-center">
                            @if($lokasi->lat && $lokasi->long)
                                <span style="font-size: 8pt;">
                                    {{ number_format($lokasi->lat, 6) }},<br>
                                    {{ number_format($lokasi->long, 6) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Optional Summary (Hidden in formal print if desired, currently visible) --}}
        {{-- <div class="no-print" style="margin-top: 10px; padding: 8px; background-color: #f0f0f0; border: 1px solid #ccc; font-size: 9pt;">
             ... summary logic ...
        </div> --}}
    @else
        <p><em>Tidak ada data lokasi.</em></p>
    @endif
</div>