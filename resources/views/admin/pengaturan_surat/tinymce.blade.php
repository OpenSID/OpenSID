@include('admin.pengaturan_surat.asset_tinymce')

<div class="tab-pane" id="template-surat">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body">
        <div class="form-group">
            <textarea name="template_desa" class="form-control input-sm editor required">{{ $suratMaster->template_desa ?? $suratMaster->template }}</textarea>
        </div>
    </div>
</div>
<div class="tab-pane" id="form-isian">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body">
        <h5><b>Sumber Data</b></h5>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tbody>
                    <tr style="font-weight: bold;">
                        <td>Data Penduduk Individu Berdasarkan</td>
                        <td>Pilihan</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>
                            <select class="form-control input-sm select2" name="individu_sex">
                                <option value="">SEMUA</option>
                                @foreach ($form_isian['daftar_jenis_kelamin'] as $key => $data)
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->sex)>{{ $data }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Jenis Peristiwa</td>
                        <td>
                            <select class="form-control input-sm select2" name="individu_status_dasar">
                                <option value="">SEMUA</option>
                                @foreach ($form_isian['daftar_status_dasar'] as $key => $data)
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->status_dasar)>{{ $data }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Status Hubungan Dalam Keluarga (SHDK)</td>
                        <td>
                            <select class="form-control input-sm select2" name="individu_kk_level">
                                <option value="">SEMUA</option>
                                @foreach ($form_isian['daftar_shdk'] as $key => $data):
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->kk_level)>{{ $data }}
                                    </option>
                                    <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <h5><b>Kode Isian</b></h5>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tbody>
                    <tr style="font-weight: bold;">
                        <td>Tipe</td>
                        <td>Nama</td>
                        <td>Deskripsi</td>
                        <td>Atribut</td>
                        <td>Aksi</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <button type="button" class="btn btn-success btn-sm btn-block tambah-kode"><i
                                    class="fa fa-plus"></i></button>
                        </td>
                    </tr>
                    @forelse ($suratMaster->kode_isian as $key => $value)
                        <tr class="duplikasi" id="gandakan-{{ $key }}">
                            <td>
                                <select class="form-control input-sm pilih_tipe" name="tipe_kode[]">
                                    @foreach ($attributes as $attr_key => $attr_value)
                                        <option value="{{ $attr_key }}" @selected($attr_key == $value->tipe)>{{ $attr_value }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="nama_kode[]" class="form-control input-sm"
                                    value="{{ $value->nama }}"></td>
                            <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm"
                                    value="{{ $value->deskripsi }}"></td>
                            <td>
                                <textarea class="form-control input-sm" placeholder='minlength="5" maxlength="50"' name="atribut_kode[]" rows="5">{{ $value->atribut }}</textarea>
                            </td>
                            <td width="1%">
                                <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                        class='fa fa-trash-o'></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr class="duplikasi" id="gandakan-0">
                            <td>
                                <select class="form-control input-sm pilih_tipe" name="tipe_kode[]">
                                    @foreach ($attributes as $attr_key => $attr_value)
                                        <option value="{{ $attr_key }}" @selected($attr_key == 1)>{{ $attr_value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="nama_kode[]" class="form-control input-sm"></td>
                            <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm"></td>
                            <td>
                                <textarea class="form-control input-sm" placeholder='minlength="5" maxlength="50"' name="atribut_kode[]" rows="5">{{ $value->atribut }}</textarea>
                            </td>
                            <td width="1%">
                                <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                        class='fa fa-trash-o'></i></button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#gandakan-0').find("button").hide();
            var counter = $(".tambah-kode").data("counter");
            var key = $("#gandakan-0").length;

            $('.tambah-kode').on('click', function() {
                var editElm;
                counter++;
                $("#gandakan-0").clone(true)
                    .map(function() {
                        editElm = $(this)
                            .attr('id', 'gandakan-' + counter)
                            .find('select')
                            .end();
                    });

                if ($("#gandakan-" + (counter - 1)).length) {
                    $("#gandakan-" + (counter - 1)).before(editElm);
                } else {
                    $("#gandakan-0").before(editElm);
                }
                $("#gandakan-" + counter).find('input').val('');
                $("#gandakan-" + counter).find('select').change(0);
                $("#gandakan-" + counter + " option:selected").removeAttr('selected');

                $('.duplikasi').find("button").show();
                $('#gandakan-0').find("button").hide();
            });

            $('.hapus-kode').on('click', function() {
                $(this).parents('.duplikasi').remove();
            });

            $('.pilih_tipe').on('change', function() {
                var tipe = $(this).val();
                var atribut = '{}';

                if (tipe == 'text') {
                    atribut = 'minlength="5" maxlength="50"';
                } else if (tipe == 'number') {
                    atribut = 'min="1" max="100" step="1"';
                } else if (tipe == 'email') {
                    atribut = 'minlength="5" maxlength="50"';
                } else if (tipe == 'url') {
                    atribut = 'minlength="5" maxlength="50"';
                } else if (tipe == 'date') {
                    atribut = 'min="2021-01-01" max="2021-12-31"';
                } else if (tipe == 'time') {
                    atribut = 'min="00:00" max="23:59"';
                } else {
                    atribut = 'minlength="5" maxlength="50" rows="5"';
                }

                $(this).parents('.duplikasi').find('textarea').attr("placeholder", atribut);
            });
        });
    </script>
@endpush
