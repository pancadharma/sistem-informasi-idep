<script>
$(document).ready(function() {
    $('.select2').select2();
    $('#provinsi_id').select2({
        placeholder: "{{ trans('global.selectProv')}}",
        allowClear: true,
    });

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
        ajax: "{{ route('data.dusun') }}",
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
                width: "15%",
                className: "text-left"
            },
            {
                data: "desa.nama", // Update to match the server-side column name
                // name: 'dusun.nama',
                width: "15%",
                className: "text-left"
            },
            {
                data: "kode_pos", // 
                // name: 'dusun.nama',
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
                width: "10%",
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
                            columns: [0, 1, 2, 3] // Ensure these indices match your visible columns
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
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
            [2, 'asc'] // Ensure this matches the index of the `dusun` column
        ],
        lengthMenu: [5, 10 ,25, 50, 100, 200],
    });

    //Validation Form Submit
    $('#submit_dusun').on('submit', function(e) {
        e.preventDefault();
        
    });

    function removeInvalidClass() {
        $(this).removeClass('is-invalid');
        let value_id = $(this).val();        
    }
    $('#provinsi_id').on('change', removeInvalidClass);
    $('#kabupaten_id').on('change', removeInvalidClass);
    $('#kecamatan_id').on('change', removeInvalidClass);
    $('#desa_id').on('change', removeInvalidClass);
    $('#kode').on('input', removeInvalidClass);
    $('#nama').on('input', removeInvalidClass);

    // $('#kode, #nama').on('input', function() {
    //     $(this).removeClass('is-invalid');
    // });

    const validator = $('#submit_dusun').validate({
        rules: {
            nama: { required: true, minlength: 3 },
            kode: { required: true, minlength: 16, maxlength: 16 },
            provinsi_id: { required: true },
            kabupaten_id: { required: true },
            kecamatan_id: { required: true },
            desa_id: { required: true }
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
            desa_id: "{{ trans('cruds.dusun.validation.des') }}"
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

    function reset_selet2(){
        
    }

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

    $('#provinsi_id').change(function(){
        var provinsi_id = $(this).val();
        if(provinsi_id){
            $.ajax({
                type: 'GET',
                url:  '{{ route('api.kab', ':id') }}'.replace(':id', provinsi_id),
                method: 'GET',
                dataType: 'json',
                success: function(hasil) {
                    $("#kabupaten_id").empty();
                    $("#kecamatan_id").val('').trigger('change');
                    $('#kabupaten_id').html(`<option value="0" selected>{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}</option>`);
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

    $('#kabupaten_id').change(function(){
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
                    $("#kecamatan_id").empty();
                    $('#kecamatan_id').html(`<option value="0">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}</option>`);
                    if(hasil){
                        $.each(hasil, function(index, item) {
                            $("#kecamatan_id").append('<option value="' + item.id + '" data-id="'+item.kode+'">' + item.text + '</option>');
                        });
                    }
                    else{
                        $("#kecamatan_id").empty();
                    }
                }
            });
        }
        if(kode_kab !== undefined ){
            // $('#kode').val(kode_kab+'.');
            // $('#kode').prop("title", "please input 4 digit additional village code");
            return;
        }else{
            $('#kode').val('');
            $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}");
        }
    });


});


































</script>