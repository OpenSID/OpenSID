<div class="tab-pane" id="kode-isian">
    <div class="box-body">
        <div class="form-group">
            <a data-target="#form-kodeisian" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-plus"></i>Form Kode Isian
            </a>
        </div>
        <div class="form-group">
            <table id="table-kodeisian-alias" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Alias</th>
                        <th>Isi</th>
                        <th class="padat">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alias as $isian)
                        <tr class="bold" data-index="{{ $isian->id }}">
                            <td><input type="text" class="form-control" name="judul_kodeisian[]" readonly value="{{ $isian->judul }}" maxlength="20" /></td>
                            <td><input type="text" class="form-control" name="alias_kodeisian[]" readonly value="{{ $isian->alias }}" /></td>
                            <td><input type="text" class="form-control" name="content_kodeisian[]" readonly value="{{ $isian->content }}" /></td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button
                                        type="button"
                                        data-target="#form-kodeisian"
                                        data-remote="false"
                                        data-toggle="modal"
                                        data-backdrop="false"
                                        data-keyboard="false"
                                        class="btn btn-sm btn-warning can-edit"
                                    ><i class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="$(this).closest('tr').remove()"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="tidak-ada-data">
                            <td colspan="4" class="padat">Data tidak tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@include('admin.pengaturan_surat.partials.modal_kodeisian')
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#form-kodeisian').on('shown.bs.modal', function(ev) {
                let _btn = $(ev.relatedTarget)
                const modal = $(this)
                modal.find(`.modal-body input`).val('')
                tinymce.get('editor-kodeisian').setContent('')
                modal.data('index', null)
                modal.find('.modal-body input.judul_kode_isian').attr('maxlength', 20)
                modal.find('.modal-body input.alias_kode_isian').attr('maxlength', 20)
                if (_btn.hasClass('can-edit')) {
                    const _tr = _btn.closest('tr')
                    modal.data('index', _tr.attr('data-index'))
                    modal.find('.modal-body input.judul_kode_isian').val(_tr.find('input[name="judul_kodeisian[]"]').val())
                    modal.find('.modal-body input.alias_kode_isian').val(_tr.find('input[name="alias_kodeisian[]"]').val())
                    tinymce.get('editor-kodeisian').setContent(_tr.find('input[name="content_kodeisian[]"]').val())
                }
            });

            tinymce.init({
                selector: '.value_isian_editor',
                promotion: false,
                height: "150",
                plugins: "kodeisian",
                menu: {
                    isian: {
                        title: 'Kode Isian',
                        items: 'kodeisian'
                    }
                },
                menubar: 'isian',
                toolbar: "kodeisian",
                skin: 'tinymce-5',
                relative_urls: false,
                remove_script_host: false,
                entity_encoding: 'raw',
                // gak bisa pakai false
                //forced_root_block: false, 
                forced_root_block: ' ',
            });

            $('#btn-tambah-alias').click(function() {
                const _modal = $(this).closest('.modal')
                let _formGroup = _modal.find('.kode-isian-group')
                let _judul = _formGroup.find('input.judul_kode_isian').val()
                let _alias = _formGroup.find('input.alias_kode_isian').val()
                let _content = tinymce.get('editor-kodeisian').getContent({
                    format: 'text'
                })

                let _index = _modal.data('index') ?? new Date().getTime()
                let _dataSudahAda = _modal.data('index') == null ? 0 : 1
                let _trSelected = _modal.data('index') == null ? null : $(`#table-kodeisian-alias > tbody > tr[data-index=${_index}]`)

                let _sudahAdaAlias = [],
                    _sudahAdaJudul = []
                $('#table-kodeisian-alias > tbody > tr').not(_trSelected).each(function() {
                    _sudahAdaAlias.push($(this).find('input[name^=alias_kodeisian]').val())
                })
                $('#table-kodeisian-alias > tbody > tr').not(_trSelected).each(function() {
                    _sudahAdaJudul.push($(this).find('input[name^=judul_kodeisian]').val())
                })

                if (_formGroup.find('input.alias_kode_isian').next('label.error').is(':visible')) {
                    _alias = false
                }

                if (!_judul) {
                    _error('Judul harus diisi')
                    return
                }

                if (!_alias) {
                    _error('Alias harus diisi dan harus valid')
                    return
                }

                if (!_content) {
                    _error('Isi harus diisi')
                    return
                }

                if (_sudahAdaJudul.includes(_judul)) {
                    _error('Data judul sudah ada')
                    return
                }

                if (_sudahAdaAlias.includes(_alias)) {
                    _error('Data alias sudah ada')
                    return
                }

                if (!_dataSudahAda) {
                    $(`<tr data-index="${_index}">
                            <td><input type="text" class="form-control" name="judul_kodeisian[]" readonly value="${_judul}" /></td>
                            <td><input type="text" class="form-control" name="alias_kodeisian[]" readonly value="${_alias}" /></td>
                            <td><input type="text" class="form-control" name="content_kodeisian[]" readonly value="${_content}" /></td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button type="button" data-target="#form-kodeisian" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" class="btn btn-sm btn-warning can-edit" ><i class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="$(this).closest('tr').remove()"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>`).appendTo('#table-kodeisian-alias > tbody')
                } else {
                    _trSelected.find('input[name="judul_kodeisian[]"]').val(_judul)
                    _trSelected.find('input[name="alias_kodeisian[]"]').val(_alias)
                    _trSelected.find('input[name="content_kodeisian[]"]').val(_content)
                }

                if ($('#table-kodeisian-alias > tbody > tr').not('#tidak-ada-data').length > 0) {
                    $('#tidak-ada-data').hide();
                } else {
                    $('#tidak-ada-data').show();
                }

                _formGroup.find('input.judul_kode_isian').val('')
                _formGroup.find('input.alias_kode_isian').val('')
                tinymce.get('editor-kodeisian').setContent('')
                _modal.modal('hide')
            });
        })
    </script>
@endpush
