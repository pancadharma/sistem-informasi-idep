<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Export</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #212529; }
        .header { display:flex; align-items:center; justify-content:space-between; padding: 10px 12px; border-bottom: 3px solid #007bff; margin-bottom: 10px; }
        .brand { font-size: 18px; font-weight: 700; color: #007bff; }
        .subtitle { color:#6c757d; font-size: 12px; }
        .footer { margin-top: 14px; padding-top: 8px; border-top: 1px solid #e9ecef; color: #6c757d; font-size: 11px; text-align: right; }
        .cards { display: flex; flex-wrap: wrap; gap: 8px; margin: 10px 0 12px; }
        .card { border-radius: .25rem; color: #fff; padding: 10px; width: 180px; }
        .bg-primary { background-color: #007bff; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #212529; }
        .bg-danger { background-color: #dc3545; }
        .bg-info { background-color: #17a2b8; }
        .bg-secondary { background-color: #6c757d; }
        .section-title { font-weight: 600; margin: 16px 0 8px; color:#343a40; }
        .img-block { margin-bottom: 12px; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dee2e6; padding: 6px; }
        thead th { background: #f8f9fa; text-align: left; }
        tbody tr:nth-child(odd) { background: #fcfcfc; }
    </style>
    <!-- Dompdf supports data: URLs for <img>, so base64 images will render. -->
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">IDEP Dashboard</div>
            <div class="subtitle">Dashboard Export</div>
        </div>
        <div class="subtitle">Generated at: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    @php $labels = $filter_labels ?? []; @endphp
    <div>
        Filters:
        @if(!empty($labels['program'])) Program: {{ $labels['program'] }} @endif
        @if(!empty($labels['tahun'])) | Tahun: {{ $labels['tahun'] }} @endif
        @if(!empty($labels['provinsi'])) | Provinsi: {{ $labels['provinsi'] }} @endif
    </div>

    <div class="cards">
        <div class="card bg-primary"><div>Total {{ __('cruds.beneficiary.title') }}</div><div style="font-size: 20px"><strong>{{ $cards['semua'] }}</strong></div></div>
        <div class="card bg-success"><div>{{ __('cruds.beneficiary.penerima.laki') }}</div><div style="font-size: 20px"><strong>{{ $cards['laki'] }}</strong></div></div>
        <div class="card bg-warning"><div>{{ __('cruds.beneficiary.penerima.perempuan') }}</div><div style="font-size: 20px"><strong>{{ $cards['perempuan'] }}</strong></div></div>
        <div class="card bg-info"><div>{{ __('cruds.beneficiary.penerima.boy') }}</div><div style="font-size: 20px"><strong>{{ $cards['anak_laki'] }}</strong></div></div>
        <div class="card bg-info"><div>{{ __('cruds.beneficiary.penerima.girl') }}</div><div style="font-size: 20px"><strong>{{ $cards['anak_perempuan'] }}</strong></div></div>
        <div class="card bg-danger"><div>{{ __('cruds.beneficiary.penerima.disability') }}</div><div style="font-size: 20px"><strong>{{ $cards['disabilitas'] }}</strong></div></div>
        <div class="card bg-secondary"><div>{{ __('cruds.beneficiary.penerima.keluarga') }}</div><div style="font-size: 20px"><strong>{{ $cards['keluarga'] }}</strong></div></div>
    </div>

    @if(!empty($snapshots['barChart']))
        <div class="section-title">Bar Chart</div>
        <div class="img-block">
            <img src="{{ $snapshots['barChart'] }}" alt="Bar Chart" style="max-width: 100%;">
        </div>
    @endif

    @if(!empty($snapshots['pieChart']))
        <div class="section-title">Pie Chart</div>
        <div class="img-block">
            <img src="{{ $snapshots['pieChart'] }}" alt="Pie Chart" style="max-width: 100%;">
        </div>
    @endif

    @if(!empty($snapshots['kabupatenPie']))
        <div class="section-title">Kabupaten Pie Chart</div>
        <div class="img-block">
            <img src="{{ $snapshots['kabupatenPie'] }}" alt="Kabupaten Pie Chart" style="max-width: 100%;">
        </div>
    @endif

    @if(!empty($snapshots['map']))
        <div class="section-title"></div>
        <div class="img-block">
            <img src="{{ $snapshots['map'] }}" alt="Map" style="max-width: 100%;">
        </div>
    @endif
    @if(empty($snapshots['map']) && !empty($snapshots['map_static_url']))
        <div class="section-title"></div>
        <div class="img-block">
            <img src="{{ $snapshots['map_static_url'] }}" alt="Map" style="max-width: 100%;">
        </div>
    @endif

    <div class="section-title">Table Desa Penerima Manfaat</div>
    @php
        $totals = [
            'total_penerima' => 0,
            'laki' => 0,
            'perempuan' => 0,
            'anak_laki' => 0,
            'anak_perempuan' => 0,
            'disabilitas' => 0,
        ];
        foreach ($tableData as $row) {
            foreach ($totals as $k => $v) { $totals[$k] += (int)($row[$k] ?? 0); }
        }
    @endphp
    <table>
        <thead>
            <tr>
                <th>Dusun</th>
                <th>Desa</th>
                <th>Kecamatan</th>
                <th>Kabupaten</th>
                <th>Provinsi</th>
                {{-- <th>Program</th> --}}
                <th>Total</th>
                <th>Laki</th>
                <th>Perempuan</th>
                <th>Anak L</th>
                <th>Anak P</th>
                <th>Disabilitas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tableData as $row)
                <tr>
                    <td>{{ $row['nama_dusun'] }}</td>
                    <td>{{ $row['desa'] }}</td>
                    <td>{{ $row['kecamatan'] }}</td>
                    <td>{{ $row['kabupaten'] }}</td>
                    <td>{{ $row['provinsi'] }}</td>
                    {{-- <td>{{ $row['program'] }}</td> --}}
                    <td>{{ $row['total_penerima'] }}</td>
                    <td>{{ $row['laki'] }}</td>
                    <td>{{ $row['perempuan'] }}</td>
                    <td>{{ $row['anak_laki'] }}</td>
                    <td>{{ $row['anak_perempuan'] }}</td>
                    <td>{{ $row['disabilitas'] }}</td>
                </tr>
            @empty
                <tr><td colspan="12">No data</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total</th>
                <th>{{ $totals['total_penerima'] }}</th>
                <th>{{ $totals['laki'] }}</th>
                <th>{{ $totals['perempuan'] }}</th>
                <th>{{ $totals['anak_laki'] }}</th>
                <th>{{ $totals['anak_perempuan'] }}</th>
                <th>{{ $totals['disabilitas'] }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">© {{ date('Y') }} IDEP Foundation</div>
</body>
</html>
