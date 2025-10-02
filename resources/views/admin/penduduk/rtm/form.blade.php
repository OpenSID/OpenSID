@if (can('u'))
    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
        <div class='modal-body'>
            <div class="form-group">
                <label for="no_rtm">Nomor Rumah Tangga</label>
                <input id="no_rtm" name="no_rtm" class="form-control input-sm nama_terbatas" type="text" placeholder="Nomor Rumah Tangga" maxlength="30" />
                <code>Kosongkan untuk melanjutkan nomor rumah tangga terakhir</code>
            </div>
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

            <div id="anggota_rtm"></div>

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
                    <input type="checkbox" id="terdaftar_dtks" name="terdaftar_dtks" class="form-checkbox"> Terdaftar di
                    DTKS
                </label>
            </div>
        </div>
        <div class="modal-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i>
                Simpan</button>
        </div>
    </form>
    @include('admin.layouts.components.form_modal_validasi')
@endif

<script>
    $(document).ready(function() {
        $('#nik').select2({
            dropdownParent: $('#modalBox'),
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

        $('#nik').on('change', function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: '{{ ci_route("rtm.list_anggota_kk") }}/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var html =
                            '<div class="form-group"><label for="anggota">Anggota Rumah Tangga</label><table class="table table-bordered table-striped table-hover"><thead><tr><th><input type="checkbox" id="check-all"></th><th>No</th><th>NIK</th><th>Nama</th><th>Hubungan</th></tr></thead><tbody>';
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(item, index) {
                                html += '<tr>';
                                html +=
                                    '<td class="padat"><input type="checkbox" name="anggota_kk[]" value="' +
                                    item.id + '"></td>';
                                html += '<td class="padat">' + (index + 1) + '</td>';
                                html += '<td>' + item.nik + '</td>';
                                html += '<td>' + item.nama + '</td>';
                                html += '<td>' + item.hubungan + '</td>';
                                html += '</tr>';
                            });
                        } else {
                            html +=
                                '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
                        }
                        html += '</div></tbody></table>';
                        $('#anggota_rtm').html(html);

                        $('#check-all').on('click', function() {
                            $('input[name^="anggota_kk"]').prop('checked', $(this)
                                .prop('checked'));
                        });
                    }
                });
            } else {
                $('#anggota_rtm').html('');
            }
        });
    });
</script>