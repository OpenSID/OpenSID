<style>
    .judul-surat {
        /* margin: -10px; */
        font-size: 18px;
        font-weight: bold;
    }
</style>
<div class="box box-solid" id="wrapper-mandiri">
    <div class="box-header with-border bg-green">
        <h4 class="box-title">Surat</h4>
    </div>
    <div class="box-body box-line">
        <h1 class="judul-surat">Surat <?= $surat['nama'] ?></h1>
        <div class="box-body permohonan-surat">

            <form id="validasi" action="<?= $form_action?>" method="POST" class="form-surat form-horizontal">
                <input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
                <input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
                <div class="form-group cari_nik">
                    <label for="nik"  class="col-sm-3 control-label">NIK / Nama <?= $pemohon?></label>
                    <div class="col-sm-6 col-lg-4">
                    <select class="form-control input-sm readonly-permohonan readonly-periksa" id="nik" name="nik" style ="width:100%;">
                        <?php if ($individu): ?>
                            <option value="<?= $individu['id']?>" selected><?= $individu['nik'] . ' - ' . $individu['tag_id_card'] . ' - ' . $individu['nama']?></option>
                        <?php endif; ?>
                    </select>
                    </div>
                </div>
                <?php if ($individu): ?>
                    <?php $this->load->view('surat/form/konfirmasi_pemohon'); ?>
                <?php	endif; ?>
                <div class="row jar_form">
                    <label for="nomor" class="col-sm-3"></label>
                    <div class="col-sm-8">
                        <input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
                    </div>
                </div>
                <?php
                    $this->load->view('surat/form/nomor_surat');
        $this->load->view('surat/form/kode_isian');
        $this->load->view('surat/form/tgl_berlaku');
        $this->load->view('surat/form/_pamong');
        ?>
            </form>

            <textarea id="isian_form" hidden="hidden"><?= $isian_form; ?></textarea>
        </div>
    </div>
    <?php $this->load->view('surat/form/tombol_cetak.php'); ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Di form surat ubah isian admin menjadi disabled
        $("#wrapper-mandiri .readonly-permohonan").attr('disabled', true);
        $("#wrapper-mandiri .tdk-permohonan textarea").removeClass('required');
        $("#wrapper-mandiri .tdk-permohonan select").removeClass('required');
        $("#wrapper-mandiri .tdk-permohonan input").removeClass('required');
    });

    $(document).ready(function() {
        // Di form surat ubah isian admin menjadi disabled
        $("#periksa-permohonan .readonly-periksa").attr('disabled', true);

        if ($('#isian_form').val()) {
            setTimeout(function() {isi_form();}, 100);
        }
    });

    function isi_form() {
        var isian_form = JSON.parse($('#isian_form').val(), function(key, value) {

            if (key) {
                var elem = $('*[name=' + key + ']');
                elem.val(value);
                elem.change();
                // Kalau isian hidden, akan ada isian lain untuk menampilkan datanya
                if (elem.is(":hidden")) {
                    var show = $('#' + key + '_show');
                    show.val(value);
                    show.change();
                }
            }
        });
    }
</script>
