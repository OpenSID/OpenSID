window.{{ config('datatables-html.namespace', 'LaravelDataTables') }} = window.{{ config('datatables-html.namespace', 'LaravelDataTables') }} || {};
window.{{ config('datatables-html.namespace', 'LaravelDataTables') }}.options = %2$s
window.{{ config('datatables-html.namespace', 'LaravelDataTables') }}.editors = [];
@foreach($editors as $editor)
window.{{ config('datatables-html.namespace', 'LaravelDataTables') }}.editors["{{$editor->instance}}"] = {!! $editor->toJson()  !!}
@endforeach
