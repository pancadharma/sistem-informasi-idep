The **sistem‑informasi‑idep** repository is organised under a `project/` folder and is a fairly standard Laravel 10 application.  The `composer.json` lists common Laravel packages such as `laravel/framework`, `barryvdh/laravel‑dompdf`, `yajra/laravel‑datatables‑oracle` and several Spatie libraries.  The top‑level `README.md` explains that the project is a training‑management system with modules for programme management, participant tracking and reporting.

### Structure and key components

* **Modules and Controllers** – The application has many modules living in `project/app/Http/Controllers/Admin`.  For example `ProgramController.php` manages CRUD logic for training programmes, including dashboards and data‑table rendering.  A separate `TrProgramController.php` handles file uploads for programme documents and attachments.
* **Kegiatan (activity) module** – `KegiatanController.php` is very large (\~1 300 lines).  It handles listing, viewing, creating, storing and exporting activities.  Activities are presented via server‑side DataTables and various permission checks.  Export functionality is implemented inline: depending on the requested format, the controller renders a blade view to PDF using `laravel‑dompdf` or creates a DOCX using `PhpWord`, then returns a download.
* **Dependencies** – The project uses AdminLTE for the admin UI, Spatie’s MediaLibrary for file storage, Laravel Sanctum for API auth and laraveldaily/laravel‑charts for charts.

### Observations

1. **Large controllers / many responsibilities** – `KegiatanController` encapsulates many distinct concerns: data retrieval, view rendering, validation, permission checks, PDF/Word export and call‑outs to API controllers.  This makes the file hard to navigate and test.
2. **Export logic tied to controller and views** – The export function is embedded in the controller with PDF and DOCX branches.  Code is duplicated between PDF and Word generation (setting fonts, rendering HTML etc.).
3. **Mixed presentation and business logic** – DataTables column definitions and HTML snippets are written directly inside controllers, mixing presentation with backend logic.
4. **.DS\_Store and other Mac‑specific files** – The repository includes `.DS_Store` files, which should be removed and added to `.gitignore`.
5. **No CLI implementation yet** – The project does not currently provide artisan commands or CLI scripts for exporting “kegiatan”/“program” data.  Export happens only via the web interface.

### Recommendations

* **Refactor controllers** – Apply the Single Responsibility Principle by moving complex logic into Services or Actions.  For example, create an `KegiatanExportService` that accepts a Kegiatan instance and returns a file in the desired format.  Controllers would then delegate to this service.
* **Add artisan commands** – To enable command‑line exports, create artisan console commands (e.g. `php artisan kegiatan:export {id} {--format=pdf}`) that call the export service and save files.  This keeps CLI and web export logic consistent.
* **Separate DataTables and view code** – Use DataTable classes or view composers to encapsulate column definitions and button generation.  This removes HTML strings from controllers and allows easier testing.
* **Improve security and validation** – Centralise permission checks via Laravel policies and middleware rather than repeating checks in controllers.  Ensure that all requests are validated using Form Request classes.
* **Remove unnecessary files** – Add `.DS_Store`, `.editorconfig` and other non‑Laravel artefacts to `.gitignore` and clean them from the repository.
* **Documentation** – The root `README.md` is a good start; consider adding a `project/README.md` describing how to set up the Laravel application (environment variables, database migrations, seeders) and any custom packages.
* **Consider using typed enums** – Instead of string constants, use PHP 8.1 enums for statuses such as `Program` statuses or `Kegiatan` types.  This improves type safety.

Implementing these changes will make the project easier to maintain, facilitate unit testing and allow for CLI‑driven exports without duplicating logic.

The **dashboard/home page** is not populated with data directly in the blade template; it loads most of its content through AJAX calls to controller methods.  Here’s how each part of the page gets its data and which controllers and database tables are involved.

### 1. Variables passed directly to the view

* **Filters (Program, Year, Province)** – When a user visits the dashboard, `HomeController@index` queries the database and passes the lists of programs (`trprogram`), active provinces (`provinsi`) and distinct years to the view.  These variables populate the “Program,” “Periode (Tahun)” and “Provinsi” dropdowns.  The controller also passes the **Google Maps API key** to the view.

### 2. Dashboard statistic cards

