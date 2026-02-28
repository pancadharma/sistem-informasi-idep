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
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-tasks mr-2"></i> {{ $kegiatan->jenisKegiatan?->nama }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <tbody>
                        @foreach($fields as $field)
                            <tr>
                                <td width="30%" class="bg-light font-weight-bold text-muted small text-uppercase">
                                    {{ __('cruds.kegiatan.hasil.' . $field) }}
                                </td>
                                <td>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        {{ __('btor.no_data_available_for_this_activity_type') }}
    </div>
@endif
