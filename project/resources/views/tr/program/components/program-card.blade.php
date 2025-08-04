{{-- Mobile-First Responsive Layout Components --}}

{{-- Program Card Component for Mobile View --}}
@component('components.program-card')
    @slot('program')
        {{ $program }}
    @endslot
@endcomponent

{{--
    Usage:
    @component('components.program-card')
        @slot('program')
            {{ $program }}
        @endslot
    @endcomponent
--}}

@if (!isset($__env->componentContext['components.program-card']))
    @php
        $__env->startComponent('components.program-card');
        $__env->slot('program');
    @endphp
@endif

<div class="program-card mobile-card mb-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="card-title mb-0">{{ $program->nama }}</h6>
                <span class="badge badge-{{ $program->status === 'running' ? 'success' : ($program->status === 'pending' ? 'warning' : 'secondary') }}">
                    {{ strtoupper($program->status) }}
                </span>
            </div>
            
            <div class="program-meta">
                <small class="text-muted d-block mb-1">
                    <i class="fas fa-code"></i> {{ $program->kode }}
                </small>
                <small class="text-muted d-block mb-1">
                    <i class="fas fa-calendar"></i> {{ $program->tanggalmulai }} - {{ $program->tanggalselesai }}
                </small>
                <small class="text-muted d-block">
                    <i class="fas fa-users"></i> {{ $program->ekspektasipenerimamanfaat ?? 0 }} beneficiaries
                </small>
            </div>
            
            <div class="program-actions mt-3">
                <div class="btn-group btn-group-sm w-100">
                    <a href="{{ route('program.show', $program->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye"></i>
                        <span class="d-none d-sm-inline">View</span>
                    </a>
                    <a href="{{ route('program.edit', $program->id) }}" class="btn btn-outline-success">
                        <i class="fas fa-edit"></i>
                        <span class="d-none d-sm-inline">Edit</span>
                    </a>
                    <a href="{{ route('program.details', $program->id) }}" class="btn btn-outline-info">
                        <i class="fas fa-list"></i>
                        <span class="d-none d-sm-inline">Details</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if (isset($__env->componentContext['components.program-card']))
    @php
        $__env->endSlot();
        $__env->endComponent();
    @endphp
@endif

<style>
.program-card.mobile-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.program-card.mobile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.program-meta {
    font-size: 0.875rem;
}

.program-actions .btn-group .btn {
    flex: 1;
}

@media (max-width: 576px) {
    .program-card.mobile-card .card-body {
        padding: 1rem;
    }
    
    .program-card.mobile-card .program-meta {
        font-size: 0.8rem;
    }
    
    .program-card.mobile-card .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>