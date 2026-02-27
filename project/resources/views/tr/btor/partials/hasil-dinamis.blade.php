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
    <h5 class="mt-4 mb-3 pl-2">c. 
        {{ __('btor.hasil_kegiatan') }}
        <span class="text-primary">
            {{ $kegiatan->jenisKegiatan?->nama }}
        </span>
    </h5>
    <div class="p-3 bg-light rounded" style="min-height: 100px;">
        @foreach($fields as $field)
            <div class="form-group row border-bottom pb-2 mb-2">
                <label class="col-sm-4 col-md-3 col-form-label font-weight-bold">
                    {{ __('cruds.kegiatan.hasil.' . $field) }}
                    <!-- <i class="fas fa-question-circle text-info ml-1" 
                        data-toggle="tooltip" 
                        title="{{ __('cruds.kegiatan.hasil.' . $field) }}"></i> -->
                </label>

                <div class="col-sm-8 col-md-9 d-flex align-items-center">
                    @if(in_array($field, $radioFields))
                        @if($relationData->$field == 1)
                            <span class="badge badge-pill badge-success px-3">
                                <i class="fas fa-check-circle mr-1"></i> {{ __('global.yes') }}
                            </span>
                        @else
                            <span class="badge badge-pill badge-secondary px-3">
                                <i class="fas fa-times-circle mr-1"></i> {{ __('global.no') }}
                            </span>
                        @endif
                    @elseif(str_contains($field, 'mulai') || str_contains($field, 'selesai'))
                        <span class="text-dark">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $relationData->$field ? \Carbon\Carbon::parse($relationData->$field)->format('d M Y H:i') : '-' }}
                        </span>
                    @else
                        <div class="text-dark">
                            {!! $relationData->$field ?: '<em class="text-muted">-</em>' !!}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        {{ __('btor.no_data_available_for_this_activity_type') }}
    </div>
@endif