<script>
$(document).ready(function() {
    $('.select2').select2();
    $('#provinsi_id').select2({
        placeholder: "{{ trans('global.selectProv')}}",
        allowClear: true,
    });
    $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title')}}");

    //autoformat kode dusun
    $('#kode').on('input', function() {
        let input = $(this).val();
        input = input.replace(/\D/g, ''); // Remove all non-digit characters

        if (input.length > 2) {
            input = input.substring(0, 2) + '.' + input.substring(2);
        }
        if (input.length > 4) {
            input = input.substring(0, 5) + '.' + input.substring(5);
        }
        if (input.length > 6) {
            input = input.substring(0, 8) + '.' + input.substring(8);
        }
        if (input.length > 10) {
            input = input.substring(0, 13) + '.' + input.substring(13);
        }
        if (input.length > 15) {
            input = input.substring(0, 16);
        }
        $(this).val(input);
    });

    //Dusun DataTables
    $('#dusun_list').DataTable({
        responsive: true,
        ajax: {
            url: "{{ route('data.dusun') }}",
            method: 'GET',
            dataType: 'JSON',
        },
        processing: true,
        serverSide: true,
        deferRender: true,
        stateSave: true,
        columns: [
            {
                    data: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center align-middle", width: "5%",
            },
            {
                data: "kode",
                // name: 'kelurahan.kode', //needed when using alternative query
                width: "15%",
                className: "text-center align-middle",
                orderable: false
            },
            {
                data: "nama",
                // name: 'kelurahan.nama',
                width: "25%",
                className: "text-left align-middle"
            },
            {
                data: "desa.nama", // Update to match the server-side column name
                // name: 'dusun.nama',
                width: "20%",
                className: "text-lef align-middle"
            },
            {
                data: "kode_pos", //
                // name: 'dusun.nama',
                width: "10%",
                className: "text-left align-middle"
            },
            {
                data: "aktif",
                // name: 'kelurahan.aktif',
                width: "10%",
                className: "text-center align-middle",
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data === 1) {
                        return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id +
                            '" class="icheck-primary" alt="☑️ aktif" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' + row.id + '"></label></div>';
                    } else {
                        return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id +
                            '" class="icheck-primary" alt="not-aktif" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox"><label for="aktif_' +
                            row.id + '"></label></div>';
                    }
                }
            },
            {
                data: "action",
                width: "15%",
                className: "text-center align-middle",
                orderable: false,
            }
        ],
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'print', text: `<i class="fas fa-print"></i>`, titleAttr: "Print Table Data",
                        exportOptions: {
                            stripHTML: false,
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 5) {
                                        return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                    }
                                    return data;

                                }
                            },
                            columns: [0, 1, 2, 3,4,5] // Ensure these indices match your visible columns
                        }
                    },
                    {
                        extend: 'excelHtml5', text: `<i class="far fa-file-excel"></i>`, titleAttr: "Export to EXCEL", className: "btn-success",
                        exportOptions: {
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 5) {
                                        return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                    }
                                    return data;

                                }
                            },
                            columns: [0, 1, 2, 3,4,5]
                        }
                    },
                    {
                        extend: 'pdfHtml5', text: `<i class="far fa-file-pdf"></i>`, titleAttr: "Export to PDF", className: "btn-danger",
                        exportOptions: {
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 5) {
                                        return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                    }
                                    return data;

                                }
                            },
                            columns: [0, 1, 2, 3,4,5]
                        }
                    },
                    {
                        extend: 'copy', text: `<i class="fas fa-copy"></i>`, titleAttr: "Copy",
                        exportOptions: {
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 5) {
                                        return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                    }
                                    return data;
                                }
                            },
                            columns: [0, 1, 2, 3,4,5]
                        }
                    },
                    {extend: 'colvis', text: `<i class="fas fa-eye"></i><span class="d-none d-md-inline">Column visibility</span>`, titleAttr: "Select Visible Column", className: "btn-warning"},
                ],
            },
        },      
        order: [
            [2, 'asc'] // Ensure this matches the index of the `dusun` column
        ],
        lengthMenu: [5, 10 ,25, 50, 100, 200],
    });

    //Validation Form Submit
    $('#submit_dusun').on('submit', function(e) {
        e.preventDefault();
        // Check if the form is valid
        if (!$(this).valid()) {
            return; // Stop the form submission if validation fails
        }
        let kode_desa = $('#desa_id').children('option:selected').data('id');
        let formData = $(this).serialize();
        formData += '&kode_desa=' + kode_desa;
        let url = $(this).attr('action');

        console.log(formData);

        $.ajax({
            url: url,
            dataType: "JSON",
            method: "POST",
            data: formData,
            beforeSend: function(){
                Toast.fire({
                    icon: "info",
                    title: "Processing...",
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                },
                success: function(hasil){
                    if(hasil.success === true){
                        Swal.fire({
                            title: "Success",
                            text: hasil.message,
                            icon: "success",
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        $("#kabupaten_id").empty();
                        $("#kecamatan_id").empty();
                        $("#desa_id").empty();
                        $('#nama').removeClass('is-valid');
                        $('#kode').removeClass('is-valid');
                        $('#kode_pos').removeClass('is-valid');
                        $(".btn-tool").trigger('click');
                        $('#submit_dusun').trigger('reset');
                        $('#dusun_list').DataTable().ajax.reload();
                        resetForm();
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
                                    $(`#${field}-error`).removeClass('is-valid').addClass('is-invalid');
                                    $(`#${field}-error`).text(message);
                                    $(`#${field}`).removeClass('invalid').addClass('is-invalid');
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
    });
    // call edit form & view form in single script
    $('#dusun_list tbody').on('click','.edit-dusun-btn, .view-dusun-btn', function(e){
        e.preventDefault();
        let dusunID     = $(this).data('dusun-id');
        let action      = $(this).data('action');
        let url         = '{{ route('dusun.edit', ':id') }}'.replace(':id',dusunID);
        let show        = '{{ route('dusun.show', ':id') }}'.replace(':id',dusunID);
        let form_url    = '{{ route('dusun.update', ':id') }}'.replace(':id', dusunID);

        if(action === "edit"){
            $.getJSON(url, function(hasil){
                Toast.fire({
                    icon: "info",
                    title: "Processing...",
                    timer: 200,
                    timerProgressBar: true,
                });

                let provID = hasil.dusun.desa.kecamatan.kabupaten.provinsi_id;
                let kabID = hasil.dusun.desa.kecamatan.kabupaten.id;
                let kecID = hasil.dusun.desa.kecamatan.id;
                let desaID = hasil.dusun.desa.id;
                let dusunID = hasil.dusun.id;
                let dusunKode = hasil.dusun.kode;
                let dusunNama = hasil.dusun.nama;
                let pos = hasil.dusun.kode_pos;
                let aktifVal = hasil.dusun.aktif;
                console.log(provID, kabID, kecID,desaID);
                resetForm();
                $('#id_dusun').val(dusunID);
                $("#EditDusunForm").trigger('reset');
                $("#EditDusunForm").attr('action', form_url);
                $('#editaktif').prop('checked', aktifVal === 1);
                $('#edit-aktif').val(aktifVal);
                $('#provinsi').empty();
                $.each(hasil.provinsi, function(key, value) {
                    let selected = (value.id === provID) ? 'selected' : '';
                        $('#provinsi').append('<option data-id="'+value.kode+'" value="'+ value.id +'" '+ selected +'>'+ value.text +'</option>');
                });
                $('#kabupaten').empty();
                    $.each(hasil.kabupaten, function(key, value) {
                        let selected = (value.id === kabID) ? 'selected' : '';
                        $('#kabupaten').append('<option data-id="'+value.kode+'" value="'+ value.id +'" '+ selected +'>'+ value.text +'</option>');
                    });
                $('#kecamatan').empty();
                    $.each(hasil.kecamatan, function(key, value) {
                        let selected = (value.id === kecID) ? 'selected' : '';
                        $('#kecamatan').append('<option data-id="'+value.kode+'" value="'+ value.id +'" '+ selected +'>'+ value.text +'</option>');
                    });
                $('#desa').empty();
                    $.each(hasil.desa, function(key, value) {
                        let selected = (value.id === desaID) ? 'selected' : '';
                        $('#desa').append('<option data-id="'+value.kode+'" value="'+ value.id +'" '+ selected +'>'+ value.text +'</option>');
                    });
                $('#kode_dusun').val(dusunKode);
                $('#nama_dusun').val(dusunNama);
                $('#postcode').val(pos);
                $('#editDesaModal').modal('show');

            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                alert("Error: " + textStatus + " - " + errorThrown);
                Swal.fire({
                    text: textStatus,
                    message: "Failed to fetch data "+ errorThrown,
                    icon: "error"
                });
            })
        }
        if(action === "view"){
            Toast.fire({icon: "info",title: "Processing...",timer: 200,timerProgressBar: true,});
            $.getJSON(show, function(data){
                $("#show-kode").text(data.kode);
                $("#show-nama").text(data.nama);
                $("#show-desa").text(data.desa.nama);
                $("#show-kode_pos").text(data.kode_pos);
                if (data.aktif === 1) {
                    $('#show-aktif').val(data.aktif);
                    $("#show-aktif").prop("checked",true);
                } else {
                    $('#show-aktif').val(0);
                    $("#show-aktif").prop("checked",false);
                }

                $('#DusunModalShow').modal('show');
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                alert("Error: " + textStatus + " - " + errorThrown);
                Swal.fire({text: textStatus,message: "Failed to fetch data "+ errorThrown,icon: "error"});
            })
            .always(function(){
                //
            });
        }
    });
    //Update Data
    $('#EditDusunForm').on('submit', function(e){
        e.preventDefault();
        if (!$(this).valid()) {
            return;
        }
        let kode_desa = $('#desa').children('option:selected').data('id');
        let form = $(this);
        let action = form.attr('action');
        let dusunID = $('#id_dusun').val();
        let url = '{{ route('dusun.update', ':id') }}'.replace(':id', dusunID);
        let formData = form.serialize();
        formData += '&kode_desa=' + kode_desa;

        $.ajax({
            url: url,
            method: 'PUT',
            data: formData,
            dataType: 'json',
            beforeSend: function(){
                Toast.fire({
                    icon: "info",
                    title: "Processing...",
                    timer: 1500,
                    timerProgressBar:true,
                    didOpen: ()=>{
                        Swal.showLoading();
                    },
                })
            },
            success: function(response) {
                if(response.success === true){
                    Swal.fire({
                        title: "Updated",
                        text: response.message,
                        icon: "success",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: ()=>{
                            Swal.showLoading();
                        },
                    });
                }
                $('#dusun_list').DataTable().ajax.reload();
                $('#editDesaModal').modal('hide');
                resetForm();
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
                                $(`#${field}-error`).removeClass('is-valid').addClass('is-invalid');
                                $(`#${field}-error`).text(message);
                                $(`#${field}`).removeClass('invalid').addClass('is-invalid');
                            });
                        });
                        errorMessage += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                        });
                    }

                    if (response.message) {
                        errorMessage = response.message;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                        });
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

    function removeInvalidClass() {
        $(this).removeClass('is-invalid');
        let value_id = $(this).val();
    }
    $('#provinsi_id, #provinsi').on('change', removeInvalidClass);
    $('#kabupaten_id, #kabupaten').on('change', removeInvalidClass);
    $('#kecamatan_id, #kecamatan').on('change', removeInvalidClass);
    $('#desa_id, #desa').on('change', removeInvalidClass);
    $('#kode, #kode_dusun').on('input', removeInvalidClass);
    $('#nama, #nama_dusun').on('input', removeInvalidClass);
    $('#kode_pos, #postcode').on('input', removeInvalidClass);

    //validasi form submit dusun
    $(function(){
        const addDusunValidator = $('#submit_dusun').validate({
            rules: {
                nama: { required: true, minlength: 3 },
                kode: { required: true, minlength: 16, maxlength: 16 },
                provinsi_id: { required: true },
                kabupaten_id: { required: true },
                kecamatan_id: { required: true },
                desa_id: { required: true },
                },
            messages: {
                nama: {
                    required: "{{ trans('cruds.dusun.validation.req_nama') }}",
                    minlength: "{{ trans('cruds.dusun.validation.min_nama') }}"
                },
                kode: {
                    required: "{{ trans('cruds.dusun.validation.kode') }}",
                    minlength: "{{ trans('cruds.dusun.validation.min_kode') }}",
                    maxlength: "{{ trans('cruds.dusun.validation.max_kode') }}"
                },
                provinsi_id: "{{ trans('cruds.dusun.validation.prov') }}",
                kabupaten_id: "{{ trans('cruds.dusun.validation.kab') }}",
                kecamatan_id: "{{ trans('cruds.dusun.validation.kec') }}",
                desa_id: "{{ trans('cruds.dusun.validation.des') }}",
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            }
        });
        const editDusunValidator = $('#EditDusunForm').validate({
            rules: {
                nama_dusun: { required: true, minlength: 3 },
                kode_dusun: { required: true, minlength: 16, maxlength: 16 },
                provinsi: { required: true },
                kabupaten: { required: true },
                kecamatan: { required: true },
                desa: { required: true }
            },
            messages: {
                nama_dusun: {
                    required: "{{ trans('cruds.dusun.validation.req_nama') }}",
                    minlength: "{{ trans('cruds.dusun.validation.min_nama') }}"
                },
                kode_dusun: {
                    required: "{{ trans('cruds.dusun.validation.kode') }}",
                    minlength: "{{ trans('cruds.dusun.validation.min_kode') }}",
                    maxlength: "{{ trans('cruds.dusun.validation.max_kode') }}"
                },
                provinsi: "{{ trans('cruds.dusun.validation.prov') }}",
                kabupaten: "{{ trans('cruds.dusun.validation.kab') }}",
                kecamatan: "{{ trans('cruds.dusun.validation.kec') }}",
                desa: "{{ trans('cruds.dusun.validation.des') }}"
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            }
        });
    });

    //reset form
    function resetForm() {
        $('#edit_dusun, #submit_dusun')[0].reset();
        $('.form-group').find('.is-invalid').removeClass('is-invalid');
        $('.form-group').find('.is-valid').removeClass('is-valid');
        $('.form-group').find('.invalid-feedback').text(''); // Clear text in error spans
    }

    //UpperCase Input Nama Form
    function capitalizeWords(str) {
        return str.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }
    $('#nama, #editnama').on('input', function() {
        let input = $(this).val();
        if (/^\d{1,3}/.test(input)) {
            input = input.replace(/^\d{1,3}/, '');
        }
        input = capitalizeWords(input);
        $(this).val(input);
    });
    $('#show-aktif, #aktif_').click(function(event) {
        event.preventDefault();
    });

    //select provinsi
    $('#provinsi_id, #provinsi').change(function(){
        var provinsi_id = $(this).val();
        if(provinsi_id){
            $.ajax({
                type: 'GET',
                url:  '{{ route('api.kab', ':id') }}'.replace(':id', provinsi_id),
                method: 'GET',
                dataType: 'json',
                success: function(hasil) {
                    $("#kabupaten_id, #kabupaten").empty();
                    $('#kabupaten_id, #kabupaten').html(`<option value="000" selected>{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}</option>`);
                    $('#kecamatan_id, #kecamatan').html(`<option value="000">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}</option>`);
                    $('#desa_id, #desa').html(`<option value="000">{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title')}}</option>`);
                    $('#kode, #kode_dusun').val('');
                    // $('#postcode, #kode_pos').val('');
                    if(hasil){
                        $.each(hasil, function(index, item) {
                            $("#kabupaten_id, #kabupaten").append('<option value="' + item.id + '" data-id="'+item.kode+'">' + item.text + '</option>');
                        });
                    }
                    else{
                        $("#kabupaten_id, #kabupaten").empty();
                    }
                }
            });
        }
        $('#kabupaten_id, #kabupaten').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
        });
    });
    //select kabupaten
    $('#kabupaten_id, #kabupaten').change(function(){
        var selected = $(this).find('option:selected');
        var kode_kab = selected.data('id');
        var kab_id   = selected.val();
        console.log(kab_id);
        if(kab_id){
            $.ajax({
                type: 'GET',
                url:  '{{ route('api.kec', ':id') }}'.replace(':id', kab_id),
                method: 'GET',
                dataType: 'json',
                success: function(hasil) {
                    $("#kecamatan_id, #kecamatan").empty();
                    $('#kecamatan_id, #kecamatan').html(`<option value="0">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}</option>`);
                    $("#desa_id, #desa").empty();
                    $('#desa_id, #desa').html(`<option value="000">{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title')}}</option>`);
                    $('#kode, #kode_dusun').val('');
                    if(hasil){
                        $.each(hasil, function(index, item) {
                            $("#kecamatan_id, #kecamatan").append('<option value="' + item.id + '" data-id="'+item.kode+'">' + item.text + '</option>');
                        });
                    }
                    else{
                        $("#kecamatan_id, #kecamatan").empty();
                    }
                }
            });
        }
        if(kode_kab !== undefined ){
            $('#kode, #kode_dusun').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title')}}");
            return;
        }else{
            $('#kode, #kode_dusun').val('');
        }
    });
    //select kecamatan
    $('#kecamatan_id, #kecamatan').on('change', function() {
        var selectedProvinsi = $('#provinsi_id').val();
        var selectedKab = $('#kabupaten_id').val();
        var selectedKec = $(this).val();
        $('#desa_id, #desa').html(`<option value="000">{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title')}}</option>`);
        console.log(selectedKec);
        if (selectedKec) {
            $.getJSON('{{ route('api.desa', '') }}/' + selectedKec, function(hasil){
                $('#desa_id, #desa').empty();
                $('#desa_id, #desa').append(new Option(`{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title')}}`, 0));
                $.each(hasil, function(index, desa) {
                    $("#desa_id, #desa").append('<option value="' + desa.id + '" data-id="'+desa.kode+'">' + desa.text + '</option>');
                });
                $('#kode, #kode_dusun').val('');
            }).fail(function() {
                Toast.fire({
                    icon: "error",
                    title: "Failed to fetch data",
                    timer: 1500,
                    timerProgressBar: true,
                });
            });
        }
    });
    // select desa then fill kode dusun
    $('#desa_id, #desa').on('change', function(){
        var kodeDusun   = $(this).find('option:selected').data('id');

        if(kodeDusun !== undefined){
            $('#kode, #kode_dusun').val(kodeDusun+'.');
        }
    });

    $('#kode_pos, #postcode').on('input', function(e){
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 5) {
            this.value = this.value.slice(0, 5);
        }
        const errorElement = $('#kodepos_kurang');
        if (this.value.length < 5) {
                errorElement.text('Postal code must be exactly 5 digits.');
                this.setCustomValidity('Postal code must be exactly 5 digits.');
        } else if (this.value.length > 5) {
            errorElement.text('Postal code cannot be more than 5 digits.');
            this.setCustomValidity('Postal code cannot be more than 5 digits.');
        } else {
            errorElement.text('');
            this.setCustomValidity('');
        }
    });

    $('#editaktif').change(function() {
        $('#edit-aktif').val(this.checked ? 1 : 0);
    });

});


































</script>
