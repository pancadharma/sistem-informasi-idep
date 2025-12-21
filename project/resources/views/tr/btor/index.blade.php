{{-- @extends('tr.btor.layouts.master')

@section('title', 'BTOR Report List') --}}
@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.list'))
@section('content_header_title')
    &nbsp;
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

@section('content_body')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2>BTOR - Back to Office Report</h2>
            <p class="text-muted">Manage and export activity reports</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('btor.index') }}" id="filterForm">
                {{-- Row 1: Program, Kegiatan, Jenis Kegiatan --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Program</label>
                            <select name="program_id" id="program_id" class="form-control" style="width: 100%">
                                <option value="">Pilih Program</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kegiatan</label>
                            <select name="activity_id" id="activity_id" class="form-control select2" style="width: 100%">
                                <option value="">Pilih Semua</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jenis Kegiatan</label>
                            <select name="jeniskegiatan_id" id="jeniskegiatan_id" class="form-control select2" style="width: 100%">
                                <option value="">Pilih Semua</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Status, Search Button --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control select2" style="width: 100%">
                                <option value="">Pilih Semua</option>
                                <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="ongoing" {{ ($filters['status'] ?? '') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary" id="search-btn">
                                    Search
                                </button>
                                <a href="{{ route('btor.index') }}" class="btn btn-secondary ml-2">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Bulk Actions --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('btor.export.bulk') }}" id="bulkForm">
                @csrf
                <input type="hidden" name="ids" id="selectedIds">
                <button type="submit" class="btn btn-danger" id="bulkExportBtn" disabled>
                    <i class="fas fa-file-pdf"></i> Export Selected to PDF
                </button>
                <button type="button" class="btn btn-secondary" id="bulkPrintBtn" disabled>
                    <i class="fas fa-print"></i> Print Selected
                </button>
                {{-- <a href="{{ route('btor.export.config') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a> --}}
                <span id="selectedCount" class="ml-3 text-muted">0 items selected</span>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40px">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th width="60px">ID</th>
                            <th>Activity Name</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th width="200px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatanList as $kegiatan)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="select-item" value="{{ $kegiatan->id }}">
                                </td>
                                <td>{{ $kegiatan->id }}</td>
                                <td>
                                    <strong>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $kegiatan->jenisKegiatan?->nama ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($kegiatan->tanggalmulai)
                                        {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $kegiatan->getDurationInDays() }} days</td>
                                <td>
                                    <span class="badge badge-{{ $kegiatan->status === 'completed' ? 'success' : ($kegiatan->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($kegiatan->status ?? 'draft') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('btor.show', $kegiatan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i> 
                                    </a>
                                    <a href="{{ route('btor.print', $kegiatan->id) }}" class="btn btn-sm btn-secondary" target="_blank" title="Print Preview">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a href="{{ route('btor.export.pdf', $kegiatan->id) }}" class="btn btn-sm btn-danger" title="Download PDF">
                                        <i class="fas fa-file-pdf"></i> 
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i> No reports available
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>

    $('#search-btn').click(function(e) {
        e.preventDefault();
        let programId = $('#program_id').val();
        if (!programId) {
            e.preventDefault();
            Toast.fire({
                icon: "warning",
                title: "Opssss...",
                text: "Please select a program first.",
                timer: 2000,
                position: "top-end",
                timerProgressBar: true,
            });

            return false;

        } else {
            //do submit
            $('#filterForm').submit();
        }
    });


    $(function() {
        // API URLs
        const programsUrl = "{{ route('btor.api.programs') }}";
        const kegiatanUrl = "{{ route('btor.api.kegiatan') }}";
        const jenisKegiatanUrl = "{{ route('btor.api.jenis_kegiatan') }}";

        // Old filter values from URL
        const oldProgramId = "{{ $filters['program_id'] ?? '' }}";
        const oldActivityId = "{{ $filters['activity_id'] ?? '' }}";
        const oldJenisId = "{{ $filters['jeniskegiatan_id'] ?? '' }}";

        // Initialize Program Select2 with AJAX
        function initProgramSelect() {
            $('#program_id').select2({
                // placeholder: 'Choose...',
                allowClear: true,
                ajax: {
                    url: programsUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: true
                }
            });

            // Restore old value if any
            if (oldProgramId) {
                $.ajax({
                    url: programsUrl,
                    dataType: 'json',
                    success: function(data) {
                        var found = data.results.find(p => p.id == oldProgramId);
                        if (found) {
                            var option = new Option(found.text, found.id, true, true);
                            $('#program_id').append(option).trigger('change');
                        }
                    }
                });
            }
        }

        // Initialize Kegiatan (Activity) Select2 with AJAX
        function initKegiatanSelect() {
            $('#activity_id').select2({
                // placeholder: 'Choose...',
                allowClear: true,
                ajax: {
                    url: kegiatanUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            program_id: $('#program_id').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: false
                }
            });

            // Restore old value if any
            if (oldActivityId) {
                $.ajax({
                    url: kegiatanUrl,
                    data: { program_id: oldProgramId },
                    dataType: 'json',
                    success: function(data) {
                        var found = data.results.find(k => k.id == oldActivityId);
                        if (found) {
                            var option = new Option(found.text, found.id, true, true);
                            $('#activity_id').append(option).trigger('change');
                        }
                    }
                });
            }
        }


        // Initialize Jenis Kegiatan Select2 with AJAX
        function initJenisKegiatanSelect() {
            $('#jeniskegiatan_id').select2({
                // placeholder: 'Choose...',
                allowClear: true,
                ajax: {
                    url: jenisKegiatanUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            activity_id: $('#activity_id').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: false
                }
            });

            // Restore old value if any
            if (oldJenisId) {
                $.ajax({
                    url: jenisKegiatanUrl,
                    data: { activity_id: oldActivityId },
                    dataType: 'json',
                    success: function(data) {
                        var found = data.results.find(j => j.id == oldJenisId);
                        if (found) {
                            var option = new Option(found.text, found.id, true, true);
                            $('#jeniskegiatan_id').append(option).trigger('change');
                        }
                    }
                });
            }
        }

        // Status Select2
        $('#status').select2({
            // placeholder: 'Choose...',
            allowClear: true
        });

        // Initialize all selects
        initProgramSelect();
        initKegiatanSelect();
        initJenisKegiatanSelect();

        // When Program changes, reset Kegiatan and Jenis Kegiatan
        $('#program_id').on('change', function() {
            $('#activity_id').val(null).trigger('change');
            $('#jeniskegiatan_id').val(null).trigger('change');
        });

        // When Activity changes, reload Jenis Kegiatan options
        $('#activity_id').on('change', function() {
            var activityId = $(this).val();
            
            // Reset jenis kegiatan and reload options
            $('#jeniskegiatan_id').val(null).trigger('change');
            
            // If activity is selected, fetch available jenis kegiatan
            if (activityId) {
                $.ajax({
                    url: jenisKegiatanUrl,
                    data: { activity_id: activityId },
                    dataType: 'json',
                    success: function(data) {
                        // If only one jenis kegiatan, auto-select it
                        if (data.results.length === 1) {
                            var jenis = data.results[0];
                            var option = new Option(jenis.text, jenis.id, true, true);
                            $('#jeniskegiatan_id').empty().append(option).trigger('change');
                        }
                    }
                });
            }
        });
    });

    // Select All Checkbox
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    // Individual Checkboxes
    document.querySelectorAll('.select-item').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Update Bulk Actions
    function updateBulkActions() {
        const selected = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.value);
        const count = selected.length;

        document.getElementById('selectedCount').textContent = count + ' item(s) selected';
        document.getElementById('bulkExportBtn').disabled = count === 0;
        document.getElementById('bulkPrintBtn').disabled = count === 0;
        document.getElementById('selectedIds').value = selected.join(',');
    }

    // Bulk Form Submit (Export PDF)
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        const count = document.querySelectorAll('.select-item:checked').length;
        if (count === 0) {
            e.preventDefault();
            Toast.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal 1 laporan untuk diekspor.',
                timer: 2000,
                position: 'top-end',
                timerProgressBar: true
            });
        }
    });

    // Bulk Print - Open single page with all selected reports
    document.getElementById('bulkPrintBtn').addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.value);
        
        if (selected.length === 0) {
            Toast.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal 1 laporan untuk dicetak.',
                timer: 2000,
                position: 'top-end',
                timerProgressBar: true
            });
            return;
        }

        // Confirm before opening print preview
        Swal.fire({
            title: 'Print ' + selected.length + ' Laporan?',
            text: 'Akan membuka 1 tab dengan semua laporan yang dipilih.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Buka Print Preview',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Open single page with all selected reports
                const bulkPrintUrl = '{{ route("btor.print.bulk") }}';
                const url = bulkPrintUrl + '?ids=' + selected.join(',');
                window.open(url, '_blank');
                
                Toast.fire({
                    icon: 'success',
                    title: 'Print Preview',
                    text: 'Membuka print preview untuk ' + selected.length + ' laporan...',
                    timer: 2000,
                    position: 'top-end',
                    timerProgressBar: true
                });
            }
        });
    });
</script>
@endpush

