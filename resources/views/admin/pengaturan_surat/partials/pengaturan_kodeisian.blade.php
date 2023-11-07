<div class="tab-pane" id="kode-isian">
    <div class="box-body">
        <div class="kode-isian-group">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Judul</label>
                        <input type="text" class="form-control judul_kode_isian" maxlength="10" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Alias</label>
                        <input type="text" class="form-control alias_kode_isian kode_isian" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Isi</label>
                <textarea id="editor-kodeisian" class="form-control value_isian_editor"></textarea>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-sm btn-success btn-block" id="btn-tambah-alias"><i class="fa fa-plus"></i></button>
            </div>
            <div class="info-kodeisian"></div>
        </div>
        <hr>
        <div class="form-group">
            <table id="table-kodeisian-alias" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Alias</th>
                        <th>Isi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alias as $isian)
                    <tr class="bold">
                        <td><input type="text" class="form-control" name="judul_kodeisian[]" readonly
                            value="{{ $isian->judul }}" maxlength="10" /></td>
                        <td><input type="text" class="form-control" name="alias_kodeisian[]" readonly
                                value="{{ $isian->alias }}" /></td>
                        <td><input type="text" class="form-control" name="content_kodeisian[]" readonly
                                value="{{ $isian->content }}" /></td>
                        <td><button type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash" onclick="$(this).closest('tr').remove()"></i></button></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="padat">Data tidak tersedia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function () {
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

        $('#btn-tambah-alias').click(function () {
            let _formGroup = $(this).closest('.kode-isian-group')
            let _judul = _formGroup.find('input.judul_kode_isian').val()
            let _alias = _formGroup.find('input.alias_kode_isian').val()
            let _content = tinymce.get('editor-kodeisian').getContent({
                format: 'text'
            })
            let _sudahAdaAlias = [],
                _sudahAdaJudul = []
            $('#table-kodeisian-alias > tbody').find('input[name^=alias_kodeisian]').each(function () {
                _sudahAdaAlias.push($(this).val())
            })
            $('#table-kodeisian-alias > tbody').find('input[name^=judul_kodeisian]').each(function () {
                _sudahAdaJudul.push($(this).val())
            })
            if (_formGroup.find('input.alias_kode_isian').next('label.error').is(':visible')) {
                _alias = false
            }

            if (!_judul) {
                $('div.info-kodeisian').addClass('error').html('Judul tidak boleh kosong')
                return
            }

            if (!_alias) {
                $('div.info-kodeisian').addClass('error').html('Alias tidak boleh kosong dan harus valid')
                return
            }

            if (!_content) {
                $('div.info-kodeisian').addClass('error').html('Isi tidak boleh kosong')
                return
            }

            if (_sudahAdaJudul.includes(_judul)) {
                $('div.info-kodeisian').addClass('error').html('Data judul sudah ada')
                return
            }
            
            if (_sudahAdaAlias.includes(_alias)) {
                $('div.info-kodeisian').addClass('error').html('Data alias sudah ada')
                return
            }            

            $(`<tr>
                    <td><input type="text" class="form-control" name="judul_kodeisian[]" readonly value="${_judul}" /></td>
                    <td><input type="text" class="form-control" name="alias_kodeisian[]" readonly value="${_alias}" /></td>
                    <td><input type="text" class="form-control" name="content_kodeisian[]" readonly value="${_content}" /></td>
                    <td><button type="button"  class="btn btn-sm btn-danger" ><i class="fa fa-trash" onclick="$(this).closest('tr').remove()"></i></button></td>
                </tr>`).appendTo('#table-kodeisian-alias > tbody')

            _formGroup.find('input.judul_kode_isian').val('')
            _formGroup.find('input.alias_kode_isian').val('')
            tinymce.get('editor-kodeisian').setContent('')
        })
    })
</script>
@endpush