<!DOCTYPE html>
<html>
<head>
    <title>Komponen Model Dashboard - DOCX Export</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
            display: inline-block; /* For DOCX to render side-by-side */
            margin: 5px; /* Spacing for DOCX */
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
            /* For DOCX, flexbox might not work as expected, so rely on block display */
            margin-bottom: 30px;
        }
        .chart-container {
            width: 100%; /* Full width for DOCX */
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
        <ul>
            <li><strong>Program:</strong> {{ $filters['program_id'] ?? 'All' }}</li>
            <li><strong>Sektor:</strong> {{ $filters['sektor_id'] ?? 'All' }}</li>
            <li><strong>Model:</strong> {{ $filters['model_id'] ?? 'All' }}</li>
            <li><strong>Tahun:</strong> {{ $filters['tahun'] ?? 'All' }}</li>
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

</body>
</html>
