# Walkthrough: Model Dashboard Implementation

## Overview

Implemented the **Model Dashboard (Komodel)** to monitor the distribution and development of IDEP models.

## Changes

### 1. Controller (`App\Http\Controllers\Revisi\KomponenModel.php`)

- **`index()`**: Fetches filter options (Programs, Provinces, Years, Sektors) and returns the dashboard view.
- **`getData()`**: Handles AJAX requests to provide dynamic data:
  - **Filters**: Applies filters for Program, Year, Province, and Sektor (`mtargetreinstra`).
  - **Stats**: Calculates Total Model, Total Types, Total Locations, and Total Quantity.
  - **Map**: Returns detailed location data including lat/long, model type, and sector.
  - **Charts**: Prepares detailed multi-dimensional data:
    - **Trend**: Grouped by Year and Model Type.
    - **Sektor**: Pivoted by Sektor and Model Type.
    - **Colors**: Assigns consistent colors per Model Type.

### 2. View (`resources/views/dashboard/revisi/model.blade.php`)

- **UI Design**: Standard **AdminLTE** design with `small-box` for stats and `card-outline` for sections, matching `home.blade.php`.
- **Layout**: Extends `layouts.app` and uses `Figtree` font.
- **Filters**: Select2 dropdowns for Program, Province, Year, and Sektor.
- **Visualizations**:
  - **Map**: **Google Maps** integration showing custom colored circular markers per Model Type. Defaults to Indonesia view (Zoom 5) on load. InfoWindow displays Program, Model Type, Quantity, and Location (Dusun, Desa, etc).
  - **Charts**:
    - **Trend**: Line chart showing trends per Model Type over years.
    - **Sektor**: Stacked Bar chart showing Model Type contribution per Sektor.
    - **Distribution**: Horizontal Bar chart showing total units per Model Type.
- **Interactivity**: AJAX reloading on filter change without page refresh.

### 3. Routes (`routes/web.php`)

- Added routes under `revisi/dashboard`:
  - `GET /revisi/dashboard/model` (Page)
  - `GET /revisi/dashboard/model/data` (AJAX Data)
