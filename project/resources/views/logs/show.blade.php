@extends('layouts.app')

@section('content_body')
<div class="container mt-4">
    <h1 class="mb-4">Log Details</h1>
    <ul class="list-group">
        <li class="list-group-item"><strong>Description:</strong> {{ $log->description }}</li>
        <li class="list-group-item"><strong>User:</strong> {{ $log->causer->nama ?? 'System' }}</li>
        <li class="list-group-item"><strong>Event:</strong> {{ ucfirst($log->event) }}</li>
        <li class="list-group-item"><strong>Model:</strong> {{ class_basename($log->subject_type) }}</li>
        {{-- <li class="list-group-item"><strong>Model ID:</strong> {{ $log->subject_id }}</li> --}}
        <li class="list-group-item"><strong>Time:</strong> {{ $log->created_at->format('Y-m-d H:i:s') }}</li>
        <li class="list-group-item">
            <strong>Old Values:</strong>
            <ul>
                @php
                    $oldValues = collect($log->properties['old'] ?? [])->except(['created_at', 'updated_at']);
                @endphp
                @forelse ($oldValues as $key => $value)
                    @php
                        $newValue = $log->properties['attributes'][$key] ?? null;
                        $isChanged = $value !== $newValue;
                    @endphp
                    <li>
                        {{ ucfirst($key) }}:
                        <span @if($isChanged) style="font-weight: bold;" @endif>{{ $value }}</span>
                    </li>
                @empty
                    <li class="text-muted">No old values</li>
                @endforelse
            </ul>
        </li>
        <li class="list-group-item">
            <strong>New Values:</strong>
            <ul>
                @php
                    $newValues = collect($log->properties['attributes'] ?? [])->except(['created_at', 'updated_at']);
                @endphp
                @forelse ($newValues as $key => $value)
                    @php
                        $oldValue = $log->properties['old'][$key] ?? null;
                        $isChanged = $value !== $oldValue;
                    @endphp
                    <li>
                        {{ ucfirst($key) }}:
                        <span @if($isChanged) style="font-weight: bold;" @endif>{{ $value }}</span>
                    </li>
                @empty
                    <li class="text-muted">No new values</li>
                @endforelse
            </ul>
        </li>
    </ul>
    <a href="{{ route('logs.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

