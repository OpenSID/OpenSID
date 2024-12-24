@include('admin.layouts.components.form_modal_validasi')
<form method="post" action="{{ $form_action }}" id="validasi">
    <div class='modal-body'>
        <div class="box box-danger">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="nama">Kumpulan KK</label>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <select
                                autofocus
                                name="kumpulan_kk[]"
                                id="kumpulan_kk"
                                class="form-control input-sm select2 select2-kk-ajax"
                                data-placeholder="-- Cari No KK --"
                                multiple
                                data-url="{{ ci_route('keluarga.list_kk_ajax') }}"
                            >
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="button" onclick="searchKK(this)" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i>
            Simpan</button>
    </div>
</form>
<script>
    function searchKK(elm) {
        $('#tabeldata').data('bantuan', '')
        $('#tabeldata').data('kumpulanKK', $('#kumpulan_kk').val())
        $('#tabeldata').DataTable().draw()
        $(elm).closest('.modal-dialog').find('.modal-header>button.close').click()
    }
    $(function() {
        let kkTerpilih = $('#tabeldata').data('kumpulanKK')
        if (kkTerpilih) {
            kkTerpilih.forEach(item => {
                $(`<option selected value='${item}'>${item}</option>`).appendTo($('#kumpulan_kk'))
            });
        }
        $('.select2-kk-ajax').select2({
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
