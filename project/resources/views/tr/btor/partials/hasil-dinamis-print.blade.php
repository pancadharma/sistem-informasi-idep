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

    // Filter out redundant fields that are handled in the main report sections
    $fields = array_filter($fields, function($field) {
        return !str_ends_with($field, 'kendala') && 
               !str_ends_with($field, 'isu') && 
               !str_ends_with($field, 'pembelajaran');
    });
@endphp

@if($relationData)
    <div class="section-title" style="margin-top: 20px;">
        {{ strtoupper(__('btor.hasil_kegiatan')) }}: {{ strtoupper($kegiatan->jenisKegiatan?->nama) }}
    </div>
    
    <table class="table-bordered" style="width: 100%; margin-top: 5px;">
        @foreach($fields as $field)
            <tr>
                <td width="25%" style="background-color: #f2f2f2; font-weight: bold;">
                    {{ __('cruds.kegiatan.hasil.' . $field) }}
                </td>
                <td width="75%">
                    @if(in_array($field, $radioFields))
                        @if($relationData->$field == 1)
                            {{ __('global.yes') }}
                        @else
                            {{ __('global.no') }}
                        @endif
                    @elseif(str_contains($field, 'mulai') || str_contains($field, 'selesai'))
                        {{ $relationData->$field ? \Carbon\Carbon::parse($relationData->$field)->format('d M Y H:i') : '-' }}
                    @else
                        {!! $relationData->$field ?: '-' !!}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endif