* **AJAX endpoint** – The JavaScript function `loadDashboardData()` sends a GET request to `route('dashboard.data')`, which invokes `HomeController::getDashboardData`.
* **Query logic** – `getDashboardData` builds a query on the `trmeals_penerima_manfaat` table (model `Meals_Penerima_Manfaat`) and filters by province (`provinsi_id`), program (`program_id`) and year (`tahun`).  It counts total beneficiaries, male, female, children (boy/girl), disabled and unique families and returns these counts as JSON.  The AJAX success handler updates the statistic cards with these totals.

### 3. Desa‑per‑Provinsi charts

* **AJAX endpoint** – The `loadChartData()` function calls `/dashboard/data/get-desa-chart-data` (route name `dashboard.chart.desa`).  This maps to `HomeController::getDesaPerProvinsiChartData`.
* **Query logic** – This method queries `trmeals_penerima_manfaat` joined through `dusun → desa → kecamatan → kabupaten → provinsi`, applies the same filters and groups by province.  For each province it counts the number of distinct villages (`kelurahan`) with beneficiaries and returns an array of `{ provinsi, total_desa }` objects.  The JS code uses Chart.js to draw a bar chart and pie chart with this data.

### 4. Google Maps markers

* **Markers for province view** – When the map is zoomed out (<9) or no province is selected, `loadMapMarkers()` calls the `dashboard.api.markers` route.  This route is `HomeController::getFilteredProvinsi`.  It assembles a query on `trmeals_penerima_manfaat` joined through all geographic tables, applies optional filters and either selects one province (`id`) or all provinces with beneficiaries.  For each province it counts the number of villages (`total_desa`) and beneficiaries (`total_penerima`), and returns these statistics along with the province’s stored latitude/longitude.  The map uses these lat/long values to place red pins; clicking a pin opens an info window showing the total villages and beneficiaries for that province.

* **Clustered desa markers** – When a province is selected and the map is zoomed in (≥9), `loadMapMarkers()` calls the `dashboard.api.combined_desa_map_data` route.  This maps to `HomeController::getCombinedDesaMapData`.  The method builds a complex query:

  * It creates subqueries for **exact** coordinates from `trkegiatan_lokasi`, **average** dusun coordinates from `dusun`, and falls back to kabupaten coordinates when neither exists.
  * It joins these subqueries to `kelurahan` (`desa`) and counts distinct beneficiaries (`trmeals_penerima_manfaat`) per village.
  * It filters by `provinsi_id`, `program_id` and `tahun`.
  * For each village it returns the latitude/longitude, the coordinate source (exact, averaged, dusun or kabupaten) and the total beneficiaries in that village.

  The front‑end script uses these data points to create bubble markers showing the number of beneficiaries and displays an info window with details about the village and coordinate source.

### 5. DataTable and kabupaten pie chart

* **Table data** – The DataTable of dusun (villages) is loaded by the AJAX call to `route('dashboard.provinsi.data.desa')`, which invokes `DashboardProvinsiController::getFilteredDataDesa`.  This method queries `trmeals_penerima_manfaat` with relations to `dusun → desa → kecamatan → kabupaten → provinsi`, applies the same filters and groups results by `dusun_id`.  It returns, for each dusun, its name, desa, kecamatan, kabupaten, provinsi and the count of beneficiaries and additional gender statistics.  The view updates the kabupaten pie chart by aggregating the returned rows on the client side.

### Summary

* **Home page variables** – Provided by `HomeController@index`: programs, provinces and years.
* **Statistics cards** – Fetched via AJAX from `getDashboardData` and updated dynamically.
* **Charts** – Populated using data from `getDesaPerProvinsiChartData`.
* **Map markers** – Province pins come from `getFilteredProvinsi`; clustered desa markers come from `getCombinedDesaMapData`.
* **Table** – Populated via `DashboardProvinsiController::getFilteredDataDesa`.

Thus, the `home.blade.php` view itself contains only the HTML scaffolding and JavaScript functions; all dynamic data is supplied by these controller endpoints, which query the underlying MySQL tables (`trmeals_penerima_manfaat`, `trprogram`, `provinsi`, `dusun`, `kelurahan`, `kecamatan`, `kabupaten`, `trkegiatan_lokasi`, etc.) and return JSON to the front‑end for rendering.

### Improve Suggestion
Here’s what the current “print/export” implementation does and some targeted improvements to make the exported dashboard mirror the on‑screen dashboard more closely.

---

### Current print/export behaviour

