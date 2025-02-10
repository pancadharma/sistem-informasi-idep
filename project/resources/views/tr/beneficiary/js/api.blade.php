<script>
function initializeDisabilityDropdown() {
    // Load data from API or database using a promise
    fetch('/api/options') // Replace with your API endpoint
        .then(response => response.json())
        .then(data => {
            // Initialize Select2 for add modal
            $('.select2-multiple').select2({
                data: data,
                dropdownParent: $('#ModalTambahPeserta'),
                width: '100%'
            });

            // Initialize Select2 for edit modal
            $('#editKelompokRentan').select2({
                data: data,
                dropdownParent: $('#editDataModal'),
                width: '100%'
            });
        })
        .catch(error => console.error('Error loading data:', error));
}

function loadDisabilityOptions() {
    // Load data from API or database using AJAX
    $.ajax({
        type: 'GET',
        url: '/api/options', // Replace with your API endpoint
        dataType: 'json',
        success: function(data) {
            // Initialize Select2 for add modal
            $('.select2-multiple').select2({
                data: data,
                dropdownParent: $('#ModalTambahPeserta'),
                width: '100%'
            });

            // Initialize Select2 for edit modal
            $('#editKelompokRentan').select2({
                data: data,
                dropdownParent: $('#editDataModal'),
                width: '100%'
            });
        }
    });
}

























</script>
