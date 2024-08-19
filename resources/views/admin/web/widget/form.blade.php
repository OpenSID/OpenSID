@extends('admin.layouts.index')

@section('title')
<h1>
    <h1>Widget</h1>
</h1>
@endsection

@section('breadcrumb')
<li><a href="{{ ci_route('web_widget') }}"> Widget</a></li>
<li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')

{!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <a href="{{ ci_route('web_widget') }}"
                    class="btn btn-social  btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                    title="Widget">
                    <i class="fa fa-arrow-circle-left "></i>Kembali Ke Widget
                </a>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="judul">Gambar Widget</label>
                <div class="col-sm-6">
                    <?php if (is_file(LOKASI_GAMBAR_WIDGET . $widget['foto'])) : ?>
                    <img class="img-responsive" src="<?= to_base64(LOKASI_GAMBAR_WIDGET . $widget['foto']) ?>"
                        alt="Gambar Utama Widget">
                    <?php else : ?>
                    <img class="img-responsive" src="<?= to_base64('assets/images/404-image-not-found.jpg') ?>"
                        alt="Gambar Utama Widget" />
                    <?php endif; ?>
                    <div class="input-group input-group-sm">
                        <input type="hidden" name="old_foto" value="<?= $widget['foto'] ?>">
                        <input type="text" class="form-control" id="file_path">
                        <input type="file" class="hidden" id="file" name="foto" accept=".jpg,.jpeg,.png">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info " id="file_browser"><i
                                    class="fa fa-search"></i></button>
                        </span>
                        <span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
                            <input type="checkbox" title="Centang Untuk Hapus Gambar" name="hapus_foto" value="hapus">
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jenis">Jenis Widget</label>
                <div class="col-sm-6">
                    <select id="jenis_widget" name="jenis_widget" class="form-control input-sm">
                        <option value="">-- Pilih Jenis Widget --</option>
                        <option value="2" <?php selected($widget['jenis_widget'], 2); ?>>Statis</option>
                        <option value="3" <?php selected($widget['jenis_widget'], 3); ?>>Dinamis</option>
                    </select>
                </div>
            </div>
            <?php if ($widget['jenis_widget'] && $widget['jenis_widget'] != '1' && $widget['jenis_widget'] != '2') {
                        $dinamis = true;
                    } ?>
            <div id="dinamis" class="form-group" <?php if (!$dinamis) { echo 'style="display:none;"' ; } ?>>
                <label class="col-sm-3 control-label" for="alamat_kantor">Kode Widget</label>
                <div class="col-sm-6">
                    <textarea style="resize:none;height:150px;" id="isi-dinamis" name="isi-dinamis"
                        class="form-control input-sm" placeholder="Kode Widget"><?= $widget['isi'] ?></textarea>
                </div>
            </div>
            <?php if ($widget['jenis_widget'] && $widget['jenis_widget'] == 2) {
                        $statis = true;
                    } ?>
            <div id="statis" class="form-group" <?php if (!$statis) { echo 'style="display:none;"' ; } ?>>
                <label class="col-sm-3 control-label" for="isi-statis">Nama File Widget (.php)</label>
                <div class="col-sm-6">
                    <?php if ($list_widget) : ?>
                    <select id="isi-statis" name="isi-statis" class="form-control input-sm select2">
                        <option value="">-- Pilih Widget --</option>
                        <?php foreach ($list_widget as $list) : ?>
                        <option value="<?= $list ?>" <?=selected($list, $widget['isi']) ?>>
                            <?= $list ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php else : ?>
                    <span
                        class="help-block"><code>Widget tidak tersedia atau sudah ditambahkan semua (desa/widgets atau desa/themes/nama_tema/widgets)</code></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class='box-footer'>
            <div class='col-xs-12'>
                <button type='reset' class='btn btn-social  btn-danger btn-sm'><i class='fa fa-times'></i>
                    Batal</button>
                <button type='submit' class='btn btn-social  btn-info btn-sm pull-right confirm'><i
                        class='fa fa-check'></i> Simpan</button>
            </div>
        </div>
    </div>
</div>
</div>
</form>
@endsection

@push('scripts')
<script>
    var elem = document.getElementById("jenis_widget");
        elem.onchange = function() {
            var dinamis = document.getElementById("dinamis");
            var statis = document.getElementById("statis");
            dinamis.style.display = (this.value == "3") ? "block" : "none";
            statis.style.display = (this.value == "2") ? "block" : "none";
        };
</script>
@endpush