1. **DataTable Pagination** – When the user clicks **Print**, the script temporarily sets the DataTable page length to `-1` so all rows are shown, triggers `window.print()`, then resets the page length.  This prevents pagination in the printed version.
2. **CSS for print** – An extensive `@media print` block hides the sidebar, header, filter row, pagination controls and other non‑essential elements.  It also adjusts grid widths and ensures DataTables rows are bordered.
3. **Charts and map** – The dashboard uses Chart.js canvases for the bar and pie charts and an interactive Google Map.  When the browser prints, canvases and embedded maps aren’t guaranteed to render correctly.  The CSS doesn’t explicitly hide them, but many browsers skip canvases in print or produce blank boxes.
4. **Export scope** – The printed page is essentially a screenshot of the current HTML.  There’s no server‑side PDF generation, and the interactivity (tooltips, collapsible cards) is lost.

---

### Recommendations to improve export fidelity

#### 1. Preserve charts by converting them to images during print

Canvas elements often render blank in PDF/print.  You can convert each Chart.js canvas to a PNG data URL just before printing and temporarily replace the canvas with an `<img>`:

```javascript
function convertChartsForPrint() {
  const barCanvas = document.getElementById('barChart');
  const pieCanvas = document.getElementById('pieChart');
  const barImg = document.getElementById('barChartImg');
  const pieImg = document.getElementById('pieChartImg');

  barImg.src = barCanvas.toDataURL('image/png');
  pieImg.src = pieCanvas.toDataURL('image/png');
  barCanvas.style.display = 'none';
  pieCanvas.style.display = 'none';
  barImg.style.display = 'block';
  pieImg.style.display = 'block';
}

function restoreChartsAfterPrint() {
  // reverse the above changes
}
```

In the blade file, place hidden `<img id="barChartImg">` and `<img id="pieChartImg">` right next to the canvases.  Call `convertChartsForPrint()` before calling `window.print()` and `restoreChartsAfterPrint()` in `onafterprint`.  This ensures the charts look identical to the on‑screen versions.

#### 2. Capture the map as a static image

Interactive maps aren’t printable.  You can use a library like **html2canvas** to capture the map div and replace it with an `<img>` for printing:

```javascript
function convertMapForPrint() {
  html2canvas(document.getElementById('map'), {useCORS: true}).then(canvas => {
    const mapImg = document.getElementById('mapPrintImage');
    mapImg.src = canvas.toDataURL('image/png');
    document.getElementById('map').style.display = 'none';
    mapImg.style.display = 'block';
  });
}

function restoreMapAfterPrint() {
  document.getElementById('map').style.display = 'block';
  document.getElementById('mapPrintImage').style.display = 'none';
}
```

Add `<img id="mapPrintImage" style="display:none;width:100%;height:500px;">` below the map container.  Include html2canvas via a CDN.  Then in your print handler, call `convertMapForPrint()` before `window.print()` and `restoreMapAfterPrint()` afterwards.

An alternative is to call the Google Static Maps API to generate a static snapshot of the map with current markers, but html2canvas avoids the need for an extra API call.

#### 3. Consolidate DataTable export behaviour

The current code uses `table.page.len(-1).draw()` to show all rows for printing.  To ensure consistency:

* Set `paging: false` inside the print handler to disable pagination entirely.
* After printing, restore the original pagination settings.
* Consider disabling search/order controls during print so they don’t clutter the page.

#### 4. Ensure summary cards stay visible

The `@media print` block hides various UI elements, but the statistic cards (`#dashboardCards`) remain.  Confirm that the cards’ colours and icons print legibly; you may want to add a light border or background for contrast.

#### 5. (Optional) Add a dedicated PDF export

If you need a polished export that doesn’t rely on the browser, create a new route (e.g. `/dashboard/export`) and use **laravel-dompdf** to assemble a PDF.  Build a blade template mirroring the dashboard layout: query the same data as `getDashboardData()`, `getDesaPerProvinsiChartData()` and `getCombinedDesaMapData()`, convert charts to images using Chart.js’s `toBase64Image()` method, and embed a static map image (via Static Maps API).  This server‑side approach produces a consistent, professional export regardless of browser quirks.

---

These adjustments will ensure the exported/printed dashboard matches the on‑screen display more closely, includes all DataTable rows without pagination and preserves the visual charts and map rather than leaving empty boxes.
