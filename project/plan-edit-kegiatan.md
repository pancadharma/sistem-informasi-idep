# Plan for Completing `edit.blade.php` for Kegiatan

This plan outlines the necessary changes to ensure the `edit.blade.php` view correctly loads and displays all saved data for a "Kegiatan" (Activity) record, while maintaining its existing dynamic functionality.

## 1. Controller Modifications (`app/Http/Controllers/Admin/KegiatanController.php`)

The `edit` method in the `KegiatanController` needs to be updated to ensure all related data is eagerly loaded and passed to the view.

### Changes:

- **Eager Load Relationships**: Modify the `edit` method to eager load all necessary relationships for the `$kegiatan` object. This will prevent multiple database queries and ensure all data is available in the view.

```php
public function edit(Kegiatan $kegiatan)
{
    $kegiatan->load(
        'programoutcomeoutputactivity.program_outcome_output.program_outcome.program',
        'jeniskegiatan',
        'sektor',
        'mitra',
        'lokasi.desa.kecamatan.kabupaten.provinsi',
        'kegiatan_penulis.peran', // Changed from 'penulis.peran' to 'kegiatan_penulis.peran'
        // Add specific activity relationships based on jenis_kegiatan
        'assessment',
        'sosialisasi',
        'pelatihan',
        'pembelanjaan',
        'pengembangan',
        'kampanye',
        'pemetaan',
        'monitoring',
        'kunjungan',
        'konsultasi',
        'lainnya'
    );

    // ... existing code
}
```

## 2. Blade File Updates (`resources/views/tr/kegiatan/edit.blade.php`)

The `edit.blade.php` file needs to be updated to correctly populate all form fields with the loaded data.

### Changes:

- **Basic Information**:
    - **Jenis Kegiatan**: Ensure the `jeniskegiatan_id` select field is correctly populated with the saved value.
    - **Sektor Kegiatan**: Ensure the `sektor_id` multi-select field is correctly populated with the saved values.
    - **Mitra**: Ensure the `mitra_id` multi-select field is correctly populated with the saved values.
- **Description**:
    - **Latar Belakang, Tujuan, Keluaran**: Populate the `summernote` text areas with the corresponding data from the `$kegiatan` object.
    - **Peserta**: Populate the participant count fields with the saved data.
- **Lokasi**:
    - **Provinsi, Kabupaten, Kecamatan, Desa**: Ensure the location select fields are correctly populated with the saved data.
    - **Latitude & Longitude**: Populate the latitude and longitude input fields with the saved data.
- **Penulis**:
    - **Penulis & Jabatan**: Ensure the "Penulis" and "Jabatan" select fields are correctly populated for each author.
- **File Uploads**:
    - **Dokumen & Media**: Display the list of already uploaded files with options to view or delete them.

## 3. JavaScript Enhancements

The JavaScript code within `edit.blade.php` needs to be enhanced to handle the pre-selected data and ensure all dynamic features work correctly.

### Changes:

- **Select2 Initialization**:
    - **Static Dropdowns**: Initialize the "Status" and "Fase Pelaporan" dropdowns as standard Select2 elements without AJAX.
    - **Dynamic Dropdowns**: For dropdowns that load data via AJAX (e.g., "Mitra", "Sektor"), ensure they are correctly initialized and can handle pre-selected values.
- **Location Fields**:
    - **Pre-population**: When the page loads, the script should automatically populate the "Kabupaten", "Kecamatan", and "Desa" dropdowns based on the pre-selected "Provinsi".
    - **Dynamic Loading**: Ensure that changing the "Provinsi" or "Kabupaten" correctly updates the dependent dropdowns.
- **Penulis Fields**:
    - **Dynamic Rows**: Ensure that the "Add Penulis" button continues to work correctly and that new rows are initialized with the appropriate Select2 fields.
- **File Uploads**:
    - **Existing Files**: Implement the logic to display existing files and handle their deletion via AJAX.

## 4. Analysis of `create.blade.php` and its Dynamic Functionalities

The `create.blade.php` view and its associated JavaScript provide a dynamic and user-friendly interface for creating new "Kegiatan" records. The following is a breakdown of its key functionalities:

-   **Basic Information Tab:**
    *   **Program Code & Name (`program_id`, `kode_program`, `nama_program`):** These fields are read-only and populated from a modal (`ModalDaftarProgramActivity`). `program_id` is a hidden field.
    *   **Activity Code & Name (`programoutcomeoutputactivity_id`, `kode_kegiatan`, `nama_kegiatan`):** Similar to program fields, these are read-only and populated from the same modal. `programoutcomeoutputactivity_id` is a hidden field.
    *   **Jenis Kegiatan (`jeniskegiatan_id`):** A Select2 dropdown that fetches data from `api.kegiatan.jenis_kegiatan`. This field is crucial as it dynamically generates the "Hasil Kegiatan" form.
    *   **Sektor Kegiatan (`sektor_id[]`):** A multi-select Select2 dropdown that fetches data from `api.kegiatan.sektor_kegiatan`.
    *   **Fase Pelaporan (`fasepelaporan`):** A Select2 dropdown. Its value is automatically populated based on the selected activity, fetching the next available phase from `kegiatan/api/fase-pelaporan`.
    *   **Start Date (`tanggalmulai`) & End Date (`tanggalselesai`):** Date input fields.
    *   **Duration (`durasi`):** Read-only field that calculates the duration in days between `tanggalmulai` and `tanggalselesai`.
    *   **Nama Mitra (`mitra_id[]`):** A multi-select Select2 dropdown that fetches data from `api.kegiatan.mitra`.
    *   **Status (`status`):** A static Select2 dropdown with predefined options (draft, ongoing, completed, cancelled).
    *   **Location Fields (Provinsi, Kabupaten, Kecamatan, Desa, Lokasi, Lat, Long):**
        *   `provinsi_id`, `kabupaten_id`: Select2 dropdowns for selecting province and regency.
        *   Dynamic Location Rows: The "Tambah Lokasi" button adds new rows, each containing Select2 dropdowns for `kecamatan_id[]` and `kelurahan_id[]`, and input fields for `lokasi[]`, `lat[]`, and `long[]`. These dropdowns are dynamically populated based on the selected parent location.
        *   Map Integration: There's an included `_google_map.blade.php` for map functionality, likely for picking coordinates.

-   **Description Tab:**
    *   **Latar Belakang (`deskripsilatarbelakang`), Tujuan (`deskripsitujuan`), Keluaran (`deskripsikeluaran`):** These are Summernote text areas for rich text input.
    *   **Peserta (Beneficiaries):** A set of number input fields for `penerimamanfaatdewasaperempuan`, `penerimamanfaatdewasalakilaki`, etc., for different age groups and disability statuses. There are calculated total fields. The `calculateTotals()` function handles the summation.

-   **Hasil Kegiatan Tab (`#tab-hasil`):**
    *   This tab contains a `dynamic-form-container` div.
    *   The content of this div is dynamically generated by the `getFormFields(fieldPrefix)` JavaScript function based on the `jeniskegiatan_id` selected in the "Basic Information" tab.
    *   The `formFieldMap` object maps `jeniskegiatan_id` to a `fieldPrefix` (e.g., "assessment", "sosialisasi", "konsultasi").
    *   Each `fieldPrefix` has a predefined set of fields (textarea, radio, datetime-local, etc.) with their respective labels, names, and tooltips.
    *   Summernote is initialized for textarea fields within this dynamic form.

-   **File Uploads Tab (`#tab-file`):**
    *   **Dokumen Pendukung (`dokumen_pendukung[]`):** File input for documents (pdf, doc, docx, xls, xlsx, pptx). Uses `krajee-fileinput`.
    *   **Media Pendukung (`media_pendukung[]`):** File input for media (jpg, png, jpeg). Uses `krajee-fileinput`.

-   **Penulis Laporan Kegiatan Tab (`#tab-penulis`):**
    *   **Add Penulis Button (`#addPenulis`):** Dynamically adds new rows for authors.
    *   Each author row contains:
        *   **Penulis (`penulis[]`):** A Select2 dropdown for selecting a user.
        *   **Jabatan (`jabatan[]`):** A Select2 dropdown for selecting a role/position.
        *   A remove button (`.remove-penulis-row`).

-   **Form Submission:**
    *   The form is submitted via AJAX to `api.kegiatan.store`.
    *   Client-side validation is performed by `validateForm()` before submission, which calls various helper validation functions (`validasiProgramIDActivityID`, `validasiSingleSelect2`, `validasiMultipleSelect2`, `validasiLongLat`, `validasiPenulis`).
    *   SweetAlert2 is used for confirmation and displaying success/error messages.
    *   File uploads include progress tracking.

-   **LocalStorage (Present in `create.blade.php` but not strictly needed for `edit.blade.php`):**
    *   `saveFormDataToStorage()`: Saves form data to `localStorage`.
    *   `loadFormDataFromStorage()`: Loads form data from `localStorage`. This is commented out in the provided `create.blade.php` snippet, but the functions are there. This is typically used for draft saving.

### Implementation in `edit.blade.php`

To ensure consistency and a seamless user experience, all of the dynamic functionalities from the `create.blade.php` view will be implemented in the `edit.blade.php` view. This includes:

-   **Dynamic "Hasil Kegiatan" Form**: The `edit.blade.php` view will also dynamically generate the "Hasil Kegiatan" form based on the selected "Jenis Kegiatan". This will involve copying the `getFormFields` function and `formFieldMap` (and related logic) from `tr.kegiatan.js.create` and adapting it to pre-populate with existing data from the `$kegiatan` object.
-   **Pre-populated Dynamic Fields**: All dynamic fields, including the location and author fields, will be pre-populated with the saved data when the page loads. This is mostly done, but participant fields need to be checked.
-   **File Uploads with Existing Files**: The file upload fields will display the list of already uploaded files, with options to view or delete them. This is already implemented.
-   **Consistent Validation**: The same validation rules from the `create.blade.php` view will be applied to the `edit.blade.php` view. This is largely handled by the included `_validasi.blade.php`.