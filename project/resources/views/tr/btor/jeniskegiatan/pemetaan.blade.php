<div class="pemetaan-details">
    @if($kegiatan->pemetaan)
        <div class="card border-dark">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-map"></i> Mapping Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Output/Product</strong></td>
                            <td>{!! $kegiatan->pemetaan->pemetaanyangdihasilkan ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Area Coverage</strong></td>
                            <td>
                                @if($kegiatan->pemetaan->pemetaanluasan && $kegiatan->pemetaan->pemetaanunit)
                                    <strong>{{ $kegiatan->pemetaan->pemetaanluasan }}</strong> {{ $kegiatan->pemetaan->pemetaanunit }}
                                @else
                                    <em class="text-muted">No data</em>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Participants Involved</strong></td>
                            <td>{!! $kegiatan->pemetaan->pemetaanyangterlibat ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Future Plans</strong></td>
                            <td>{!! $kegiatan->pemetaan->pemetaanrencana ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Issues</strong></td>
                            <td>{!! $kegiatan->pemetaan->pemetaanisu ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Lessons Learned</strong></td>
                            <td>{!! $kegiatan->pemetaan->pemetaanpembelajaran ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No mapping data available for this activity.
        </div>
    @endif
</div>
