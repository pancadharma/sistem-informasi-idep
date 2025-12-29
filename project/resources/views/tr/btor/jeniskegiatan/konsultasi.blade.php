<div class="konsultasi-details">
    @if($kegiatan->konsultasi)
        <div class="card" style="border-color: #e83e8c;">
            <div class="card-header text-white" style="background-color: #e83e8c;">
                <h5 class="mb-0"><i class="fas fa-comments"></i> Consultation Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Consultant</strong></td>
                            <td>{!! $kegiatan->konsultasi->konsultasikonsultan ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Subject Matter</strong></td>
                            <td>{!! $kegiatan->konsultasi->konsultasihal ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Consultation Results</strong></td>
                            <td>{!! $kegiatan->konsultasi->konsultasihasil ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Follow-up Plans</strong></td>
                            <td>
                                @if($kegiatan->konsultasi->konsultasirencana)
                                    <span class="badge badge-success mb-2">Yes</span>
                                    <div class="mt-2">
                                        {!! $kegiatan->konsultasi->konsultasirencana_ket ?? '' !!}
                                    </div>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Challenges</strong></td>
                            <td>{!! $kegiatan->konsultasi->konsultasikendala ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Issues</strong></td>
                            <td>{!! $kegiatan->konsultasi->konsultasiisu ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Lessons Learned</strong></td>
                            <td>{!! $kegiatan->konsultasi->konsultasipembelajaran ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No consultation data available for this activity.
        </div>
    @endif
</div>
