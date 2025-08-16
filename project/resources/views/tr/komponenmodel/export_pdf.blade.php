<!DOCTYPE html>
<html>
<head>
    <title>Komponen Model Dashboard - PDF Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        .card {
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            width: 23%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin: 0;
            font-size: 2em;
        }
        .card p {
            margin: 5px 0 0;
            color: #555;
        }
        .charts {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        .chart-container {
            width: 48%; /* Adjust as needed */
            margin-bottom: 20px;
            text-align: center;
        }
        .chart-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .filters-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Komponen Model Dashboard</h1>
        <p>Export Date: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div class="filters-info">
        <h2>Filters Applied:</h2>
        @php $labels = $filter_labels ?? []; @endphp
        <ul>
            <li><strong>Program:</strong> {{ $labels['program'] ?? 'All' }}</li>
            <li><strong>Sektor:</strong> {{ $labels['sektor'] ?? 'All' }}</li>
            <li><strong>Model:</strong> {{ $labels['model'] ?? 'All' }}</li>
            <li><strong>Tahun:</strong> {{ $labels['tahun'] ?? 'All' }}</li>
        </ul>
    </div>

    <div class="summary-cards">
        <div class="card">
            <h3>{{ $summary['totalKomponen'] }}</h3>
            <p>Total Komponen Model</p>
        </div>
        <div class="card">
            <h3>{{ $summary['totalProgram'] }}</h3>
            <p>Total Program</p>
        </div>
        <div class="card">
            <h3>{{ $summary['totalLokasi'] }}</h3>
            <p>Total Lokasi</p>
        </div>
        <div class="card">
            <h3>{{ $summary['totalJumlah'] }}</h3>
            <p>Total Kuantitas</p>
        </div>
    </div>

    <div class="charts">
        <div class="chart-container">
            <h3>Komponen per Sektor</h3>
            <img src="{{ $charts['sektorChart'] }}" alt="Sektor Chart">
        </div>
        <div class="chart-container">
            <h3>Jumlah per Program</h3>
            <img src="{{ $charts['programChart'] }}" alt="Program Chart">
        </div>
        <div class="chart-container">
            <h3>Komponen per Model</h3>
            <img src="{{ $charts['modelChart'] }}" alt="Model Chart">
        </div>
</div>

{{-- You might want to add a table for map data here if needed --}}

@php $ag = $aggregates ?? []; @endphp
@if(!empty($ag))
    <h2>Ringkasan Tambahan</h2>
    <div style="display:flex; flex-wrap:wrap; gap:16px;">
        <div style="flex:1; min-width:280px;">
            <h3>Jumlah per Program</h3>
            <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse;">
                <thead><tr style="background:#f5f5f5"><th align="left">Program</th><th align="right">Jumlah</th><th align="right">Lokasi</th></tr></thead>
                <tbody>
                @foreach(($ag['perProgram'] ?? []) as $row)
                    <tr><td>{{ $row->program }}</td><td align="right">{{ number_format($row->total_jumlah) }}</td><td align="right">{{ number_format($row->total_lokasi) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="flex:1; min-width:280px;">
            <h3>Jumlah per Model</h3>
            <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse;">
                <thead><tr style="background:#f5f5f5"><th align="left">Model</th><th align="right">Jumlah</th><th align="right">Lokasi</th></tr></thead>
                <tbody>
                @foreach(($ag['perModel'] ?? []) as $row)
                    <tr><td>{{ $row->model }}</td><td align="right">{{ number_format($row->total_jumlah) }}</td><td align="right">{{ number_format($row->total_lokasi) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:16px; margin-top:10px;">
        <div style="flex:1; min-width:280px;">
            <h3>Jumlah per Provinsi</h3>
            <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse;">
                <thead><tr style="background:#f5f5f5"><th align="left">Provinsi</th><th align="right">Jumlah</th><th align="right">Lokasi</th></tr></thead>
                <tbody>
                @foreach(($ag['perProvinsi'] ?? []) as $row)
                    <tr><td>{{ $row->provinsi }}</td><td align="right">{{ number_format($row->total_jumlah) }}</td><td align="right">{{ number_format($row->total_lokasi) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="flex:1; min-width:280px;">
            <h3>Jumlah per Satuan</h3>
            <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse;">
                <thead><tr style="background:#f5f5f5"><th align="left">Satuan</th><th align="right">Jumlah</th><th align="right">Lokasi</th></tr></thead>
                <tbody>
                @foreach(($ag['perSatuan'] ?? []) as $row)
                    <tr><td>{{ $row->satuan }}</td><td align="right">{{ number_format($row->total_jumlah) }}</td><td align="right">{{ number_format($row->total_lokasi) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:10px;">
        <h3>Top Kabupaten (10 Teratas)</h3>
        <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse;">
            <thead><tr style="background:#f5f5f5"><th align="left">Kabupaten</th><th align="right">Jumlah</th><th align="right">Lokasi</th></tr></thead>
            <tbody>
            @foreach(($ag['topKabupaten'] ?? []) as $row)
                <tr><td>{{ $row->kabupaten ?? '-' }}</td><td align="right">{{ number_format($row->total_jumlah) }}</td><td align="right">{{ number_format($row->total_lokasi) }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

</body>
</html>
