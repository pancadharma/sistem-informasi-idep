@php
    $jenisId = $kegiatan->jeniskegiatan_id;
    $fieldMap = App\Models\Kegiatan::getJenisKegiatanFieldMap();
    $fields = $fieldMap[$jenisId] ?? [];

    $radioFields = [
        'assessmenttambahan', 'sosialisasitambahan', 'pelatihandistribusi',
        'pelatihanunggahan', 'pembelanjaanterdistribusi', 'pembelanjaanakandistribusi',
        'monitoringkegiatanselanjutnya'
    ];

    $relationName = App\Models\Kegiatan::getJenisKegiatanRelationMap()[$jenisId] ?? null;
    $relationData = $relationName ? $kegiatan->$relationName : null;
@endphp

@if($relationData)
    <div class="section">
        <h4 class="section-title">{{ $kegiatan->jenisKegiatan?->nama }}</h4>
        <table class="table-bordered">
            <tbody>
                @foreach($fields as $field)
                    <tr>
                        <td width="35%" style="background-color: #f2f2f2; font-weight: bold;">
                            {{ __('cruds.kegiatan.hasil.' . $field) }}
                        </td>
                        <td>
                            @if(in_array($field, $radioFields))
                                {{ $relationData->$field == 1 ? __('global.yes') : __('global.no') }}
                            @elseif(str_contains($field, 'mulai') || str_contains($field, 'selesai'))
                                {{ $relationData->$field ? \Carbon\Carbon::parse($relationData->$field)->format('d M Y H:i') : '-' }}
                            @else
                                {!! $relationData->$field ?: '-' !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
