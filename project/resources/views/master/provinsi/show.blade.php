<x-adminlte-modal id="showProvinceModal" title="{{ trans('global.show') }} {{ trans('cruds.provinsi.title_singular') }}" size="lg" theme="success" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>{{ __('cruds.provinsi.kode') }}</th>
                    <td id="show-kode"></td>
                </tr>
                <tr>
                    <th>{{ __('cruds.provinsi.nama') }}</th>
                    <td id="show-nama"> </td>
                </tr>
                <tr>
                    <th>{{ __('cruds.status.title') }}</th>
                    <td><div class="icheck-primary d-inline"><input type="checkbox" id="show-status"><label for="show-status"></label></div></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <x-slot name="footerSlot">
        {{--        <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>--}}
        {{--        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>--}}
    </x-slot>
</x-adminlte-modal>

@push('js')
<script>
    $(document).ready(function() {
        $('#provinsi tbody').on('click', '.view-province-btn', function(e) {
            e.preventDefault(); // Prevent default form submission
            let provinceId = $(this).data('province-id');
            let action = $(this).data('action')

            // console.log(provinceId);
            $.ajax({
                url: '{{ route('provinsi.show', ':id') }}'.replace(':id', provinceId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function (response){
                    
                    let data = response || [];

                    if (action === 'view') {
                        $("#show-kode").text(data.kode);
                        $("#show-nama").text(data.nama);
                        $("#show-aktif").prop("checked", data.aktif === 1);
                        $('#showProvinceModal').modal('show');
                } else {
                    swal.fire({
                        text: "Error",
                        message: "Failed to fetch data",
                        icon: "error"
                    });
                }
                }
            })
        });
    });
</script>
@endpush