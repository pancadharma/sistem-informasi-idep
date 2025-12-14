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
                        <tr>
                            <td class="table-secondary"><strong>Challenges</strong></td>
                            <td>{!! $kegiatan->lainnya->lainnyakendala ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Issues</strong></td>
                            <td>{!! $kegiatan->lainnya->lainnyaisu ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Lessons Learned</strong></td>
                            <td>{!! $kegiatan->lainnya->lainnyapembelajaran ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
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
