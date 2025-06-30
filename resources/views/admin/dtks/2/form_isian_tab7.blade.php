@push('css')
    <link rel="stylesheet" href="<?= asset('css/camera.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/cropper.min.css') ?>">
@endpush
<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" id="tabel_lampiran">
            <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <td>Aksi</td>
                        <td>Judul</td>
                        <td>Sampel Foto Ukuran Kecil</td>
                        <td>Keterangan</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dtks->lampiran as $lampiran)
                        <tr data-id="{{ $lampiran->id }}">
                            <td>
                                <a href="#" data-id="{{ $lampiran->id }}" class="btn-hapus btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" data-target="#modal-confirm-delete-lampiran"><i class="fa fa-trash"></i> Hapus</a>
                            </td>
                            <td>
                                {{ $lampiran->judul }}
                            </td>
                            <td>
                                <a href="#" data-target="#modal-foto" data-foto="{{ $lampiran->foto }}" data-toggle="modal"><img src="{{ $lampiran->foto_kecil }}" title="Foto Thumbnail" alt="Foto {{ $lampiran->judul }}" style="max-height: 150px; max-width: 50vw;"></a>
                            </td>
                            <td>
                                {{ $lampiran->keterangan }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <a href="#" id="btn-tambah-lampiran" data-remote="false" data-toggle="modal" data-target="#modal-tambah-lampiran" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                                    class="fa fa-plus"></i> Tambah Foto</a>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12 text-center">
        <button type="button" onclick="$(`#nav-bagian-6`).trigger('click')" class="btn btn-social btn-default btn-sm"><i class='fa fa-arrow-left'></i> Sebelumnya</button>
        <button type="button" disabled class="btn btn-social btn-default btn-sm">Selanjutnya <i class="fa fa-arrow-right"></i></button>
    </div>
</div>

<div
    class="modal fade"
    id="modal-tambah-lampiran"
    style="overflow: scroll;"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Tambah lampiran foto</h4>
            </div>
            <div class="modal-body">
                {!! form_open('', 'class="" id="form-7-upload"') !!}
                <input type="hidden" name='tipe_save' value='bagian7_upload'>

                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="penduduk" id="foto" src="{{ AmbilFoto('', '') }}" alt="Foto">
                        <br />
                        <div class="input-group input-group-sm text-center">
                            <input type="file" class="hidden" id="file" name="foto" accept="image/*">
                            <input type="text" class="hidden" id="file_path" name="file_path">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-block btn-mb-5" id="file_browser"><i class="fa fa-upload"></i> Unggah</button>
                                <button type="button" class="btn btn-danger btn-block btn-mb-5" onclick="kamera();"><i class="fa fa-camera"></i> Kamera</button>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="judul_foto">Judul</label>
                            <select class="form-control input-sm select2-tags" data-placeholder="Pilih/Isi judul" id="judul_foto" name="judul_foto">
                                <option value="">Pilih/Isi Judul</option>
                                @foreach ($judul_lampiran as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan_foto">Keterangan</label>
                            <textarea id="keterangan_foto" name="keterangan_foto" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class='fa fa-times'></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="modal-confirm-delete-lampiran"
    style="overflow: scroll;"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            {!! form_open('', 'class="" id="form-7-remove-lampiran"') !!}
            <input type="hidden" name='tipe_remove' value='lampiran'>
            <input type="hidden" name='lampiran_id' id="lampiran_id" value=''>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i> Konfirmasi</h4>
            </div>
            <div class="modal-body btn-info">
                Apakah Anda yakin ingin menghapus lampiran ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                <button type="submit" class="btn btn-social btn-danger btn-sm" id="okdelete"><i class="fa fa-trash-o"></i> Hapus</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-camera" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center">Ambil Gambar</h4>
            </div>
            <div class="modal-body">
                <div id="kamera"></div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger btn-sm" onClick="ambil();"><i class="fa fa-camera"></i>&nbsp; Ambil Gambar</button>
                    </div>
                    <select class="input-sm" id="mode">
                        <option value="user" selected>Kamera Depan</option>
                        <option value="environment">Kamera Belakang</option>
                        <option value="computer">Webcam</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal-crop" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center">Ubah Gambar</h4>
            </div>
            <div class="modal-body">
                <div id="cropimage"></div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger btn-sm" title="Ambil Gambar" onclick="kamera();"><i class="fa fa-camera"></i>&nbsp;</button>
                        <button type="button" class="btn btn-primary btn-sm" id="rotateL" title="Putar ke kiri"><i class="fa fa-undo"></i>&nbsp;</button>
                        <button type="button" class="btn btn-primary btn-sm" id="rotateR" title="Putar ke kanan"><i class="fa fa-repeat"></i>&nbsp;</button>
                        <button type="button" class="btn btn-primary btn-sm" id="scaleX" title="Balik Horizontal"><i class="fa fa-arrows-h"></i>&nbsp;</button>
                        <button type="button" class="btn btn-primary btn-sm" id="scaleY" title="Balik Vertikal"><i class="fa fa-arrows-v"></i>&nbsp;</button>
                        <button type="button" class="btn btn-primary btn-sm" id="reset-ini" title="Default"><i class="fa fa-refresh"></i>&nbsp;</button>
                        <button type="button" class="btn btn-success btn-sm" id="simpan-gambar" title="Simpan"><i class="fa fa-save"></i>&nbsp;</button>
                    </div>
                    <div class="btn-group">
                        <select class="input-sm" id="ratio">
                            <option value="NaN">Pilih Ratio (NaN)</option>
                            <option value="1.777">16 : 9</option>
                            <option value="1.500">3 : 2</option>
                            <option value="1.333" selected>4 : 3</option>
                            <option value="1.000">1 : 1</option>
                            <option value="0.666">2 : 3</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- buatkan modal untuk menampilkan foto --}}
