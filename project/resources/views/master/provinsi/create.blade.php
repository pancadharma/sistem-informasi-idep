<x-adminlte-modal id="modalCustom" title="{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}" size="lg" theme="teal"
icon="fa fa-plus" v-centered static-backdrop scrollable>
{{-- <div style="height:800px;">Read the account policies...</div> --}}
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>