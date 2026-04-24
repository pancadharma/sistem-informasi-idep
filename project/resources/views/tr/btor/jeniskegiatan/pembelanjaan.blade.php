<div class="pembelanjaan-details">
    @if($kegiatan->pembelanjaan)
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Procurement Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="table-secondary"><strong>Item Details</strong></td>
                            <td>{!! $kegiatan->pembelanjaan->pembelanjaandetailbarang ?? '<em class="text-muted">No data</em>' !!}</td>
                        </tr>
                        <tr>
                            <td class="table-secondary"><strong>Procurement Period</strong></td>
                            <td>
                                @if($kegiatan->pembelanjaan->pembelanjaanmulai && $kegiatan->pembelanjaan->pembelanjaanselesai)
                                    <strong>Start:</strong> {{ \Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaanmulai)->format('d F Y') }}
                                    <br>
                                    <strong>End:</strong> {{ \Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaanselesai)->format('d F Y') }}
                                    <br>
                                    <span class="badge badge-info">
                                        {{ \Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaanmulai)->diffInDays(\Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaanselesai)) }} days
                                    </span>
                                @else
                                    <em class="text-muted">No data</em>
                                @endif
                            </td>
                        </tr>
                        {{-- <tr>
                            <td class="table-secondary"><strong>Distribution Period</strong></td>
                            <td>
                                @if($kegiatan->pembelanjaan->pembelanjaandistribusimulai && $kegiatan->pembelanjaan->pembelanjaandistribusiselesai)
                                    <strong>Start:</strong> {{ \Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaandistribusimulai)->format('d F Y') }}
                                    <br>
                                    <strong>End:</strong> {{ \Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaandistribusiselesai)->format('d F Y') }}
                                    <br>
                                    <span class="badge badge-info">
                                        {{ \Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaandistribusimulai)->diffInDays(\Carbon\Carbon::parse($kegiatan->pembelanjaan->pembelanjaandistribusiselesai)) }} days
                                    </span>
                                @else
                                    <em class="text-muted">No data</em>
                                @endif
                            </td>
                        </tr> --}}
                        <tr>
                            <td class="table-secondary"><strong>Distribution Status</strong></td>
                            <td>
                                @if($kegiatan->pembelanjaan->pembelanjaanterdistribusi)
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-check-circle"></i> Distributed
                                    </span>
                                @else
                                    <span class="badge badge-warning badge-lg">
                                        <i class="fas fa-clock"></i> Not Yet Distributed
                                    </span>
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
            No procurement data available for this activity.
        </div>
    @endif
</div>
