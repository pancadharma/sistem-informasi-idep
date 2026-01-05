<div class="sosialisasi-details">
    @if($kegiatan->sosialisasi)
        <div class="card card-primary card-outline">
            <div class="card-header bg-primary">
                <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Socialization Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Participants Involved</strong></td>
                            <td>{!! $kegiatan->sosialisasi->sosialisasiyangterlibat ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Findings</strong></td>
                            <td>{!! $kegiatan->sosialisasi->sosialisasitemuan ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Additional Socialization</strong></td>
                            <td>
                                @if($kegiatan->sosialisasi->sosialisasitambahan)
                                    <span class="badge badge-success mb-2">Yes</span>
                                    <div class="mt-2">
                                        {!! $kegiatan->sosialisasi->sosialisasitambahan_ket ?? '' !!}
                                    </div>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        {{-- REVIEW: Redundant fields (kendala, isu, pembelajaran) removed to avoid duplication with summary sections --}}
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No socialization data available for this activity.
        </div>
    @endif
</div>
