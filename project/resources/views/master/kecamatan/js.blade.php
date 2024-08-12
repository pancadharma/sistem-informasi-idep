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
            if(kode_kab != undefined ){
                $('#kode').val(kode_kab+'.');
                $('#kode').prop("title", "add two more of kode kecamatan");
            }else{
                $('#kode').val('');
                $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}");
            }
        });

        //load data kecamatan into datatables
        $('#kecamatan_list').DataTable({
            responsive: true,
            ajax: "{{ route('data.kecamatan') }}",
            processing: true,
            serverSide: true,
            stateSave: true, //to remember last position of data table browsed page
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
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" alt="☑️" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' +
                                row.id + '"></label></div>';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
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
                        'colvis',
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
    });
    
    // submit button add kecamatan
    $(document).ready(function(){
        $('#provinsi_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
        });

        //submit data kecamatan
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
        //edit kecamatan 
        $('#kecamatan_list tbody').on('click', '.edit-kec-btn', function(e){
            e.preventDefault();
            let kecamatan_id = $(this).data('kecamatan-id');
            let action = $(this).data('action');
            let url_update = '{{ route('kecamatan.update', ':id') }}'.replace(':id', kecamatan_id); //change form action url 

            $.ajax({
                url: '{{ route('kecamatan.edit', ':id') }}'.replace(':id', kecamatan_id),
                method: 'GET',
                dataType: 'json',
                success: function(hasil){
                    $('#editnama').val(hasil.kecamatan.nama);
                    $('#editkode').val(hasil.kecamatan.kode);
                    $('#kabupaten_id').val(hasil.kecamatan.kabupaten_id).trigger('change');
                    $('#provinsi_id').val(hasil.kecamatan.provinsi_id).trigger('change');
                    $('#editaktif').prop('checked', hasil.kecamatan.aktif == 1);
                    $('#edit-aktif').val(hasil.kecamatan.aktif);

                    $('#provinsi_id').empty();
                    $.each(hasil.provinsi, function(key, value) {
                        $('#provinsi_id').append('<option value="'+ value.id +'">'+ value.nama +'</option>');
                    });
                    // Set the selected provinsi
                    $('#provinsi_id').val(hasil.kecamatan.provinsi_id).trigger('change');

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

            // Toast.fire({
            //     icon: "success",
            //     title: "Your Click is Successs",
            // });
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
        })
    });
    //validation
    $(document).ready(function() {
        $('#kode, #nama, #kabupaten_id').on('input', function(){
            var kode = $('#kode').val();
            var kabupaten_id = $('#kabupaten_id').val();
            var nama = $('#nama').val();
            if(kode != null || kode !== ''){
                $('#kabupaten_id-error').text('').hide();
            }
            if(kabupaten_id != null && kabupaten_id !== ''){
                $('#kode-error').text('').hide();
            }
            if(nama != null && nama !== ''){
                $('#nama-error').text('').hide();
            } else {
                // $('#nama-error').text('Nama is required').show();
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
        $('#nama').on('input', function() {
            var value = $(this).val();
            var regex = /^[^\d][a-zA-Z\s]{2,}$/;
            if (!regex.test(value)) {
                $('#nama_error').show();
            } else {
                $('#nama_error').hide();
            }
        });
    });
</script>