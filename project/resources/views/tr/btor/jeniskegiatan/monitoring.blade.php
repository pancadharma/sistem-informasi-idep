<div class="monitoring-details">
    @if($kegiatan->monitoring)
        <div class="card" style="border-color: #17a2b8;">
            <div class="card-header text-white" style="background-color: #17a2b8;">
                <h5 class="mb-0"><i class="fas fa-eye"></i> Monitoring Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>What Was Monitored</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringyangdipantau ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Monitoring Data</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringdata ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Participants Involved</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringyangterlibat ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Methodology</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringmetode ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Results/Findings</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringhasil ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Follow-up Activities</strong></td>
                            <td>
                                @if($kegiatan->monitoring->monitoringkegiatanselanjutnya)
                                    <span class="badge badge-success mb-2">Yes</span>
                                    <div class="mt-2">
                                        {!! $kegiatan->monitoring->monitoringkegiatanselanjutnyaket ?? '' !!}
                                    </div>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Challenges</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringkendala ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Issues</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringisu ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Lessons Learned</strong></td>
                            <td>{!! $kegiatan->monitoring->monitoringpembelajaran ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No monitoring data available for this activity.
        </div>
    @endif
</div>