<div class="modal fade" id="modal-foto" style="overflow: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center">Foto</h4>
            </div>
            <div class="modal-body">
                <img id="foto_lampiran_full" alt="Foto" class="img-responsive" width="600px">
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="<?= asset('js/webcam.min.js') ?>"></script>
        <script src="<?= asset('js/cropper.min.js') ?>"></script>
        <script src="<?= asset('js/main-camera.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                let default_pic = "{{ AmbilFoto('', '') }}";
                $('.btn-hapus').one('click', function() {
                    $('#form-7-remove-lampiran #lampiran_id').val($(this).data('id'));
                });
                $('#form-7-remove-lampiran').on('submit', function(ev) {
                    ev.preventDefault();

                    let form = $('#form-7-remove-lampiran').serializeArray();
                    ajax_save_dtks("{{ ci_route('dtks.remove') . '/' . $dtks->id }}", form,
                        callback_success = function(data) {
                            $('#modal-confirm-delete-lampiran').modal('hide');
                            $(document).find('tr[data-id=' + $('#form-7-remove-lampiran #lampiran_id').val() + ']').remove();
                        },
                        callback_fail = function(xhr) {
                            $('#modal-confirm-delete-lampiran').modal('hide');
                        }
                    );
                });
                $('#modal-tambah-lampiran').on('shown.bs.modal', function(ev) {

                });
                $('#form-7-upload').on('submit', function(ev) {
                    ev.preventDefault();

                    let judul_terisi = $('#judul_foto').val() != '' || $('#judul_foto_select').val() != null;
                    if (!judul_terisi) {
                        Swal.fire({
                            icon: 'error',
                            html: 'Judul harus diisi atau dipilih',
                        })
                        return false;
                    }
                    if ($('#keterangan_foto').val() == '') {
                        Swal.fire({
                            icon: 'error',
                            html: 'Keterangan harus diisi',
                        })
                        return false;
                    }

                    let form = new FormData(this);
                    ajax_save_dtks("{{ ci_route('dtks.save') . '/' . $dtks->id }}", form,
                        callback_success = function(data) {
                            $('#judul_foto').val(null).trigger('change');
                            $('.select2-tags').append('<option value="' + data.data.judul + '">' + data.data.judul + '</option>')
                            $('#modal-tambah-lampiran').modal('hide');
                            $('#foto').attr('src', default_pic);
                            $('#form-7-upload').trigger('reset');
                            $('#tabel_lampiran tbody').append(`<tr data-id="` + data.data.id + `">` +
                                `<td>` +
                                `<a href="#" data-id="` + data.data.id + `" class="btn-hapus btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#modal-confirm-delete-lampiran"><i class="fa fa-trash"></i> Hapus</a>` +
                                `</td>` +
                                `<td>` +
                                data.data.judul +
                                `</td>` +
                                `<td>` +
                                `<a href="#" data-target="#modal-foto" data-foto="` + data.data.foto + `" data-toggle="modal"><img src="` + data.data.foto_kecil + `" title="Foto Thumbnail" alt="Foto ` + data.data.judul + `" style="max-height: 150px;max-width: 50vw;"></a>` +
                                `</td>` +
                                `<td>` +
                                data.data.keterangan +
                                `</td>` +
                                `</tr>`);
                            // tambahkan event hapus untuk lampiran baru
                            $('tr[data-id=' + data.data.id + '] .btn-hapus').on('click', function() {
                                $('#form-7-remove-lampiran #lampiran_id').val($(this).data('id'));
                            });
                        },
                        callback_fail = function(xhr) {
                            $('#modal-tambah-lampiran').modal('hide');
                            $('#foto').attr('src', default_pic);
                            $('#form-7-upload').trigger('reset');
                        },
                        custom_config = {
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: 'json',
                        }
                    );
                });
            });
            // buat fungsi ketika modal modal-foto di show, maka gambar akan di load
            $('#modal-foto').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var pathFoto = `{{ site_url() . LOKASI_FOTO_DTKS }}`;
                var foto = pathFoto + button.data('foto');
                var modal = $(this);
                modal.find('.modal-body #foto_lampiran_full').attr('src', foto);
            });
        </script>
    @endpush
