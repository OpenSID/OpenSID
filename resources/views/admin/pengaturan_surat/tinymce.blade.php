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
                                @foreach ($form_isian['daftar_jenis_kelamin'] as $key => $data):
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->sex)>{{ $data }}
                                    </option>
                                    <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Jenis Peristiwa</td>
                        <td>
                            <select class="form-control input-sm select2" name="individu_status_dasar">
                                <option value="">SEMUA</option>
                                @foreach ($form_isian['daftar_status_dasar'] as $key => $data):
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->status_dasar)>{{ $data }}
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
                        <td>Nama</td>
                        <td>Deskripsi</td>
                        <td>Aksi</td>
                    </tr>
                    @forelse ($suratMaster->kode_isian as $key => $value)
                        <tr class="duplikasi" id="gandakan-{{ $key }}">
                            @if ($value->tipe === 'text')
                                <td><input type="text" name="nama_kode[]" class="form-control input-sm"
                                        value="{{ $value->nama }}"></td>
                                <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm"
                                        value="{{ $value->deskripsi }}"></td>
                                <td width="1%">
                                    <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                            class='fa fa-trash-o'></i></button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="duplikasi" id="gandakan-0">
                            <td><input type="text" name="nama_kode[]" class="form-control input-sm"></td>
                            <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm"></td>
                            <td width="1%">
                                <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                        class='fa fa-trash-o'></i></button>
                            </td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="3">
                            <button type="button" class="btn btn-success btn-sm btn-block tambah-kode"><i
                                    class="fa fa-plus"></i></button>
                        </td>
                    </tr>
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
                key++;
                $("#gandakan-0").clone(true)
                    .map(function() {
                        editElm = $(this)
                            .attr('id', 'gandakan-' + key)
                            .find('select')
                            .end();
                    });

                if ($("#gandakan-" + (key - 1)).length) {
                    $("#gandakan-" + (key - 1)).after(editElm);
                } else {
                    $("#gandakan-0").after(editElm);
                }
                $("#gandakan-" + key).find('input').val('');

                $('.duplikasi').find("button").show();
                $('#gandakan-0').find("button").hide();
            });

            $('.hapus-kode').on('click', function() {
                $(this).parents('.duplikasi').remove();
            });
        });
    </script>
@endpush
