### 2025-07-28: Centralized File Upload Scripts for Kegiatan Create/Edit Pages

- **Task**: Refactored file upload logic to a new Blade file (`_file_upload_scripts.blade.php`) to ensure consistency between `create.blade.php` and `edit.blade.php` for Kegiatan module.
- **Changes**:
    - Created `resources/views/tr/kegiatan/js/_file_upload_scripts.blade.php` containing Krajee FileInput initialization and common AJAX submission logic for file uploads.
    - Modified `resources/views/tr/kegiatan/create.blade.php` to include the new centralized file upload script.
    - Modified `resources/views/tr/kegiatan/edit.blade.php` to remove redundant Krajee FileInput script includes and `_dropzone_scripts.blade.php` inclusion, and instead include the new centralized file upload script.
- **Impact**: Both create and edit pages for Kegiatan now utilize the same file upload mechanism, ensuring consistent behavior and easier maintenance.

### 2025-07-28: Confirmed Centralized File Upload Scripts for Kegiatan Create/Edit Pages

- **Task**: Verified that both `create.blade.php` and `edit.blade.php` for the Kegiatan module are now correctly using the centralized file upload script (`_file_upload_scripts.blade.php`).
- **Changes**: Confirmed the removal of old, redundant file upload script inclusions and the correct inclusion of the new centralized script in both files.
- **Impact**: Ensures consistent and maintainable file upload functionality across the Kegiatan creation and editing interfaces.

### 2025-07-28: Added Missing API Routes for Temporary File Uploads

- **Task**: Resolved "Route [api.kegiatan.upload_temp_file] not defined" error by adding missing API routes.
- **Changes**: Added `api.kegiatan.upload_temp_file` and `api.kegiatan.delete_temp_file` routes to `routes/api.php`, pointing to `uploadTempFile` and `deleteTempFile` methods in `App\Http\Controllers\Admin\KegiatanController`.
- **Impact**: Enabled proper functioning of temporary file upload and deletion features used by the Krajee FileInput in Kegiatan create and edit forms.

### 2025-07-28: Centralized File Upload Script Inclusion in Create Page

- **Task**: Ensured `create.blade.php` consistently uses the centralized file upload script.
- **Changes**: Replaced direct Krajee FileInput script inclusions in `resources/views/tr/kegiatan/create.blade.php` with `@include('tr.kegiatan.js._file_upload_scripts')`.
- **Impact**: Further centralizes file upload logic, improving maintainability and consistency across the application.

### 2025-07-28: Confirmed File Upload Consistency in Create and Edit Pages

- **Task**: Re-analyzed `create.blade.php` (after restoration) and `edit.blade.php` to confirm consistent file upload implementation.
- **Changes**: Verified that both `create.blade.php` and `edit.blade.php` now directly include Krajee FileInput scripts and use the same methods for file uploads, including proper handling of `initialPreview` and `initialPreviewConfig` for existing files in `edit.blade.php`.
- **Impact**: Ensures full consistency and correct functionality of file uploads across both create and edit forms for Kegiatan.