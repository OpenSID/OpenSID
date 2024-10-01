@if (can('u'))
    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
        <div class='modal-body'>
            <div class="form-group">
                <label for="nik">Kepala Rumah Tangga</label>
                <select class="form-control input-sm select2 required" id="nik" name="nik" style="width:100%;">
                    <option option value="">-- Silakan Cari NIK / Nama Penduduk--</option>
                </select>
            </div>
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                Silakan cari nama / NIK dari data penduduk yang sudah terinput.
                Penduduk yang dipilih otomatis berstatus sebagai Kepala Rumah Tangga baru tersebut.
            </p>

            <div class="form-group">
                <label for="bdt">BDT</label>
                <input
                    class="form-control input-sm angka"
                    type="text"
                    placeholder="BDT"
                    name="bdt"
                    value="<?= $kk['bdt'] ?>"
                    minlength="16"
                    maxlength="16"
                />
            </div>
            <div class="form-group">
                <label for="terdaftar_dtks">
                    <input type="checkbox" id="terdaftar_dtks" name="terdaftar_dtks" class="form-checkbox"> Terdaftar di DTKS
                </label>
            </div>
        </div>
        <div class="modal-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
        </div>
    </form>
    @include('admin.layouts.components.form_modal_validasi')
@endif

<script>
    $('document').ready(function() {
        $('#nik').select2({
            ajax: {
                url: '{{ ci_route('rtm.apipendudukrtm') }}',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1,
                    };
                },
                cache: true
            },
            placeholder: function() {
                $(this).data('placeholder');
            },
            minimumInputLength: 0,
            allowClear: true,
            escapeMarkup: function(markup) {
                return markup;
            },
        });
    });
</script>
