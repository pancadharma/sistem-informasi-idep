<script>
    $(document).ready(function() {
        //load data desa into datatables
        $('#desa_list').DataTable({
            responsive: true,
            ajax: "{{ route('data.desa') }}",
            type: "GET",
            processing: true,
            serverSide: true,
            deferRender: true,
            stateSave: true,
            columns: [
                {
                    data: "kode",
                    // name: 'kelurahan.kode', //needed when using alternative query
                    width: "5%",
                    className: "text-center",
                    orderable: false
                },
                {
                    data: "nama",
                    // name: 'kelurahan.nama',
                    width: "5%",
                    className: "text-left"
                },
                { 
                    // data: "kecamatan_nama", // Update to match the server-side column name
                    data: "kecamatan.nama", // Update to match the server-side column name
                    // name: 'kecamatan.nama',
                    width: "15%", 
                    className: "text-left"
                },
                {
                    data: "aktif",
                    // name: 'kelurahan.aktif',
                    width: "5%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="' + row.id +
                                '" class="icheck-primary" alt="☑️" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' + row.id + '"></label></div>';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="' + row.id +
                                '" class="icheck-primary" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox"><label for="aktif_' +
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
                                columns: [0, 1, 2] // Ensure these indices match your visible columns
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'copy',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        'colvis',
                    ],
                },
                bottomStart: {
                    pageLength: 5,
                }
            },
            order: [
                [2, 'asc'] // Ensure this matches the index of the `kecamatan_nama` column
            ],
            lengthMenu: [5, 25, 50, 100, 500],
        });

        //trigger provinsi when it changes
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
        //autofill desa_kode with kabupatan_kode
        $('#provinsi_id, #kabupaten_id').change(function(){
            var selected = $(this).find('option:selected');
            var kode_kab = selected.data('id');
            var kab_id   = selected.val();

            if(kab_id){
                $.ajax({
                    type: 'GET',
                    url:  '{{ route('kec.data', ':id') }}'.replace(':id', kab_id),
                    method: 'GET',
                    dataType: 'json',
                    success: function(hasil) {
                        $("#kecamatan_id").empty();
                        $('#kecamatan_id').html(`<option value="0">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}</option>`);
                        if(hasil){
                            $.each(hasil, function(index, item) {
                                $("#kecamatan_id").append('<option value="' + item.id + '" data-id="'+item.kode+'">' + item.text + '</option>');
                            });
                        }
                        else{
                            $("#kabupaten_id").empty();
                            $("#kecamatan_id").empty();
                        }
                    }
                });
            }
            if(kode_kab !== undefined ){
                $('#kode').val(kode_kab+'.');
                $('#kode').prop("title", "please input 4 digit additional village code");
            }else{
                $('#kode').val('');
                $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}");
            }
        });

        $('#kecamatan_id').change(function(){
            var selected = $(this).find('option:selected');
            var kode_kec = selected.data('id');
            console.log('koded kec: ',kode_kec);

            if(kode_kec !== undefined || kode_kec != ''){
                $('#kode').val(kode_kec+'.');
                $('#kode').prop("title", "add two more of kode kecamatan");
            }else{
                $('#kode').val('');
                $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}");
            }
        });

        // VALIDATION CHECK
        function validateKode(value) {
            // Regex untuk format 15.05.04.xxxx
            const regex = /^\d{2}\.\d{2}\.\d{2}\.\d{4}$/;
            return regex.test(value);
        }
        function validateNama(value) {
            return value.length >= 3;
        }
        function checkValidity() {
            let fields = ['#provinsi_id', '#kabupaten_id', '#kecamatan_id', '#nama', '#kode'];
            let isValid = true;
            fields.forEach(function(field) {
                let $field = $(field);
                let value = $field.val();

                if ($field.hasClass('select2-hidden-accessible')) {
                    if (!value) {
                        $field.next('.select2-container').addClass('is-invalid').removeClass('is-valid');
                        isValid = false;
                    } else {
                        $field.next('.select2-container').addClass('is-valid').removeClass('is-invalid');
                    }
                } else {
                    if (!value || value.trim() === '') {
                        $field.addClass('is-invalid').removeClass('is-valid');
                        isValid = false;
                    } else {
                        // Specific validation for 'kode'
                        if (field === '#kode' && value.length !== 13) {
                            $field.addClass('is-invalid').removeClass('is-valid');
                            isValid = false;
                        } else {
                            $field.addClass('is-valid').removeClass('is-invalid');
                        }
                        const kodeInput = document.getElementById('kode');
                        if (validateKode(kodeInput.value)) {
                            kodeInput.classList.add('is-valid');
                            kodeInput.classList.remove('is-invalid');
                        } else {
                            kodeInput.classList.add('is-invalid');
                            kodeInput.classList.remove('is-valid');
                        }
                        const namaInput = document.getElementById('nama');
                        if (!validateNama(namaInput.value)) {
                            document.getElementById('nama_error').textContent = 'Required. Min data is 3 characters.';
                            namaInput.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            namaInput.classList.remove('is-invalid');
                            namaInput.classList.add('is-valid');
                        }

                    }
                }
            });
            return isValid;
        }
        $('#submit_desa').find('input, select, textarea').on('input change', function() {
            checkValidity();
        });

        // SUBMIT DESA DATA
        $('#submit_desa').on('submit',function(e){
            e.preventDefault();            
            let kecamatan_kode = $('#kecamatan_id').children('option:selected').data('id');
            let formData = $(this).serialize();
            formData += '&kecamatan_kode=' + kecamatan_kode;
            let url = $(this).attr('action');
          
            var formIsValid = true;

            $(this).find('input, select, textarea').each(function() {
                if (!checkValidity(this)) {
                    formIsValid = false;
                }
            });

            if(formIsValid){
                $.ajax({
                    url: '{{ route('desa.store') }}',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    beforeSend: function(){
                        Toast.fire({
                            icon: "info",
                            title: "Processing...",
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response){
                        if(response.success === true){
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
                            $("#kabupaten_id").empty();
                            $("#kecamatan_id").empty();
                            $('#submit_desa').trigger('reset');
                            $('#desa_list').DataTable().ajax.reload();
                            $('#nama').removeClass('is-valid');
                            $('#kode').removeClass('is-valid');
                            $(".btn-tool").trigger('click');
                        }
                        $("#provinsi_id").select2({
                            placeholder: "{{ trans('global.pleaseSelect') .' '. trans('cruds.provinsi.title')}}",
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                        try {
                            const response = xhr.responseJSON;
                            if (response.errors) {
                                errorMessage += '<br><br><ul style="text-align:left!important">';
                                $.each(response.errors, function(field, messages) {
                                    messages.forEach(message => {
                                        errorMessage += `<li>${field}: ${message}</li>`;
                                        $(`#${field}-error`).text(message).removeClass('is-valid').addClass('is-invalid');
                                        $(`#${field}`).removeClass('invalid').addClass('is-invalid');
                                        console.log(mess);
                                    });
                                });
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
            }


        });


        //View Kelurahan / Desa

        $('#desa_list tbody').on('click', '.view-kec-btn', function(e){
            e.preventDefault();
            let desaId = $(this).data('desa-id');
            let action = $(this).data('action');
            let url = '{{ route('desa.show', ':id') }}'.replace(':id',desaId);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                beforeSend: function(){
                    Toast.fire({
                        icon: "info",
                        title: "Processing...",
                        timer: 1500,
                        timerProgressBar: true,
                    });
                },
                success: function(response){
                    let data = response || [];
                    if (action === 'view') {
                        $("#show-kode").text(data.kode);
                        $("#show-nama").text(data.nama);
                        $("#show-kecamatan").text(data.kecamatan.nama);
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked",true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked",false); // Set checked to false if value is not 1
                        }
                        $('#showDesaModal').modal('show');
                    } else {
                        Swal.fire({
                            text: "Error",
                            message: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, errors){
                    var response = JSON.parse(xhr.responseText);
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
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


        $('#desa_list tbody').on('click', '.edit-kec-btn', function(e){
            e.preventDefault();
            let desaId = $(this).data('desa-id');
            let action = $(this).data('action');
            let url = '{{ route('desa.edit', ':id') }}'.replace(':id',desaId);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                beforeSend: function(){
                    Toast.fire({
                        icon: "info",
                        title: "Processing...",
                        timerProgressBar: true,
                    });
                },
                success: function(response){
                    let data = response || [];
                    if (action === 'view') {
                        $("#editkode").text(data.kode);
                        $("#editnama").text(data.nama);
                        $("#edit_kecamatan_id").text(data.kecamatan.nama);
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked",true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked",false); // Set checked to false if value is not 1
                        }
                        $('#editDesaModal').modal('show');
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, errors){
                    var response = JSON.parse(xhr.responseText);
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
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
        $('#kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#edit_kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#kecamatan_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#edit_kecamatan_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') .' '. trans('cruds.kecamatan.title')}}",
            allowClear: true,
            delay: 250,
        });

        $('#show-aktif, #aktif_').click(function(event) {
            event.preventDefault();
        });

        $('#aktif').change(function() {
            $('#aktif').val(this.checked ? 1 : 0);
        });
    });
</script>