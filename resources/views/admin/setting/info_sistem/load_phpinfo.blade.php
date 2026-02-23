{{-- prettier-ignore-start --}}
@php $i = 0; @endphp
@foreach ($phpinfo as $name => $section)
    @php $i++; @endphp
    @if ($i == 1)
        <div class='table-responsive'>
            <table class='table table-bordered dataTable table-hover'>
    @else
        <h3>{{ $name }}</h3>
        <div class='table-responsive'>
            <table class='table table-bordered dataTable table-hover'>
    @endif
    @foreach ($section as $key => $val)
        @if (is_array($val))
            <tr>
                <td class="col-md-4 info">{!! $key !!}</td>
                <td>{!! $val[0] !!}</td>
                <td>{!! $val[1] !!}</td>
            </tr>
        @elseif (is_string($key))
            <tr>
                <td class="col-md-4 info">{!! $key !!}</td>
                <td colspan='2'>{!! $val !!}</td>
            </tr>
        @else
            <tr>
                <td class="btn-primary" colspan='3'><?= $val ?></td>
            </tr>
        @endif
    @endforeach
    </table>
    </div>
@endforeach
{{-- prettier-ignore-end --}}
