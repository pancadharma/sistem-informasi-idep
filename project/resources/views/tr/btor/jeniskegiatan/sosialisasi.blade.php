<div class="sosialisasi-details">
    @if($kegiatan->sosialisasi)
        <div class="card border-success">
            <div class="card-header bg-success text-white">
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
                        <tr>
                            <td class="table-secondary"><strong>Challenges</strong></td>
                            <td>{!! $kegiatan->sosialisasi->sosialisasikendala ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Issues</strong></td>
                            <td>{!! $kegiatan->sosialisasi->sosialisasiisu ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Lessons Learned</strong></td>
                            <td>{!! $kegiatan->sosialisasi->sosialisasipembelajaran ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
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
