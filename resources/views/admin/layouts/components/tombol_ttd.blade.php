@if (can('u'))
    @if ($active == 0)
        <a href="{{ $url }}" 
           class="btn bg-purple btn-sm" 
           title="Jadikan TTD a.n">a.n</a>
    @else
        <a href="{{ $url }}" 
           class="btn bg-navy btn-sm" 
           title="Bukan TTD a.n">a.n</a>
    @endif
@endif
