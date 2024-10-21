@include('admin.layouts.components.ttd_pamong')
<script>
    $(document).ready(function() {
        let _objParams = $('#tabeldata').DataTable().ajax.params()
        delete(_objParams.draw)
        delete(_objParams.search)
        $('form#validasi').find('input[name=params]').remove()
        $('form#validasi').append(`<input name="params" type="hidden" value='${JSON.stringify(_objParams)}'>`)
    })
</script>
