<div class="pengembangan-details">
    @if($kegiatan->pengembangan)
        <div class="card border-secondary">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-tools"></i> Development Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Component Type</strong></td>
                            <td>{!! $kegiatan->pengembangan->pengembanganjeniskomponen ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Number of Components</strong></td>
                            <td>{!! $kegiatan->pengembangan->pengembanganberapakomponen ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Component Location</strong></td>
                            <td>{!! $kegiatan->pengembangan->pengembanganlokasikomponen ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Participants Involved</strong></td>
                            <td>{!! $kegiatan->pengembangan->pengembanganyangterlibat ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Future Plans</strong></td>
                            <td>{!! $kegiatan->pengembangan->pengembanganrencana ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        {{-- REVIEW: Redundant fields (kendala, isu, pembelajaran) removed to avoid duplication with summary sections --}}
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No development data available for this activity.
        </div>
    @endif
</div>
