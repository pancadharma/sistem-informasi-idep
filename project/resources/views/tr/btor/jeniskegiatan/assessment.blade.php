<div class="assessment-details">
    @if($kegiatan->assessment)
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Assessment Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>What Was Monitored</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentyangdipantau ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Assessment Data</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentdata ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Participants Involved</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentyangterlibat ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Methodology</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentmetode ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Results/Findings</strong></td>
                            <td>{!! $kegiatan->assessment->assessmenthasil ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Challenges</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentkendala ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Issues</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentisu ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Lessons Learned</strong></td>
                            <td>{!! $kegiatan->assessment->assessmentpembelajaran ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
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
