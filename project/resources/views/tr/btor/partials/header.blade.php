<div class="report-info mb-4">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%"><strong>Report ID</strong></td>
                    <td>: {{ $kegiatan->id }}</td>
                </tr>
                <tr>
                    <td><strong>Report Date</strong></td>
                    <td>: {{ now()->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Fiscal Year</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('Y') }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%"><strong>Organization</strong></td>
                    <td>: IDEP Foundation</td>
                </tr>
                <tr>
                    <td><strong>Department</strong></td>
                    <td>: Programs</td>
                </tr>
                <tr>
                    <td><strong>Report Phase</strong></td>
                    <td>: {{ $kegiatan->fasepelaporan ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
