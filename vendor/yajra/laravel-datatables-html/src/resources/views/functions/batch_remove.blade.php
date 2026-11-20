$(function(){
    @foreach($editors as $editor)
        {{ config('datatables-html.namespace', 'LaravelDataTables') }}["%1$s-{{$editor->instance}}"].on('preSubmit', function(e, data, action) {
            if (action !== 'remove') return;

            for (let row_id of Object.keys(data.data))
            {
                data.data[row_id] = {
                    DT_RowId: data.data[row_id].DT_RowId
                };
            }
        });
    @endforeach
});
