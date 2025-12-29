<div class="pelatihan-details">
    @if($kegiatan->pelatihan)
        <div class="card border-warning">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Training Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Trainer(s)</strong></td>
                            <td>{!! $kegiatan->pelatihan->pelatihanpelatih ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Training Results</strong></td>
                            <td>{!! $kegiatan->pelatihan->pelatihanhasil ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Material Distribution</strong></td>
                            <td>
                                @if($kegiatan->pelatihan->pelatihandistribusi)
                                    <span class="badge badge-success mb-2">Yes</span>
                                    <div class="mt-2">
                                        {!! $kegiatan->pelatihan->pelatihandistribusi_ket ?? '' !!}
                                    </div>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Future Plans</strong></td>
                            <td>{!! $kegiatan->pelatihan->pelatihanrencana ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Document Upload Status</strong></td>
                            <td>
                                @if($kegiatan->pelatihan->pelatihanunggahan)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Uploaded
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-times-circle"></i> Not Uploaded
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Issues</strong></td>
                            <td>{!! $kegiatan->pelatihan->pelatihanisu ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Lessons Learned</strong></td>
                            <td>{!! $kegiatan->pelatihan->pelatihanpembelajaran ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No training data available for this activity.
        </div>
    @endif
</div>
