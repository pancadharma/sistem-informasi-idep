@extends('layouts.app')

@section('subtitle', 'BTOR List')
@section('content_header_title', 'BTOR - Back to Office Report')
@section('sub_breadcumb', 'BTOR List')

@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)

@section('content_body')
<div class="container-fluid">
    {{-- Header --}}
    {{-- Filter Card --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('btor.index') }}" id="filterForm">
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
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="{{ route('btor.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-redo"></i> Reset
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
            <form method="POST" id="bulkFormPdf" action="{{ route('btor.export.bulk') }}">
                @csrf
                <input type="hidden" name="ids" id="selectedIdsPdf">
            </form>
            <form method="POST" id="bulkFormDocx" action="{{ route('btor.export.bulk.docx') }}">
                @csrf
                <input type="hidden" name="ids" id="selectedIdsDocx">
            </form>

            <div class="btn-group">
                <button type="button" class="btn btn-danger" id="bulkExportPdfBtn" disabled>
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </button>
                <button type="button" class="btn btn-primary" id="bulkExportDocxBtn" disabled>
                    <i class="fas fa-file-word"></i> Export to DOCX
                </button>
                <button type="button" class="btn btn-secondary" id="bulkPrintBtn" disabled>
                    <i class="fas fa-print"></i> Print Preview
                </button>
            </div>
            <span id="selectedCount" class="ml-3 text-muted">0 items selected</span>
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
                            <th width="250px">Actions</th>
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
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" title="Export">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('btor.export.pdf', $kegiatan->id) }}">
                                                <i class="fas fa-file-pdf text-danger"></i> PDF
                                            </a>
                                            <a class="dropdown-item" href="{{ route('btor.export.docx', $kegiatan->id) }}">
                                                <i class="fas fa-file-word text-primary"></i> DOCX
                                            </a>
                                        </div>
                                    </div>
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
    $(function() {
        const programsUrl = "{{ route('btor.api.programs') }}";
        const kegiatanUrl = "{{ route('btor.api.kegiatan') }}";
        const jenisKegiatanUrl = "{{ route('btor.api.jenis_kegiatan') }}";

        const oldProgramId = "{{ $filters['program_id'] ?? '' }}";
        const oldActivityId = "{{ $filters['activity_id'] ?? '' }}";
        const oldJenisId = "{{ $filters['jeniskegiatan_id'] ?? '' }}";

        // Initialize Select2
        $('#program_id').select2({
            allowClear: true,
            ajax: {
                url: programsUrl,
                dataType: 'json',
                delay: 250,
                data: params => ({ search: params.term, page: params.page || 1 }),
                processResults: data => ({ results: data.results, pagination: data.pagination }),
                cache: true
            }
        });

        $('#activity_id').select2({
            allowClear: true,
            ajax: {
                url: kegiatanUrl,
                dataType: 'json',
                delay: 250,
                data: params => ({ search: params.term, program_id: $('#program_id').val() }),
                processResults: data => ({ results: data.results, pagination: data.pagination }),
                cache: false
            }
        });

        $('#jeniskegiatan_id').select2({
            allowClear: true,
            ajax: {
                url: jenisKegiatanUrl,
                dataType: 'json',
                delay: 250,
                data: params => ({ search: params.term, activity_id: $('#activity_id').val() }),
                processResults: data => ({ results: data.results, pagination: data.pagination }),
                cache: false
            }
        });

        $('#status').select2({ allowClear: true });

        // Restore old values
        if (oldProgramId) {
            $.get(programsUrl, data => {
                const found = data.results.find(p => p.id == oldProgramId);
                if (found) $('#program_id').append(new Option(found.text, found.id, true, true)).trigger('change');
            });
        }

        if (oldActivityId) {
            $.get(kegiatanUrl, { program_id: oldProgramId }, data => {
                const found = data.results.find(k => k.id == oldActivityId);
                if (found) $('#activity_id').append(new Option(found.text, found.id, true, true)).trigger('change');
            });
        }

        if (oldJenisId) {
            $.get(jenisKegiatanUrl, { activity_id: oldActivityId }, data => {
                const found = data.results.find(j => j.id == oldJenisId);
                if (found) $('#jeniskegiatan_id').append(new Option(found.text, found.id, true, true)).trigger('change');
            });
        }

        // Cascade changes
        $('#program_id').on('change', () => {
            $('#activity_id, #jeniskegiatan_id').val(null).trigger('change');
        });

        $('#activity_id').on('change', function() {
            $('#jeniskegiatan_id').val(null).trigger('change');
            const activityId = $(this).val();
            if (activityId) {
                $.get(jenisKegiatanUrl, { activity_id: activityId }, data => {
                    if (data.results.length === 1) {
                        const jenis = data.results[0];
                        $('#jeniskegiatan_id').empty().append(new Option(jenis.text, jenis.id, true, true)).trigger('change');
                    }
                });
            }
        });

        // Search validation
        $('#search-btn').click(function(e) {
            if (!$('#program_id').val()) {
                e.preventDefault();
                Toast.fire({
                    icon: "warning",
                    title: "Warning",
                    text: "Please select a program first.",
                    timer: 2000,
                    position: "top-end"
                });
                return false;
            }
        });
    });

    // Select All
    $('#select-all').change(function() {
        $('.select-item').prop('checked', this.checked);
        updateBulkActions();
    });

    $('.select-item').change(updateBulkActions);

    function updateBulkActions() {
        const selected = $('.select-item:checked').map(function() { return this.value; }).get();
        const count = selected.length;

        $('#selectedCount').text(count + ' item(s) selected');
        $('#bulkExportPdfBtn, #bulkExportDocxBtn, #bulkPrintBtn').prop('disabled', count === 0);
        $('#selectedIdsPdf, #selectedIdsDocx').val(selected.join(','));
    }

    // Bulk Export PDF
    $('#bulkExportPdfBtn').click(function() {
        if ($('.select-item:checked').length === 0) {
            Toast.fire({ icon: 'warning', title: 'Warning', text: 'Select at least 1 report.', timer: 2000, position: 'top-end' });
            return;
        }
        $('#bulkFormPdf').submit();
    });

    // Bulk Export DOCX
    $('#bulkExportDocxBtn').click(function() {
        if ($('.select-item:checked').length === 0) {
            Toast.fire({ icon: 'warning', title: 'Warning', text: 'Select at least 1 report.', timer: 2000, position: 'top-end' });
            return;
        }
        $('#bulkFormDocx').submit();
    });

    // Bulk Print
    $('#bulkPrintBtn').click(function() {
        const selected = $('.select-item:checked').map(function() { return this.value; }).get();
        
        if (selected.length === 0) {
            Toast.fire({ icon: 'warning', title: 'Warning', text: 'Select at least 1 report.', timer: 2000, position: 'top-end' });
            return;
        }

        Swal.fire({
            title: 'Print ' + selected.length + ' Report(s)?',
            text: 'Will open 1 tab with all selected reports.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Open Print Preview',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                window.open('{{ route("btor.print.bulk") }}?ids=' + selected.join(','), '_blank');
                Toast.fire({ icon: 'success', title: 'Print Preview', text: 'Opening print preview...', timer: 2000, position: 'top-end' });
            }
        });
    });
</script>
@endpush