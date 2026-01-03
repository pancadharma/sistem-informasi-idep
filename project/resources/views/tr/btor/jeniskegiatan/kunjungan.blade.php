<div class="kunjungan-details">
    @if($kegiatan->kunjungan)
        <div class="card" style="border-color: #6610f2;">
            <div class="card-header text-white" style="background-color: #6610f2;">
                <h5 class="mb-0"><i class="fas fa-plane-departure"></i> Visit Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Visiting From</strong></td>
                            <td>{!! $kegiatan->kunjungan->kunjungandari ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Visiting To</strong></td>
                            <td>{!! $kegiatan->kunjungan->kunjunganke ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Purpose</strong></td>
                            <td>{!! $kegiatan->kunjungan->kunjungantujuan ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Visit Results</strong></td>
                            <td>{!! $kegiatan->kunjungan->kunjunganhasil ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Follow-up Plans</strong></td>
                            <td>
                                @if($kegiatan->kunjungan->kunjunganrencana)
                                    <span class="badge badge-success mb-2">Yes</span>
                                    <div class="mt-2">
                                        {!! $kegiatan->kunjungan->kunjunganrencana_ket ?? '' !!}
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
            No visit data available for this activity.
        </div>
    @endif
</div>
