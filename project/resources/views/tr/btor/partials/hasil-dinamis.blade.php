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

<div class="form-group row">
    @foreach($fields as $field)
        <label for="sosialisasiyangterlibat" 
            class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label" 
            data-toggle="tooltip" 
            title="{{ $field }}">
            {{ __('cruds.kegiatan.hasil.' . $field) }}
            <i class="fas fa-info-circle text-success" 
            data-toggle="tooltip" 
            title="{{ $field }}"></i>
        </label>
        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
            @if(in_array($field, $radioFields))
                @if($relationData->$field == 1)
                    <span class="badge badge-success px-2 py-1">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('global.yes') }}
                    </span>
                @else
                    <span class="badge badge-danger px-2 py-1">
                        <i class="fas fa-times-circle mr-1"></i> {{ __('global.no') }}
                    </span>
                @endif
            @elseif(str_contains($field, 'mulai') || str_contains($field, 'selesai'))
                {{ $relationData->$field ? \Carbon\Carbon::parse($relationData->$field)->format('d M Y H:i') : '-' }}
            @else
                {!! $relationData->$field ?: '<em class="text-muted">-</em>' !!}
            @endif
        </div>
    @endforeach
</div>
@else
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        {{ __('btor.no_data_available_for_this_activity_type') }}
    </div>
@endif