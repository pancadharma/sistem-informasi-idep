$(document).ready(function() {
    // Load existing form data from localStorage on page load
    loadFormDataFromStorage();
    loadParticipantsFromStorage();

    // Save form data to localStorage whenever an input changes
    $('#pesertaForm input, #pesertaForm select').on('change', function() {
        saveFormDataToStorage();
    });

    // Function to save form data to localStorage
    function saveFormDataToStorage() {
        var formData = {
            identitas: $('#identitas').val(),
            no_kk: $('#no_kk').val(),
            nama: $('#nama').val(),
            jenis_kelamin: $('#jenis_kelamin').val(),
            tanggal_lahir: $('#tanggal_lahir').val(),
            disabilitas: $('#disabilitas').val(),
            hamil: $('#hamil').val(),
            status_kawin: $('#status_kawin').val(),
            jenis_peserta: $('#jenis_peserta').val(),
            nama_kk: $('#nama_kk').val()
        };

        localStorage.setItem('pesertaFormData', JSON.stringify(formData));
    }

    // Function to load form data from localStorage
    function loadFormDataFromStorage() {
        var savedFormData = localStorage.getItem('pesertaFormData');
        if (savedFormData) {
            var formData = JSON.parse(savedFormData);
            
            // Populate form fields
            $('#identitas').val(formData.identitas || '');
            $('#no_kk').val(formData.no_kk || '');
            $('#nama').val(formData.nama || '');
            $('#jenis_kelamin').val(formData.jenis_kelamin || '');
            $('#tanggal_lahir').val(formData.tanggal_lahir || '');
            $('#disabilitas').val(formData.disabilitas || '0');
            $('#hamil').val(formData.hamil || '0');
            $('#status_kawin').val(formData.status_kawin || '');
            $('#jenis_peserta').val(formData.jenis_peserta || '');
            $('#nama_kk').val(formData.nama_kk || '');
        }
    }

    // Existing functions from previous implementation (saveParticipantsToStorage, 
    // loadParticipantsFromStorage, etc.) remain the same

    // Optional: Clear stored form data
    function clearStoredFormData() {
        localStorage.removeItem('pesertaFormData');
        localStorage.removeItem('participantsData');
    }

    // Optional: Add a clear button or method to reset stored data
    $('#clearStorageButton').on('click', function() {
        clearStoredFormData();
        $('#pesertaForm')[0].reset(); // Reset form to default
        $('#tableBody').empty(); // Clear table
    });

    // Modify existing save function to clear form data after saving
    $('#saveModalData').click(function() {
        // Existing save logic remains the same
        
        // Clear stored form data after successful save
        localStorage.removeItem('pesertaFormData');
    });
});