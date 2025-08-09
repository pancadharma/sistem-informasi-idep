# Analysis and Fix for "Penulis Kegiatan" Tab

## 1. Problem Analysis

The "Penulis Kegiatan" tab in `edit.blade.php` is currently non-functional. The core issues are:

1.  **No JavaScript Logic:** There is no client-side script to handle the dynamic aspects of the tab.
2.  **"Add Penulis" Button Inactive:** The button with `id="addPenulis"` has no associated click event listener to dynamically generate new input fields for an author and their role.
3.  **"Remove" Button Inactive:** The remove buttons (`.remove-penulis-row`) do not have a click event listener to delete a row.
4.  **Select2 Not Initialized:** The dropdowns for selecting a `penulis` (author) and `jabatan` (role) are standard `<select>` elements. They have not been initialized with the `Select2` library to provide search and dynamic loading capabilities via AJAX.
5.  **No Default Row:** If a `kegiatan` has no authors, the form does not present an initial empty row, forcing the user to click "Add" to begin. This is a minor usability issue.

## 2. Proposed Solution

To fix this, I will implement the following changes:

### A. HTML Modifications (`edit.blade.php`)

1.  **Add a Default Row:** I will modify the `@if` condition that checks for existing authors. In the `@else` block, I will insert the HTML for a single, empty author row. This ensures that even for new entries, one set of fields is immediately visible.

### B. JavaScript Implementation (within `@push('js')`)

I will add a new, self-contained `<script>` block at the end of the `@push('js')` section to encapsulate all the logic for this tab.

1.  **Define API Endpoints:** Store the API routes for fetching users and roles in constants for clarity.
    *   `penulisApiUrl`: `{{ route("api.kegiatan.users") }}`
    *   `peranApiUrl`: `{{ route("api.kegiatan.peran") }}`

2.  **Create an Initialization Function:**
    *   A function named `initializeRow(row)` will be created. It will take a jQuery object representing a `.penulis-row` as an argument.
    *   Inside this function, it will find the `.penulis-select` and `.jabatan-select` elements and initialize them as `Select2` dropdowns with the appropriate AJAX configurations pointing to the API endpoints.

3.  **Implement "Add Penulis" Functionality:**
    *   A click event handler will be attached to the `#addPenulis` button.
    *   When clicked, it will dynamically create the HTML for a new author row.
    *   This new HTML will be appended to the `#list_penulis_edit` container.
    *   The `initializeRow()` function will be called on the newly created row to make its Select2 dropdowns functional.

4.  **Implement "Remove Penulis" Functionality:**
    *   A delegated click event handler will be attached to the `#list_penulis_edit` container to listen for clicks on `.remove-penulis-row`.
    *   This handler will check if there is more than one author row present.
        *   If **yes**, it will remove the entire parent `.penulis-row`.
        *   If **no** (it's the last row), it will simply clear the values of the `penulis` and `jabatan` Select2 dropdowns, preventing the user from removing all input fields.

5.  **Initialize Existing Rows on Page Load:**
    *   A loop will iterate over all `.penulis-row` elements that are present when the page loads.
    *   The `initializeRow()` function will be called for each of these existing rows to ensure their pre-populated data and Select2 instances are correctly configured.

This comprehensive approach will result in a fully functional, dynamic, and user-friendly interface for managing the authors of a `kegiatan`.


<div class="row penulis-row col-12">
    <div class="col-lg-5 form-group mb-0">
        <label for="penulis">{{ __('cruds.kegiatan.penulis.nama') }}</label>
        <div class="select2-orange">
            <select class="form-control select2 penulis-select" name="penulis[]"></select>
        </div>
    </div>
    <div class="col-lg-5 form-group d-flex align-items-end">
        <div class="flex-grow-1">
            <label for="jabatan">{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
            <div class="select2-orange">
                <select class="form-control select2 jabatan-select" name="jabatan[]"></select>
            </div>
        </div>
        <div class="ml-2">
            <button type="button" class="btn btn-danger remove-penulis-row">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</div>
