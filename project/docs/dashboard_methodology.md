# Dashboard Implementation Methodology Guide

Based on the current Beneficiary Dashboard implementation in `home.blade.php`

## Overview

This document explains the methodology used in the current dashboard implementation to help developers apply the same patterns to the new dashboard revisions (Beneficiaries, Model, and Pendanaan).

---

## Current Dashboard Architecture

### 1. **Data Loading Pattern**

The current dashboard uses AJAX to load data dynamically:

```javascript
function loadDashboardData() {
    $.ajax({
        url: "{{ route('dashboard.data') }}",
        method: "GET",
        data: {
            program_id: $("#programFilter").val(),
            provinsi_id: $("#provinsiFilter").val(),
            tahun: $("#tahunFilter").val(),
        },
        success: function (data) {
            // Update statistics cards
            $("#totalSemua").text(data.semua);
            $("#totalLaki").text(data.laki);
            $("#totalPerempuan").text(data.perempuan);
            // ... more updates
        },
        error: function () {
            // Show error notification
            Swal.fire({
                /* error config */
            });
        },
    });
}
```

**Key Points:**

- Uses jQuery AJAX for asynchronous data loading
- Filters are passed as query parameters
- Updates DOM elements directly with returned data
- Uses SweetAlert2 for error notifications

### 2. **Filter Implementation**

Filters use Select2 for enhanced dropdowns:

```javascript
// Initialize Select2
$("#programFilter, #tahunFilter, #provinsiFilter").select2();

// Event listener for filter changes
$("#programFilter, #provinsiFilter, #tahunFilter").on("change", function () {
    loadDashboardData();
    loadChartData();
    loadMapMarkers();
    reloadTableIfValid();
});
```

**Apply to New Dashboards:**

- Use Select2 for all dropdown filters
- Trigger data reload on filter change
- Update all components (stats, charts, maps, tables)

### 3. **Chart.js Implementation**

Charts are created and updated dynamically:

```javascript
let barChart, pieChart;

function loadChartData() {
    const filters = {
        provinsi_id: $("#provinsiFilter").val(),
        program_id: $("#programFilter").val(),
        tahun: $("#tahunFilter").val(),
    };

    fetch("/dashboard/data/get-desa-chart-data?" + new URLSearchParams(filters))
        .then((res) => res.json())
        .then((data) => {
            // Destroy existing charts
            if (barChart) barChart.destroy();
            if (pieChart) pieChart.destroy();

            // Create new charts
            barChart = new Chart(document.getElementById("barChart"), {
                type: "bar",
                data: {
                    /* chart data */
                },
                options: {
                    /* chart options */
                },
            });
        });
}
```

**Key Patterns:**

- Store chart instances globally
- Destroy old charts before creating new ones
- Use fetch API for data retrieval
- Pass filters as URL parameters

### 4. **Google Maps Integration**

The current dashboard uses Google Maps with Advanced Markers:

```javascript
async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");
    ({ AdvancedMarkerElement } = await google.maps.importLibrary("marker"));

    map = new Map(document.getElementById("map"), {
        center: { lat: centerLat, lng: centerLng },
        zoom: initialZoom,
        mapId: "7e7fb1bfd929ec61",
    });

    // Add marker clusterer
    markerClusterer = new markerClusterer.MarkerClusterer({
        map: map,
        markers: [],
        renderer: {
            /* custom renderer */
        },
    });
}
```

**For New Dashboards:**

- Use the same Google Maps API initialization
- Implement marker clustering for better performance
- Add zoom-based marker loading
- Use custom bubble markers for visual appeal

### 5. **DataTables Implementation**

Server-side DataTables with AJAX:

```javascript
let table = $("#tableDesa").DataTable({
    processing: true,
    serverSide: false,
    paging: true,
    pageLength: 25,
    ajax: {
        url: "{{ route('dashboard.provinsi.data.desa') }}",
        data: function (d) {
            d.program_id = $("#programFilter").val();
            d.tahun = $("#tahunFilter").val();
            d.provinsi_id = $("#provinsiFilter").val();
        },
        dataSrc: function (json) {
            // Process data before rendering
            pieChartKabupatenPenerimaManfaat(json.data || []);
            return json.data || [];
        },
    },
    columns: [
        { data: "nama_dusun", title: "Dusun" },
        { data: "desa", title: "Desa" },
        // ... more columns
    ],
});

function reloadTableIfValid() {
    if (program || tahun || provinsi) {
        table.ajax.reload(null, false);
    } else {
        table.clear().draw();
    }
}
```

**Key Features:**

- Dynamic column definitions
- Filter integration via ajax.data function
- Custom data processing in dataSrc
- Conditional table reload

---

## Recommended Structure for New Dashboards

### File Structure

```
resources/views/dashboard/
├── beneficiaries.blade.php
├── model.blade.php
└── pendanaan.blade.php
```

### Blade Template Pattern

