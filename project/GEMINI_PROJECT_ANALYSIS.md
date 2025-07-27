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