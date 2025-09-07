@extends ('layouts.app')

@section('subtitle', __('Sistem Laporan Kegiatan IDEP-LTE'))
@section('content_header_title')
    {{-- @can('export_report') --}}
    <a class="btn-success btn" href="#" id="exportReportBtn" title="Export Report">
        <i class="fas fa-file-export"></i> Export Report
    </a>
    {{-- @endcan --}}
@endsection
@section('sub_breadcumb', __('Export Report IDEP'))

@section('content_body')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Sistem Laporan Kegiatan</h3>
    </div>
    <div class="card-body table-responsive">

    </div>
</div>
@stop

@push('css')
    {{-- <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.2rem;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .report-config {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .config-section {
            margin-bottom: 25px;
        }

        .config-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ecf0f1;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .form-control {
            padding: 12px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item input[type="checkbox"] {
            transform: scale(1.2);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-success {
            background: #2ecc71;
            color: white;
        }

        .btn-info {
            background: #3498db;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .report-preview {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }

        .report-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .report-meta {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        .report-table th,
        .report-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .report-table th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
        }

        .report-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .report-table tr:hover {
            background-color: #e8f4fd;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
        }

        .summary-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .summary-label {
            color: #7f8c8d;
            font-size: 0.85rem;
        }

        .chart-section {
            margin: 30px 0;
        }

        .chart-container {
            height: 300px;
            margin-bottom: 20px;
        }

        .export-buttons {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .report-config,
            .export-buttons {
                display: none;
            }

            .report-preview {
                box-shadow: none;
                border: none;
            }
        }
    </style> --}}
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)
    <script>
        //
    </script>
@endpush
