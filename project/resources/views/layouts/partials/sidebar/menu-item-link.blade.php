{{-- This is Main Sidebar Conf
<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-item">
    <a class="nav-link {{ $item['class'] }} @isset($item['shift']){{ $item['shift'] }} @endisset" href="{{ $item['href'] }}" @isset($item['target']) target="{{ $item['target'] }}" @endisset
        {!! $item['data-compiled'] ?? '' !!}>
        <i class="{{ $item['icon'] ?? 'fas fa-genderless' }} {{ isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '' }}"></i>
        <p>{{ $item['text'] }}
            @isset($item['label'])
                <span class="badge badge-{{ $item['label_color'] ?? 'primary' }} right">{{ $item['label'] }}</span>
            @endisset
        </p>
    </a>
</li> --}}

{{-- This is Main Sidebar Conf --}}
<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-item">
    <a class="nav-link {{ $item['class'] }} @isset($item['shift']){{ $item['shift'] }} @endisset" href="{{ $item['href'] }}" @isset($item['target']) target="{{ $item['target'] }}" @endisset
        {!! $item['data-compiled'] ?? '' !!}>
        {{-- Icon with conditional rendering --}}
        @if(isset($item['icon']))
            @if(str_contains($item['icon'], 'material-symbols-outlined'))
                <span class="{{ $item['icon'] }} {{ isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '' }}">
                    {{ $item['icon_text'] ?? '' }} {{-- New required field --}}
                </span>
            @else
                <i class="{{ $item['icon'] }} {{ isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '' }}"></i>
            @endif
        @else
            <i class="fas fa-genderless {{ isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '' }}"></i>
        @endif
        
        <p>{{ $item['text'] }}
            @isset($item['label'])
                <span class="badge badge-{{ $item['label_color'] ?? 'primary' }} right">{{ $item['label'] }}</span>
            @endisset
        </p>
    </a>
</li>