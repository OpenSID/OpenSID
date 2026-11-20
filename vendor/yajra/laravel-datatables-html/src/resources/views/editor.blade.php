document.addEventListener("DOMContentLoaded", function(){
    window.{{ config('datatables-html.namespace', 'LaravelDataTables') }} = window.{{ config('datatables-html.namespace', 'LaravelDataTables') }} || {};
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});
    @foreach($editors as $editor)
        var {{$editor->instance}} = window.{{ config('datatables-html.namespace', 'LaravelDataTables') }}["%1$s-{{$editor->instance}}"] = new $.fn.dataTable.Editor({!! $editor->toJson() !!});
        {!! $editor->scripts  !!}
        @foreach ((array) $editor->events as $event)
            {{$editor->instance}}.on('{!! $event['event']  !!}', {!! $event['script'] !!});
        @endforeach
    @endforeach
    window.{{ config('datatables-html.namespace', 'LaravelDataTables') }}["%1$s"] = $("#%1$s").DataTable(%2$s);
});
@foreach ($scripts as $script)
@include($script)
@endforeach
