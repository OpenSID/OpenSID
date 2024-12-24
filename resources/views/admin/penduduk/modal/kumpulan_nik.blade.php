@include('admin.layouts.components.form_modal_validasi')
<form method="post" action="{{ $form_action }}" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <label for="nama">Kumpulan NIK</label>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <select
                        autofocus
                        name="kumpulan_nik[]"
                        id="kumpulan_nik"
                        class="form-control input-sm select2 select2-nik-ajax"
                        data-placeholder="-- Cari NIK --"
                        multiple
                        data-url="{{ ci_route('penduduk.list_nik_ajax') }}"
                    >
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="button" onclick="searchNIK(this)" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i>
            Simpan</button>
    </div>
</form>
<script>
    function searchNIK(elm) {
        $('#tabeldata').data('bantuan', null)
        $('#tabeldata').data('nik_sementara', null)
        $('#tabeldata').data('kumpulanNIK', $('#kumpulan_nik').val())
        $('#tabeldata').DataTable().draw()
        $(elm).closest('.modal-dialog').find('.modal-header>button.close').click()
    }
    $(function() {
        let nikTerpilih = $('#tabeldata').data('kumpulanNIK')
        if (nikTerpilih) {
            nikTerpilih.forEach(item => {
                $(`<option selected value='${item}'>${item}</option>`).appendTo($('#kumpulan_nik'))
            });
        }
        $('.select2-nik-ajax').select2({
            ajax: {
                url: function() {
                    return $(this).data('url');
                },
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1,
                    };
                },
                processResults: function(data, params) {
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            },
            maximumSelectionLength: 20,
            templateResult: function(penduduk) {
                return penduduk.text;
            },
            placeholder: '--  Cari NIK / Tag ID Card / Nama Penduduk --',
            minimumInputLength: 0,
        });
    })
</script>
