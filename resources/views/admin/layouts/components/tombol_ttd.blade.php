@if (can('u'))
    @if ($active == 0)
        <a href="{{ $url }}" 
           class="btn bg-purple btn-sm" 
           title="Jadikan TTD {{ $label }}">{{ $label }}</a>
    @else
        <a href="{{ $url }}" 
           class="btn bg-navy btn-sm" 
           title="Bukan TTD {{ $label }}">{{ $label }}</a>
    @endif
@endif