```blade
@extends('layouts.app')

@section('subtitle', 'Dashboard Title')
@section('content_header_title', 'Dashboard Title')

@section('content_body')
    <!-- Statistics Cards -->
    <div class="row" id="dashboardCards">
        <!-- Stat boxes -->
    </div>

    <!-- Filter Section -->
    <div class="row mb-3">
        <!-- Filter dropdowns -->
    </div>

    <!-- Map Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row" id="dashboardCharts">
        <!-- Chart cards -->
    </div>

    <!-- Table Section (if needed) -->
    <div class="row">
        <!-- DataTable -->
    </div>
@endsection

@push('css')
    <style>
        /* Custom styles */
    </style>
@endpush

@push('js')
    @section('plugins.Select2', true)
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)

    <script>
        $(document).ready(function() {
            // Initialize components
            initFilters();
            loadDashboardData();
            loadChartData();
            initMap();
        });
    </script>
@endpush
```

### Controller Pattern

```php
public function index(Request $request)
{
    // Get filter parameters
    $programId = $request->get('program_id');
    $sektorId = $request->get('sektor_id');

    // Get dropdown data
    $programs = Program::select('id', 'nama', 'kode')->get();
    $sektors = TargetReinstra::select('id', 'deskripsi as nama')->get();

    // Return view with initial data
    return view('dashboard.revisi.dashboard_name', compact(
        'programs',
        'sektors'
    ));
}

// AJAX endpoint for data
public function getData(Request $request)
{
    $programId = $request->get('program_id');

    $data = [
        'stats' => $this->getStatistics($programId),
        'chartData' => $this->getChartData($programId),
        'locations' => $this->getLocations($programId),
    ];

    return response()->json($data);
}
```

---

## Best Practices from Current Implementation

### 1. **Error Handling**

Always provide user feedback:

```javascript
.catch(error => {
    console.error('Error:', error);
    Swal.fire({
        icon: 'warning',
        title: 'Tidak ada data',
        timer: 3000,
        position: 'top-end',
    });
});
```

### 2. **Loading States**

Show processing indicators:

```javascript
$("#dashboardCards").html(
    '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>',
);
```

### 3. **Responsive Design**

Use Bootstrap grid system:

```html
<div class="col-lg-3 col-md-6 col-sm-12">
    <!-- Content -->
</div>
```

### 4. **Print Styles**

Include print-specific CSS:

```css
@media print {
    .main-sidebar,
    .main-header,
    .content-header {
        display: none !important;
    }
    .content-wrapper {
        margin-left: 0 !important;
    }
}
```

### 5. **Color Schemes**

Use consistent color generation:

```javascript
function generateColors(count) {
    const baseColors = ["#4caf50", "#03a9f4", "#00bcd4", "#e91e63"];
    // Generate additional colors if needed
    return baseColors.slice(0, count);
}
```

---

## Integration Checklist

When implementing new dashboards:

- [ ] Use Select2 for all dropdowns
- [ ] Implement AJAX data loading
- [ ] Add SweetAlert2 for notifications
- [ ] Use Chart.js for visualizations
- [ ] Implement Google Maps with clustering
- [ ] Add DataTables for tabular data
- [ ] Include print styles
- [ ] Add responsive breakpoints
- [ ] Implement error handling
- [ ] Add loading indicators
- [ ] Use consistent color schemes
- [ ] Follow existing naming conventions

---

## Route Structure

Follow the existing pattern:

```php
Route::middleware(['auth'])->prefix('/dashboard')->name('dashboard.')->group(function () {
    Route::get('/beneficiary', [Beneficiaries::class, 'index'])->name('beneficiary');
    Route::get('/beneficiary/data', [Beneficiaries::class, 'getData'])->name('beneficiary.data');

    Route::get('/model', [KomponenModel::class, 'index'])->name('model');
    Route::get('/model/data', [KomponenModel::class, 'getData'])->name('model.data');

    Route::get('/pendanaan', [Pendanaan::class, 'index'])->name('pendanaan');
    Route::get('/pendanaan/data', [Pendanaan::class, 'getData'])->name('pendanaan.data');
});
```

---

## JavaScript Libraries Used

Current dashboard dependencies:

1. **jQuery** - DOM manipulation and AJAX
2. **Select2** - Enhanced dropdowns
3. **Chart.js 4.4.1** - Charts and graphs
4. **Google Maps API** - Interactive maps
5. **MarkerClusterer** - Map marker clustering
6. **DataTables** - Interactive tables
7. **SweetAlert2** - Beautiful alerts
8. **html2canvas** - Screenshot/export functionality

All these should be available in the new dashboards as well.

---

## Summary

The current dashboard implementation provides a solid foundation for the new dashboard revisions. Key takeaways:

1. **Modular JavaScript** - Separate functions for each component
2. **AJAX-driven** - Dynamic data loading without page refresh
3. **Filter Integration** - All components respond to filter changes
4. **Responsive Design** - Works on all screen sizes
5. **User Feedback** - Clear error messages and loading states
6. **Performance** - Marker clustering and lazy loading
7. **Print Support** - Optimized for printing

Apply these patterns consistently across all three new dashboards for a cohesive user experience.
