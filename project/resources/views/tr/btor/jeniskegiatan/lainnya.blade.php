<div class="lainnya-details">
    @if($kegiatan->lainnya)
        <div class="card border-secondary">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-ellipsis-h"></i> Other Activity Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Description</strong></td>
                            <td>{!! $kegiatan->lainnya->lainnyadeskrip ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        {{-- REVIEW: Redundant fields (kendala, isu, pembelajaran) removed to avoid duplication with summary sections --}}
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No other activity data available.
        </div>
    @endif
</div>
