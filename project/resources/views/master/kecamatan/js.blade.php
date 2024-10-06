<script>
    //prevent checkbox clicked in show modal 
    $('#show-aktif, #aktif_').click(function(event) {
        event.preventDefault();
    });
    // call datatable for kecamatan
    $(document).ready(function() {
        $('#provinsi_id').change(function(){
            var provinsi_id = $(this).val();
            if(provinsi_id){
                $.ajax({
                    type: 'GET',
                    url:  '{{ route('kab.data', ':id') }}'.replace(':id', provinsi_id),
                    method: 'GET',
                    dataType: 'json',
                    success: function(hasil) {
                        $("#kabupaten_id").empty();
                        $('#kabupaten_id').html(`<option value="0">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}</option>`);
                        if(hasil){
                            $.each(hasil, function(index, item) {
                                $("#kabupaten_id").append('<option value="' + item.id + '" data-id="'+item.kode+'">' + item.text + '</option>');
                            });
                        }
                        else{
                            $("#kabupaten_id").empty();
                        }
                    }
                });
            }            
            $('#kabupaten_id').select2({
                placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            });
        });
        
        //autofill kecamatan_kode with kabupatan_kode
        $('#kabupaten_id, #provinsi_id').change(function(){
            var selected = $(this).find('option:selected');
            var kode_kab = selected.data('id');
            if(kode_kab !== undefined ){
                $('#kode').val(kode_kab+'.');
                $('#kode').prop("title", "add two more of kode kecamatan");
            }else{
                $('#kode').val('');
                $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}");
            }
        });

        //load data kecamatan into datatables
        $('#kecamatan_list').DataTable({
            ajax: "{{ route('data.kecamatan') }}",
            responsive: true,lengthChange: false,
            processing: true,autoWidth: false,serverSide: true,deferRender: true, stateSave: true,
            columns: [
                {
                    data: "kode",
                    width: "5%",
                    className: "text-center"
                },
                {
                    data: "nama",
                    width: "5%",
                    className: "text-left"
                },
                {
                    data: 'kabupaten.nama',
                    width: "15%",
                    className: "text-left"
                },
                {
                    data: "aktif",
                    width: "5%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' +row.id+ '" data-aktif-id="' + row.id +
                                '" class="icheck-primary" alt="☑️" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' +row.id+ '"></label></div>';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' +row.id+ '" data-aktif-id="' + row.id +
                                '" class="icheck-primary" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox" ><label for="aktif_' +
                                row.id + '"></label></div>';
                        }
                    }
                },
                {
                    data: "action",
                    width: "8%",
                    className: "text-center",
                    orderable: false,
                }
            ],
             layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },{
                            extend: 'pdf', 
                            exportOptions: {
                                columns: [0, 1, 2]
                            }    
                        },{
                            extend: 'copy',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline">Column visibility</span>',
                            className: 'btn btn-warning',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                    ],
                },
                bottomStart: {
                    pageLength: 5,
                }
            },
            order: [
                [2, 'asc']
            ],
            lengthMenu: [5, 25, 50, 100, 500],
        });

        $('#provinsi_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#edit_provinsi_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
            allowClear: true,
            delay: 250,
            dropdownParent: $('#editKecamatanModal')
        });
        $('#edit_kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
            dropdownParent: $('#editKecamatanModal')
        });
        $('#kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
        });       
    });
    
    // submit button add kecamatan
    $(document).ready(function(){
        // add / submit data kecamatan
        $('#kecamatanForm').on('submit', function(e){
            e.preventDefault();
            var kabupaten_kode = $('#kabupaten_id').children('option:selected').data('id');
            var form_kecamatan = $('#kecamatanForm').serialize();
            form_kecamatan += '&kabupaten_kode=' + kabupaten_kode;

            $.ajax({
                url: '{{ route('kecamatan.store') }}',
                method: 'POST',
                data: form_kecamatan,
                dataType: 'JSON',
                beforeSend: function() {
                    $('.btn-add-kecamatan').prop('disabled', true);
                },
                complete: function() {
                    $('.btn-add-kecamatan').prop('disabled', false);
                },
                success: function(response){
                    if (response.success === true) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        $(".btn-tool").trigger('click');
                        $('#kecamatanForm').trigger('reset');
                        $("#provinsi_id").select2({
                            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
                            allowClear: true,
                            delay: 250,
                        });
                        $("#kabupaten_id").empty();
                        $('#kecamatan_list').DataTable().ajax.reload();
                    }else{
                        Swal.fire({
                            title: "Something Wrong !",
                            text: response.message,
                            icon: "error",
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            }
                        });
                    }
                },
                error: function(xhr, status, errors){
                    var response = JSON.parse(xhr.responseText);
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    $('.invalid-feedback').prop('display:block!important', true);
                    if (response.errors) {
                        if (response.errors.kabupaten_id) {
                            $('#kabupaten_id-error').text(response.errors.kabupaten_id.join(', ')).show();
                        }
                        if (response.errors.kode) {
                            $('#kode-error').text(response.errors.kode.join(', ')).show();
                        }
                        if (response.errors.nama) {
                            $('#nama-error').text(response.errors.nama.join(', ')).show();
                        }
                    }
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                        if (response.errors) {
                            const errors = response.errors;
                            errorMessage += '<br><br><ul style="text-align:left!important">';
                            for (const field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    errors[field].forEach(err => {
                                        errorMessage += `<li>${field}: ${err}</li>`;
                                    });
                                }
                            }
                            errorMessage += '</ul>';
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                    });
                }
            });
        });

        function checkValidity(field) {
            var $field = $(field);
            var nama = $('#editnama');
            if (!$field.val() || $field[0].checkValidity() === false) {
                $field.addClass('is-invalid').removeClass('is-valid');
                return false;
            } else {
                $field.addClass('is-valid').removeClass('is-invalid');
                return true;
            }
        }
        $('#editKecamatanForm').find('input, select, textarea').on('input change', function() {
            checkValidity(this);
        });
        //Submit Update Kecamatan Modal Form
        $('#editKecamatanForm').on('submit', function(e){
            e.preventDefault();
            $('#editaktif').change(function() {
                $('#edit-aktif').val(this.checked ? 1 : 0);
            });
            var formIsValid = true;
            $(this).find('input, select, textarea').each(function() {
                if (!checkValidity(this)) {
                    formIsValid = false;
                }
            });

            let kabupaten_kode = $('#edit_kabupaten_id').children('option:selected').data('id');
            let formData = $(this).serialize();
                formData += '&kabupaten_kode=' + kabupaten_kode;
            let idKec = $('#id').val();
            let url_update = '{{ route('kecamatan.update', ':kec') }}'.replace(':kec', idKec);
            
            if (formIsValid) {
            $.ajax({
                url: url_update,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success === true ) {
                        Swal.fire({
                            title: response.data.nama,
                            text: response.message,
                            icon: 'success',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            }
                        });
                        $('#editKecamatanModal').modal('hide');
                        $(this).trigger('reset');
                        $('#kecamatan_list').DataTable().ajax.reload();
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Failed to Update",
                            position: 'top-end',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            }
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const errorData = JSON.parse(jqXHR.responseText);
                    const errors = errorData.errors; // Access the error object
                    let errorMessage = "";
                    for (const field in errors) {
                        errors[field].forEach(error => {
                            errorMessage +=
                            `* ${error}\n`; // Build a formatted error message string
                        });
                    }
                    Swal.fire({
                        title: jqXHR.statusText,
                        text: errorMessage,
                        icon: 'error'
                    });
                    return false;
                }
            });
            return true;
        } else {
            e.preventDefault();
        }//valid

        });

        //edit kecamatan modal show
        $('#kecamatan_list tbody').on('click', '.edit-kec-btn', function(e){
            e.preventDefault();
            let kecamatan_id = $(this).data('kecamatan-id');
            let action = $(this).data('action');
            let url_update = '{{ route('kecamatan.update', ':id') }}'.replace(':id', kecamatan_id);
            $.ajax({
                url: '{{ route('kecamatan.edit', ':id_kec') }}'.replace(':id_kec', kecamatan_id),
                method: 'GET',
                dataType: 'json',
                success: function(hasil){
                    $("#editKecamatanForm").trigger('reset');
                    $("#editKecamatanForm").attr("action", url_update);
                    $('#id').val(hasil.kecamatan.id);
                    $('#editnama').val(hasil.kecamatan.nama);
                    $('#editkode').val(hasil.kecamatan.kode);
                    $('#editaktif').prop('checked', hasil.kecamatan.aktif == 1);
                    $('#edit-aktif').val(hasil.kecamatan.aktif);
                    $('#edit_provinsi_id').empty();
                    $.each(hasil.provinsi, function(key, value) {
                        $('#edit_provinsi_id').append('<option value="'+ value.id +'">'+value.kode+' - '+ value.nama +'</option>');
                    });
                    $('#edit_provinsi_id').val(hasil.kecamatan.kabupaten.provinsi_id).trigger('change');
                    let data = hasil.kabupaten.map(function(item) {
                        return {
                            id: item.id,
                            text: item.kode+' - '+ item.nama,
                        };
                    });
                    $('#edit_kabupaten_id').select2({
                        dropdownParent: $('#editKecamatanForm'),
                        data: data,
                        placeholder: "{{ trans('global.select_type') }} {{ trans('cruds.kabupaten.title') }} / {{ trans('cruds.kabupaten.kota') }}",
                    });
                    $('#edit_kabupaten_id').val(hasil.kecamatan.kabupaten.id).trigger('change');
                    loadKab(hasil.kecamatan.kabupaten.provinsi_id, hasil.kecamatan.kabupaten.id);
                    $('#editKecamatanModal').modal('show'); // Show the modal
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const errorData = JSON.parse(jqXHR.responseText);
                    const errors = errorData.errors; // Access the error object
                    let errorMessage = "";
                    for (const field in errors) {
                        errors[field].forEach(error => {
                            errorMessage +=
                            `* ${error}\n`; // Build a formatted error message string
                        });
                    }
                    Swal.fire({
                        title: jqXHR.statusText,
                        text: errorMessage,
                        icon: 'error'
                    });
                }
            });
        });

        $('#edit_provinsi_id').on('change',function(){
            var provinsiID = $(this).val();
            var kab_load = $("#edit_kabupaten_id").find('option:selected');
            var kab_load2 = $("#edit_kabupaten_id").data('id');
            var KabKode = kab_load.data('id');

            if(KabKode !== undefined && $("#edit_kabupaten_id").prop('selectedIndex') > 0){
                loadKab(provinsiID);
                $('#editkode').html('');
                $('#editkode').empty();
                $('#editkode').val(provinsiID+'.');
            }else{
                // console.log('Expected Kode is ', kab_load2);
                return
            }
        });

        $('#edit_kabupaten_id').on('change', function() {
            changeKabKode();
        });

        function changeKabKode() {
            var kabupatenElem = $("#edit_kabupaten_id");
            var selectedOption = $("#edit_kabupaten_id").find('option:selected');
            var KabKode = selectedOption.data('id');
            let KabValue = kabupatenElem.val();

            if(kabupatenElem.length && kabupatenElem.prop('selectedIndex') > 0 && KabKode !== undefined){
                $('#editkode').html('');
                $('#editkode').empty();
                $('#editkode').val(KabKode+'.');
                console.log(KabKode);
            }
            return;
        }

        function loadKab(provinsiID, selectedKabupatenId){
            if(provinsiID){
                $.ajax({
                    type: 'GET',
                    url:  '{{ route('kab.data', ':id') }}'.replace(':id', provinsiID),
                    method: 'GET',
                    dataType: 'json',
                    success: function(hasil) {
                        $("#edit_kabupaten_id").empty();
                        $('#edit_kabupaten_id').html(`<option value="">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}</option>`);
                        if(hasil){
                            $.each(hasil, function(index, item) {
                                $("#edit_kabupaten_id").append('<option value="' + item.id + '" data-id="'+item.kode+'">' + item.text + '</option>');
                            });
                            $('#edit_kabupaten_id').val(selectedKabupatenId).trigger('change.select2');
                        }
                        else{
                            $("#edit_kabupaten_id").empty();
                        }
                    },
                    error:function(xhr){
                        Swal.fire({
                            title: "Something Wrong !",
                            text: xhr.message,
                            icon: "error",
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            }
                        });
                        console.log("failed to load kabupaten data");
                    }
                });
            }
        }
    });
    //validation
    $(document).ready(function() {
        $('#kode, #nama, #kabupaten_id').on('input', function(){
            var kode = $('#kode').val();
            var kabupaten_id = $('#kabupaten_id').val();
            var edit_kabupaten_id = $('#edit_kabupaten_id').val();
            var nama = $('#nama').val();
            var editnama = $('#editnama').val();
            if(kode != null || kode !== ''){
                $('#kabupaten_id-error').text('').hide();
            }
            if(kabupaten_id != null && kabupaten_id !== '' || edit_kabupaten_id != null && edit_kabupaten_id !== ''){
                $('#kode-error').text('').hide();
            }
            if(nama != null && nama !== '' || editnama != null && editnama !== ''){
                $('#nama-error').text('').hide();
            } else {
                return true;
            }
        });
        $('#kode').on('input', function() {
            var value = $(this).val();
            var regex_kode = /^[0-9.]*$/;
            if (!regex_kode.test(value)) {
                $('#kode_error').show();
                $(this).val(value.slice(0, -1)); // Remove the last character
            } else {
                $('#kode_error').hide();
            }
        });
        $('#nama, #editnama').on('input', function() {
            var value = $(this).val();
            var regex = /^[^\d][a-zA-Z\s]{2,}$/;
            if (!regex.test(value)) {
                $('#nama_error').show();
            } else {
                $('#nama_error').hide();
            }
        });
       
        //show kecamatan
        $('#kecamatan_list tbody').on('click', '.view-kec-btn', function(e){
            e.preventDefault();
            let kecamatanId = $(this).data('kecamatan-id');
            let action = $(this).data('action');

            $.ajax({
                url: '{{ route('kecamatan.show', ':id') }}'.replace(':id',
                kecamatanId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response || [];
                    if (action === 'view') {
                        $("#show-kode").text(data.kode);
                        $("#show-nama").text(data.nama);
                        $("#show-kabupaten").text(data.kabupaten.nama);
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked",true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked",false); // Set checked to false if value is not 1
                        }
                        $('#showKecamatanModal').modal('show');
                        Toast.fire({
                            icon: "success",
                            title: "Data Loaded",
                            position: 'bottom-end',
                        });
                    } else {
                        Swal.fire({
                            text: "Error",
                            message: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred during data fetch',
                        icon: 'error'
                    });
                }
            });
            return false;
        });
    });
</script>