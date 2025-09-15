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
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

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
                        <div class="filter-section">
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-control" id="filterProgram">
                                        <option value="">All Programs</option>
                                        <option value="1">MEALS Program 1</option>
                                        <option value="2">MEALS Program 2</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="filterProvinsi">
                                        <option value="">All Provinces</option>
                                        <option value="1">Bali</option>
                                        <option value="2">Nusa Tenggara Barat</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" id="filterDateFrom" placeholder="From Date">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" id="filterDateTo" placeholder="To Date">
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
                                        <span class="info-box-number" id="totalComponents">1,247</span>
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
                                        <span class="info-box-number" id="activePrograms">65</span>
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
                                        <span class="info-box-number" id="coverageAreas">34</span>
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
                                        <span class="info-box-number" id="totalBeneficiaries">12,547</span>
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
                                                    <th>Component</th>
                                                    <th>Location</th>
                                                    <th>Progress</th>
                                                    <th>Status</th>
                                                    <th>Last Updated</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>MEALS Program Alpha</td>
                                                    <td>Water System</td>
                                                    <td>Denpasar, Bali</td>
                                                    <td>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" style="width: 85%"></div>
                                                        </div>
                                                        <small>85% Complete</small>
                                                    </td>
                                                    <td><span class="badge bg-success">On Track</span></td>
                                                    <td>2 hours ago</td>
                                                    <td>
                                                        <button class="btn btn-xs btn-info">View</button>
                                                        <button class="btn btn-xs btn-warning">Edit</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>MEALS Program Beta</td>
                                                    <td>Education Center</td>
                                                    <td>Mataram, NTB</td>
                                                    <td>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-warning" style="width: 65%"></div>
                                                        </div>
                                                        <small>65% Complete</small>
                                                    </td>
                                                    <td><span class="badge bg-warning">Delayed</span></td>
                                                    <td>5 hours ago</td>
                                                    <td>
                                                        <button class="btn btn-xs btn-info">View</button>
                                                        <button class="btn btn-xs btn-warning">Edit</button>
                                                    </td>
                                                </tr>
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
                                                <span class="info-box-number">15</span>
                                            </div>
                                        </div>

                                        <div class="info-box mb-3">
                                            <span class="info-box-icon bg-info"><i class="fas fa-city"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Districts</span>
                                                <span class="info-box-number">87</span>
                                            </div>
                                        </div>

                                        <div class="info-box mb-3">
                                            <span class="info-box-icon bg-success"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Villages</span>
                                                <span class="info-box-number">342</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top Locations</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Bali Province
                                                <span class="badge badge-primary badge-pill">125</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Nusa Tenggara Barat
                                                <span class="badge badge-primary badge-pill">98</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Nusa Tenggara Timur
                                                <span class="badge badge-primary badge-pill">76</span>
                                            </li>
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
                                                <tr>
                                                    <td>001</td>
                                                    <td>Water Infrastructure</td>
                                                    <td>MEALS Alpha</td>
                                                    <td>15 units</td>
                                                    <td>5 villages</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                    <td>2024-01-15</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-xs btn-info">View</button>
                                                            <button class="btn btn-xs btn-warning">Edit</button>
                                                            <button class="btn btn-xs btn-danger">Delete</button>
                                                        </div>
                                                    </td>
                                                </tr>
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
                                        <span class="info-box-number">12,547</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-female"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Female</span>
                                        <span class="info-box-number">6,789</span>
                                        <span class="info-box-text">54.1%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-male"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Male</span>
                                        <span class="info-box-number">5,758</span>
                                        <span class="info-box-text">45.9%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-heart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Vulnerable Groups</span>
                                        <span class="info-box-number">2,134</span>
                                        <span class="info-box-text">17%</span>
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

    <script>
        $(document).ready(function() {
            // Tab Navigation
            $('.nav-link[data-tab]').click(function(e) {
                e.preventDefault();
                var targetTab = $(this).data('tab');

                // Update active states
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                // Show/hide content
                $('.tab-content').removeClass('active').hide();
                $('#' + targetTab).addClass('active').show();

                // Initialize tab-specific content
                initializeTabContent(targetTab);
            });

            // Initialize Overview tab by default
            initializeTabContent('overview');

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
                    case 'programs':
                        initializePrograms();
                        break;
                    case 'operations':
                        initializeOperations();
                        break;
                }
            }

            function initializeOverview() {
                // Initialize Program Distribution Chart
                var ctx1 = document.getElementById('programDistributionChart').getContext('2d');
                new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        labels: ['MEALS Alpha', 'MEALS Beta', 'MEALS Gamma', 'MEALS Delta'],
                        datasets: [{
                            data: [35, 25, 20, 20],
                            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Initialize Deployment Timeline Chart
                var ctx2 = document.getElementById('deploymentTimelineChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Components Deployed',
                            data: [45, 78, 123, 189, 234, 289],
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Initialize DataTable for progress
                $('#progressTable').DataTable({
                    responsive: true,
                    pageLength: 5,
                    searching: false,
                    ordering: true,
                    info: false
                });
            }

            function initializeGeographic() {
                // Initialize Leaflet Map
                if ($('#componentMap').length && !window.componentMapInitialized) {
                    var map = L.map('componentMap').setView([-8.5, 116.0], 8);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    // Sample markers for component locations
                    var markers = [{
                            lat: -8.6705,
                            lng: 115.2126,
                            title: 'Denpasar - Water System',
                            components: 5
                        },
                        {
                            lat: -8.7335,
                            lng: 115.1772,
                            title: 'Ubud - Education Center',
                            components: 3
                        },
                        {
                            lat: -8.4095,
                            lng: 115.1889,
                            title: 'Singaraja - Health Clinic',
                            components: 2
                        },
                        {
                            lat: -8.8451,
                            lng: 116.2809,
                            title: 'Mataram - Community Center',
                            components: 4
                        }
                    ];

                    markers.forEach(function(marker) {
                        L.marker([marker.lat, marker.lng])
                            .bindPopup(`<b>${marker.title}</b><br/>${marker.components} components`)
                            .addTo(map);
                    });

                    window.componentMapInitialized = true;
                }
            }

            function initializeComponents() {
                // Component Type Distribution Chart
                var ctx3 = document.getElementById('componentTypeChart').getContext('2d');
                new Chart(ctx3, {
                    type: 'bar',
                    data: {
                        labels: ['Water Infrastructure', 'Education Centers', 'Health Clinics', 'Community Centers', 'Agriculture Support'],
                        datasets: [{
                            label: 'Number of Components',
                            data: [45, 32, 28, 35, 22],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(255, 205, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Utilization Rate Chart
                var ctx4 = document.getElementById('utilizationChart').getContext('2d');
                new Chart(ctx4, {
                    type: 'radar',
                    data: {
                        labels: ['Planning', 'Implementation', 'Monitoring', 'Completion', 'Maintenance'],
                        datasets: [{
                            label: 'Current Performance',
                            data: [85, 78, 92, 67, 73],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }, {
                            label: 'Target Performance',
                            data: [90, 85, 95, 80, 85],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });

                // Initialize Components DataTable
                $('#componentsTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    processing: true,
                    serverSide: false, // Would be true in real implementation
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'component_type'
                        },
                        {
                            data: 'program'
                        },
                        {
                            data: 'quantity'
                        },
                        {
                            data: 'locations'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'created'
                        },
                        {
                            data: 'actions',
                            orderable: false
                        }
                    ]
                });
            }

            function initializeBeneficiaries() {
                // Age Group Distribution Chart
                var ctx5 = document.getElementById('ageGroupChart').getContext('2d');
                new Chart(ctx5, {
                    type: 'pie',
                    data: {
                        labels: ['Children (0-17)', 'Youth (18-30)', 'Adults (31-59)', 'Elderly (60+)'],
                        datasets: [{
                            data: [28, 35, 25, 12],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 205, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Program Participation Chart
                var ctx6 = document.getElementById('participationChart').getContext('2d');
                new Chart(ctx6, {
                    type: 'horizontalBar',
                    data: {
                        labels: ['Direct Participation', 'Training Programs', 'Community Events', 'Capacity Building'],
                        datasets: [{
                            label: 'Participation Rate (%)',
                            data: [87, 65, 92, 73],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(255, 205, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            }

            function initializePrograms() {
                // Program hierarchy would be loaded dynamically via AJAX
                console.log('Program hierarchy initialized');
            }

            function initializeOperations() {
                // Operational dashboard initialization
                console.log('Operations dashboard initialized');
            }

            // AJAX Functions for dynamic data loading
            function loadKPIData() {
                // This would make AJAX calls to Laravel backend
                $.ajax({
                    url: '/api/dashboard/kpi',
                    method: 'GET',
                    success: function(data) {
                        $('#totalComponents').text(data.total_components);
                        $('#activePrograms').text(data.active_programs);
                        $('#coverageAreas').text(data.coverage_areas);
                        $('#totalBeneficiaries').text(data.total_beneficiaries);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading KPI data:', error);
                    }
                });
            }

            function loadGeographicData() {
                $.ajax({
                    url: '/api/dashboard/geographic',
                    method: 'GET',
                    success: function(data) {
                        // Update map markers and location data
                        console.log('Geographic data loaded:', data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading geographic data:', error);
                    }
                });
            }

            function loadComponentData() {
                $.ajax({
                    url: '/api/dashboard/components',
                    method: 'GET',
                    success: function(data) {
                        // Update component charts and tables
                        console.log('Component data loaded:', data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading component data:', error);
                    }
                });
            }

            // Event Handlers
            $('#refreshData').click(function() {
                $(this).find('i').addClass('fa-spin');

                // Load all data
                loadKPIData();
                loadGeographicData();
                loadComponentData();

                setTimeout(() => {
                    $(this).find('i').removeClass('fa-spin');
                    toastr.success('Data refreshed successfully');
                }, 2000);
            });

            $('#exportReport').click(function() {
                window.location.href = '/api/dashboard/export/pdf';
            });

            // Filter change handlers
            $('#filterProgram, #filterProvinsi, #filterDateFrom, #filterDateTo').change(function() {
                // Apply filters and reload data
                var filters = {
                    program: $('#filterProgram').val(),
                    provinsi: $('#filterProvinsi').val(),
                    date_from: $('#filterDateFrom').val(),
                    date_to: $('#filterDateTo').val()
                };

                $.ajax({
                    url: '/api/dashboard/filter',
                    method: 'POST',
                    data: filters,
                    success: function(data) {
                        // Update all dashboard components with filtered data
                        console.log('Filtered data loaded:', data);
                    }
                });
            });

            // Auto-refresh data every 5 minutes
            setInterval(function() {
                loadKPIData();
            }, 300000);

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Load initial data
            loadKPIData();
        });

        // Utility Functions
        function formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID');
        }

        // Export functions for external use
        window.MealsDashboard = {
            refreshData: function() {
                $('#refreshData').click();
            },
            switchTab: function(tabName) {
                $('[data-tab="' + tabName + '"]').click();
            },
            exportReport: function() {
                $('#exportReport').click();
            }
        };
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

</html> }
    };
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
