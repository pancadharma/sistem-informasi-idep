<div class="assessment-details">
    @if($kegiatan->assessment)
        <div class="card border-primary">
            {{-- <div class="card-header bg-primary text-white"> --}}
                <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> {{ __('btor.hasil.assessmentdata') }}</h5>
            {{-- </div> --}}
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="20%" class="table-secondary"><strong>{{ __('btor.hasil.assessmentyangterlibat') }}</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentyangterlibat ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td width="20%" class="table-secondary"><strong>{{ __('btor.hasil.assessmenttemuan') }}</strong></td>
                            <td>{!! $kegiatan->assessment->assessmenttemuan ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td width="20%" class="table-secondary"><strong>{{ __('btor.hasil.assessmenttambahan') }}</strong></td>
                            <td>
                                @if($kegiatan->assessment->assessmenttambahan == 1)
                                    <span class="badge badge-success">{{ __('global.yes') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('global.no') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td width="20%" class="table-secondary"><strong>{{ __('btor.hasil.assessmenttambahan_ket') }}</strong></td>
                            <td>{!! $kegiatan->assessment->assessmenttambahan_ket ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        {{-- REVIEW: Redundant fields (kendala, isu, pembelajaran) removed to avoid duplication with summary sections --}}
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No assessment data available for this activity.
        </div>
    @endif
</div>
