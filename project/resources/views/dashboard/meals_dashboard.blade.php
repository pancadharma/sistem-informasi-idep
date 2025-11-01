<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MEALS Dashboard | IDEP</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <!-- Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .info-box-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-container {
            height: 400px;
            border-radius: 5px;
        }

        .progress-sm {
            height: 10px;
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255,255,255,0.7);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .loading-overlay i {
            font-size: 3rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="loading-overlay">
            <i class="fas fa-sync-alt fa-spin"></i>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="far fa-user"></i> Admin User
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">MEALS System</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                        <li class="nav-item">
                            <a href="#overview" class="nav-link active" data-tab="overview">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Overview</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#geographic" class="nav-link" data-tab="geographic">
                                <i class="nav-icon fas fa-map-marked-alt"></i>
                                <p>Geographic Distribution</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#components" class="nav-link" data-tab="components">
                                <i class="nav-icon fas fa-cubes"></i>
                                <p>Component Analysis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#beneficiaries" class="nav-link" data-tab="beneficiaries">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Beneficiaries</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#programs" class="nav-link" data-tab="programs">
                                <i class="nav-icon fas fa-project-diagram"></i>
                                <p>Program Integration</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#operations" class="nav-link" data-tab="operations">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Operations</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">

            <!-- Overview Tab -->
            <div id="overview" class="tab-content active">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Executive Overview</h1>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-sm-right">
                                    <button class="btn btn-sm btn-primary" id="refreshData">
                                        <i class="fas fa-sync-alt"></i> Refresh Data
                                    </button>
                                    <button class="btn btn-sm btn-success" id="exportReport">
                                        <i class="fas fa-download"></i> Export Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">

                        <!-- Filter Section -->
                        <div class="filter-section card card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Program</label>
                                        <select class="form-control" id="filterProgram">
                                            <option value="">All Programs</option>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}">{{ $program->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Province</label>
                                        <select class="form-control" id="filterProvinsi">
                                            <option value="">All Provinces</option>
                                            @foreach($provinces_for_filter as $province)
                                                <option value="{{ $province->id }}">{{ $province->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Component Model</label>
                                        <select class="form-control" id="filterKomponenModel">
                                            <option value="">All Components</option>
                                            @foreach($komponen_models as $model)
                                                <option value="{{ $model->id }}">{{ $model->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date Range</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="filterDateRange" placeholder="Select date range">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KPI Cards -->
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-cubes"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Components</span>
                                        <span class="info-box-number" id="totalComponents">{{ number_format($total_components) }}</span>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" style="width: 85%"></div>
                                        </div>
                                        <span class="progress-description">85% of target</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-project-diagram"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Active Programs</span>
                                        <span class="info-box-number" id="activePrograms">{{ number_format($active_programs) }}</span>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: 92%"></div>
                                        </div>
                                        <span class="progress-description">92% operational</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-map-marker-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Coverage Areas</span>
                                        <span class="info-box-number" id="coverageAreas">{{ number_format($coverage_areas) }}</span>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width: 78%"></div>
                                        </div>
                                        <span class="progress-description">Provinces covered</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Beneficiaries</span>
                                        <span class="info-box-number" id="totalBeneficiaries">{{ number_format($total_beneficiaries) }}</span>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" style="width: 95%"></div>
                                        </div>
                                        <span class="progress-description">95% target reached</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Program Distribution</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="programDistributionChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Component Deployment Timeline</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="deploymentTimelineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Tracking -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Recent Progress Updates</h3>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table table-striped" id="progressTable">
                                            <thead>
                                                <tr>
                                                    <th>Program</th>
                                                    <th>Component/Target</th>
                                                    <th>Location</th>
                                                    <th>Progress</th>
                                                    <th>Status</th>
                                                    <th>Last Updated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recent_progress as $progress)
                                                <tr>
                                                    <td>{{ $progress['program'] }}</td>
                                                    <td>{{ Str::limit($progress['component'], 30) }}</td>
                                                    <td>{{ $progress['location'] }}</td>
                                                    <td>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" style="width: {{ $progress['progress'] }}%"></div>
                                                        </div>
                                                        <small>{{ $progress['progress'] }}% Complete</small>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $progress['status'] }}</span></td>
                                                    <td>{{ $progress['last_updated'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Geographic Tab -->
            <div id="geographic" class="tab-content">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Geographic Distribution</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Component Locations Map</h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="componentMap" class="map-container"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Location Summary</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-box mb-3">
                                            <span class="info-box-icon bg-primary"><i class="fas fa-map"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Provinces</span>
                                                <span class="info-box-number" id="provincesCount">{{ number_format($provinces) }}</span>
                                            </div>
                                        </div>

                                        <div class="info-box mb-3">
                                            <span class="info-box-icon bg-info"><i class="fas fa-city"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Districts</span>
                                                <span class="info-box-number" id="districtsCount">{{ number_format($districts) }}</span>
                                            </div>
                                        </div>

                                        <div class="info-box mb-3">
                                            <span class="info-box-icon bg-success"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Villages</span>
                                                <span class="info-box-number" id="villagesCount">{{ number_format($villages) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top Locations</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group" id="topLocationsList">
                                            @foreach($top_locations as $location)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $location->name }}
                                                <span class="badge badge-primary badge-pill">{{ $location->count }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Components Tab -->
            <div id="components" class="tab-content">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Component Model Analysis</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Component Type Distribution</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="componentTypeChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Utilization Rates</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="utilizationChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Component Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped" id="componentsTable">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Component Type</th>
                                                    <th>Program</th>
                                                    <th>Total Quantity</th>
                                                    <th>Locations</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               @foreach($component_details as $detail)
                                                <tr>
                                                    <td>{{ $detail['id'] }}</td>
                                                    <td>{{ $detail['component_type'] }}</td>
                                                    <td>{{ $detail['program'] }}</td>
                                                    <td>{{ $detail['quantity'] }}</td>
                                                    <td>{{ $detail['locations'] }}</td>
                                                    <td>{!! $detail['status'] !!}</td>
                                                    <td>{{ $detail['created'] }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-xs btn-info">View</button>
                                                            <button class="btn btn-xs btn-warning">Edit</button>
                                                            <button class="btn btn-xs btn-danger">Delete</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Beneficiaries Tab -->
            <div id="beneficiaries" class="tab-content">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Beneficiary Impact Dashboard</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Beneficiaries</span>
                                        <span class="info-box-number" id="beneficiariesTotal">{{ number_format($total_beneficiaries) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-female"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Female</span>
                                        <span class="info-box-number" id="beneficiariesFemale">{{ number_format($beneficiary_by_gender['perempuan'] ?? 0) }}</span>
                                        <span class="info-box-text">{{ $total_beneficiaries > 0 ? number_format((($beneficiary_by_gender['perempuan'] ?? 0) / $total_beneficiaries) * 100, 1) : 0 }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-male"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Male</span>
                                        <span class="info-box-number" id="beneficiariesMale">{{ number_format($beneficiary_by_gender['laki-laki'] ?? 0) }}</span>
                                        <span class="info-box-text">{{ $total_beneficiaries > 0 ? number_format((($beneficiary_by_gender['laki-laki'] ?? 0) / $total_beneficiaries) * 100, 1) : 0 }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-heart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Vulnerable Groups</span>
                                        <span class="info-box-number" id="beneficiariesVulnerable">{{ number_format($beneficiary_vulnerable) }}</span>
                                        <span class="info-box-text">{{ $total_beneficiaries > 0 ? number_format(($beneficiary_vulnerable / $total_beneficiaries) * 100, 1) : 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Age Group Distribution</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="ageGroupChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Program Participation</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="participationChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Programs Tab -->
            <div id="programs" class="tab-content">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Program Integration View</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Program Hierarchy</h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="programTreeview">
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-folder-open text-warning"></i>
                                                    <strong>MEALS Program Alpha</strong>
                                                    <ul class="list-unstyled ml-3">
                                                        <li><i class="fas fa-bullseye text-info"></i> Outcome: Improved Water Access</li>
                                                        <li class="ml-3">
                                                            <i class="fas fa-arrow-right text-success"></i> Output: Water Systems Built
                                                            <ul class="list-unstyled ml-3">
                                                                <li><i class="fas fa-tasks text-primary"></i> Activity: Site Preparation</li>
                                                                <li><i class="fas fa-tasks text-primary"></i> Activity: Construction</li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Operations Tab -->
            <div id="operations" class="tab-content">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Operational Management</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">User Assignment Matrix</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Active Components</th>
                                                    <th>Workload</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>15</td>
                                                    <td>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" style="width: 75%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Jane Smith</td>
                                                    <td>12</td>
                                                    <td>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Data Quality Monitoring</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-box mb-2">
                                            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Complete Records</span>
                                                <span class="info-box-number">95.2%</span>
                                            </div>
                                        </div>
                                        <div class="info-box mb-2">
                                            <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Validation Issues</span>
                                                <span class="info-box-number">23</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 IDEP MEALS System.</strong> All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- Leaflet -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <!-- Moment.js -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <!-- Daterange picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            // Store chart instances
            let charts = {};
            let map;
            let componentsTable;

            // Tab Navigation
            $('.nav-link[data-tab]').click(function(e) {
                e.preventDefault();
                var targetTab = $(this).data('tab');

                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                $('.tab-content').removeClass('active').hide();
                $('#' + targetTab).addClass('active').show();

                // Re-initialize content if needed, especially if it was hidden
                initializeTabContent(targetTab);
            });

            function initializeTabContent(tab) {
                switch (tab) {
                    case 'overview':
                        initializeOverview();
                        break;
                    case 'geographic':
                        initializeGeographic();
                        break;
                    case 'components':
                        initializeComponents();
                        break;
                    case 'beneficiaries':
                        initializeBeneficiaries();
                        break;
                }
            }

            function initializeOverview() {
                if (!charts.programDistribution) {
                    var ctx1 = document.getElementById('programDistributionChart').getContext('2d');
                    charts.programDistribution = new Chart(ctx1, { type: 'doughnut', data: { labels: [], datasets: [] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
                }
                if (!charts.deploymentTimeline) {
                    var ctx2 = document.getElementById('deploymentTimelineChart').getContext('2d');
                    charts.deploymentTimeline = new Chart(ctx2, { type: 'line', data: { labels: [], datasets: [] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });
                }
                 if (!$.fn.DataTable.isDataTable('#progressTable')) {
                     $('#progressTable').DataTable({ responsive: true, pageLength: 5, searching: false, ordering: true, info: false });
                }
            }

            function initializeGeographic() {
                if (!map && $('#componentMap').length) {
                    map = L.map('componentMap').setView([-2.5, 118.0], 5);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' }).addTo(map);
                    updateMapMarkers({!! json_encode($map_markers) !!});
                }
            }

            function initializeComponents() {
                if (!charts.componentType) {
                    var ctx3 = document.getElementById('componentTypeChart').getContext('2d');
                    charts.componentType = new Chart(ctx3, { type: 'bar', data: { labels: {!! json_encode($component_type_distribution['labels']) !!}, datasets: [{ label: 'Number of Components', data: {!! json_encode($component_type_distribution['data']) !!}, backgroundColor: 'rgba(54, 162, 235, 0.8)' }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });
                }
                if (!charts.utilization) {
                    var ctx4 = document.getElementById('utilizationChart').getContext('2d');
                    charts.utilization = new Chart(ctx4, { type: 'radar', data: { labels: {!! json_encode($utilization_rates['labels']) !!}, datasets: [{ label: 'Current Performance', data: {!! json_encode($utilization_rates['current_performance']) !!}, backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 2 }, { label: 'Target Performance', data: {!! json_encode($utilization_rates['target_performance']) !!}, backgroundColor: 'rgba(255, 99, 132, 0.2)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, min: 0, max: 100 } } } });
                }
                if (!$.fn.DataTable.isDataTable('#componentsTable')) {
                    componentsTable = $('#componentsTable').DataTable({ responsive: true, pageLength: 10 });
                }
            }
            
            function initializeBeneficiaries() {
                 if (!charts.ageGroup) {
                    var ctx5 = document.getElementById('ageGroupChart').getContext('2d');
                    charts.ageGroup = new Chart(ctx5, { type: 'pie', data: { labels: {!! json_encode($beneficiary_age_chart['labels']) !!}, datasets: [{ data: {!! json_encode($beneficiary_age_chart['data']) !!}, backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
                }
                if (!charts.participation) {
                    var ctx6 = document.getElementById('participationChart').getContext('2d');
                    charts.participation = new Chart(ctx6, { type: 'bar', data: { labels: [], datasets: [] }, options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 100 } } } });
                }
            }

            // Initialize all tabs on load
            initializeOverview();
            initializeGeographic();
            initializeComponents();
            initializeBeneficiaries();

            // Filtering Logic
            let dateFrom, dateTo;
            $('#filterDateRange').daterangepicker({
                autoUpdateInput: false,
                locale: { cancelLabel: 'Clear' }
            });
            $('#filterDateRange').on('apply.daterangepicker', function(ev, picker) {
                dateFrom = picker.startDate.format('YYYY-MM-DD');
                dateTo = picker.endDate.format('YYYY-MM-DD');
                $(this).val(dateFrom + ' - ' + dateTo);
                applyFilters();
            });
            $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
                dateFrom = null; dateTo = null;
                $(this).val('');
                applyFilters();
            });

            $('#filterProgram, #filterProvinsi, #filterKomponenModel').change(applyFilters);

            function applyFilters() {
                $('.loading-overlay').show();
                const filters = {
                    _token: '{{ csrf_token() }}',
                    program_id: $('#filterProgram').val(),
                    provinsi_id: $('#filterProvinsi').val(),
                    komponen_model_id: $('#filterKomponenModel').val(),
                    date_from: dateFrom,
                    date_to: dateTo
                };

                $.ajax({
                    url: "{{ route('dashboard.filter') }}",
                    method: 'POST',
                    data: filters,
                    success: function(data) {
                        updateDashboard(data);
                        $('.loading-overlay').hide();
                    },
                    error: function(err) {
                        console.error('Error fetching filtered data:', err);
                        alert('Failed to load filtered data.');
                        $('.loading-overlay').hide();
                    }
                });
            }

            function updateDashboard(data) {
                // KPIs
                $('#totalComponents').text(formatNumber(data.total_components));
                $('#activePrograms').text(formatNumber(data.active_programs));
                $('#coverageAreas').text(formatNumber(data.coverage_areas));
                $('#totalBeneficiaries').text(formatNumber(data.total_beneficiaries));

                // Geographic
                $('#provincesCount').text(formatNumber(data.provinces));
                $('#districtsCount').text(formatNumber(data.districts));
                $('#villagesCount').text(formatNumber(data.villages));
                let topLocationsHtml = data.top_locations.length > 0 ? data.top_locations.map(loc => 
                    `<li class="list-group-item d-flex justify-content-between align-items-center">
                        ${loc.name}
                        <span class="badge badge-primary badge-pill">${loc.count}</span>
                    </li>`
                ).join('') : '<li class="list-group-item">No data available</li>';
                $('#topLocationsList').html(topLocationsHtml);
                if(map) updateMapMarkers(data.map_markers);

                // Overview Tab
                if(charts.programDistribution) updateChartData(charts.programDistribution, data.program_distribution_chart.labels, [{ data: data.program_distribution_chart.data, backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'] }]);
                if(charts.deploymentTimeline) updateChartData(charts.deploymentTimeline, data.deployment_timeline_chart.labels, [{ label: 'Components Deployed', data: data.deployment_timeline_chart.data, borderColor: '#007bff', tension: 0.4, fill: true }]);
                let progressTable = $('#progressTable').DataTable();
                let progressData = data.recent_progress.map(p => [p.program, p.component, p.location, `<div class="progress progress-sm"><div class="progress-bar bg-success" style="width: ${p.progress}%"></div></div><small>${p.progress}% Complete</small>`, `<span class="badge bg-info">${p.status}</span>`, p.last_updated]);
                progressTable.clear().rows.add(progressData).draw();

                // Components Tab
                if(charts.componentType) updateChartData(charts.componentType, data.component_type_distribution.labels, [{ label: 'Number of Components', data: data.component_type_distribution.data, backgroundColor: 'rgba(54, 162, 235, 0.8)' }]);
                if(charts.utilization) updateChartData(charts.utilization, data.utilization_rates.labels, [
                    { label: 'Current Performance', data: data.utilization_rates.current_performance, backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 2 },
                    { label: 'Target Performance', data: data.utilization_rates.target_performance, backgroundColor: 'rgba(255, 99, 132, 0.2)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 2 }
                ]);
                if(componentsTable) {
                    let detailsData = data.component_details.map(d => [d.id, d.component_type, d.program, d.quantity, d.locations, d.status, d.created, `<div class="btn-group"><button class="btn btn-xs btn-info">View</button></div>`]);
                    componentsTable.clear().rows.add(detailsData).draw();
                }

                // Beneficiaries Tab
                $('#beneficiariesTotal').text(formatNumber(data.total_beneficiaries));
                $('#beneficiariesFemale').text(formatNumber(data.beneficiary_by_gender.perempuan || 0));
                $('#beneficiariesMale').text(formatNumber(data.beneficiary_by_gender['laki-laki'] || 0));
                $('#beneficiariesVulnerable').text(formatNumber(data.beneficiary_vulnerable));
                if(charts.ageGroup) updateChartData(charts.ageGroup, data.beneficiary_age_chart.labels, [{ data: data.beneficiary_age_chart.data, backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'] }]);
            }

            function updateMapMarkers(markers) {
                map.eachLayer(layer => { if (layer instanceof L.Marker) map.removeLayer(layer); });
                if (markers.length > 0) {
                    const bounds = L.latLngBounds(markers.map(m => [m.lat, m.lng]));
                    if (bounds.isValid()) {
                        map.fitBounds(bounds.pad(0.1));
                    }
                    markers.forEach(marker => {
                        if(marker.lat && marker.lng) {
                            L.marker([marker.lat, marker.lng])
                            .bindPopup(`<b>${marker.title}</b><br/>${marker.components} components`)
                            .addTo(map);
                        }
                    });
                } else {
                     map.setView([-2.5, 118.0], 5);
                }
            }

            function updateChartData(chart, labels, datasets) {
                chart.data.labels = labels;
                chart.data.datasets = datasets;
                chart.update();
            }

            function formatNumber(num) {
                return new Intl.NumberFormat().format(num);
            }
        });
    </script>

    <!-- Additional CSS for better styling -->
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .chart-container canvas {
            max-height: 350px !important;
        }

        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .badge {
            font-size: 0.75em;
        }

        .btn-group .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }

        .leaflet-container {
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .info-box {
                margin-bottom: 1rem;
            }

            .chart-container {
                height: 250px !important;
            }
        }

        /* Loading animation */
        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }

        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(359deg);
            }
        }

        /* Custom scrollbar for tables */
        .table-responsive::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

</body>

</html>
