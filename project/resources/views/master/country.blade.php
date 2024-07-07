@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Dashboard') {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', 'Dashboard') {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb','') {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

{{-- Content body: main page content --}}

@section('content_body')

@can('country_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('country.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.country.title_singular') }}
            </a>
        </div>
    </div>
@endcan

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ trans('cruds.country.list') }}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Country">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.country.nama') }}</th>
                            <th>{{ trans('cruds.country.iso1') }}</th>
                            <th>{{ trans('cruds.country.iso2') }}</th>
                            <th>{{ trans('cruds.country.flag') }}</th>
                        </tr>
                    </thead>
                    {{--<tbody>
                     @foreach($countries as $key => $country)
                        <tr data-entry-id="{{ $country->id }}">
                            <td></td>
                            <td>{{ $country->nama }}</td>
                            <td>{{ $country->iso2 }}</td>
                            <td>{{ $country->iso3 }}</td>
                            <td>
                                <img src="images/flag/{{ $country->flag }}"></td>                            
                            </td>
                        </tr>
                    @endforeach
                    </tbody> --}}
                </table>
            </div>
        <div class="card-footer">
            The footer of the card
        </div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    {{-- call custom plugins js so not all pages load the JS --}}
    @section('plugins.Datatables', true) 
    {{-- @section('plugins.Select2', true) --}}
    {{-- @section('plugins.DateRangePicker', true) --}}
    <script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('country_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('country.massDestroy') }}",
            className: 'btn-danger',
        action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                    return entry.id
                });
        
                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
            
                    return
                }
        
                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                    headers: {'x-csrf-token': _token},
                    method: 'POST',
                    url: config.url,
                    data: { ids: ids, _method: 'DELETE' }})
                    .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan
    
        let dtOverrideGlobals = {
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('country.index') }}",
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'nama', name: 'nama' },
                { data: 'iso1', name: 'iso1' },
                { data: 'iso2', name: 'iso2' },
                { data: 'flag', name: 'flag', sortable: false, searchable: false },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 2, 'asc' ]],
            pageLength: 100,
        };
        let table = $('.datatable-Country').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
      
    });
    </script>
@endpush