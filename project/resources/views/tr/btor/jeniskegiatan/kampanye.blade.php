<div class="kampanye-details">
    @if($kegiatan->kampanye)
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-flag"></i> Campaign Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>What Was Monitored</strong></td>
                            <td>{!! $kegiatan->kampanye->kampanyeyangdipantau ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Campaign Data</strong></td>
                            <td>{!! $kegiatan->kampanye->kampanyedata ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Participants Involved</strong></td>
                            <td>{!! $kegiatan->kampanye->kampanyeyangterlibat ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Campaign Results</strong></td>
                            <td>{!! $kegiatan->kampanye->kampanyehasil ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        {{-- REVIEW: Redundant fields (kendala, isu, pembelajaran) removed to avoid duplication with summary sections --}}
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No campaign data available for this activity.
        </div>
    @endif
</div>
