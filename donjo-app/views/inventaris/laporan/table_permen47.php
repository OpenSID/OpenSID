<div class="content-wrapper">
  <section class="content-header">
    <h1>Daftar Inventaris Jalan, Irigasi Dan Jaringan</h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
      <li class="active">Daftar Inventaris Jalan, Irigasi Dan Jaringan</li>
    </ol>
  </section>
  <section class="content" id="maincontent">
    <form id="mainformexcel" name="mainformexcel" method="post" class="form-horizontal">
      <div class="row">
        <div class="col-md-3">
          <?php $this->load->view('inventaris/menu_kiri.php')?>
        </div>
        <div class="col-md-9">
          <div class="box box-info">
            <div class="box-header with-border">
              <a href="<?= site_url('inventaris_jalan/form')?>"
                class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Tambah Data Baru">
                <i class="fa fa-plus"></i>Tambah Data
              </a>
              <a href="#"
                class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#cetakBox"
                data-title="Cetak Inventaris">
                <i class="fa fa-print"></i>Cetak
              </a>
              <a href="#"
                class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#unduhBox"
                data-title="Unduh Inventaris">
                <i class="fa fa-download"></i>Unduh
              </a>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-9">
                      <div class="form-group">
                        <select class="form-control input-sm " name="tahun"
                          onchange="formAction('mainform','<?= site_url($this->controller.'/filter/tahun')?>')">
                          <option value="">Tahun</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-12">

                      <div class="table-responsive">
                        <table id="tabelpermendagri" class="table table-bordered table-hover">
                          <thead class="bg-gray">
                            <tr>
                              <th class="text-center" rowspan="3">No</th>
                              <th class="text-center" rowspan="3">Jenis Barang/Bangunan</th>
                              <th class="text-center" rowspan="1" colspan="5">Asal Barang/Bangungan</th>
                              <th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan AWal Tahun</th>
                              <th class="text-center" rowspan="1" colspan="4">Penghapusan Barang Dan Bangunan</th>
                              <th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan Akhir Tahun</th>
                              <th class="text-center" rowspan="3">Ket</th>
                            </tr>
                            <tr>
                              <th class="text-center" rowspan="2">Dibeli Sendiri</th>
                              <th class="text-center" rowspan="1" colspan="3">Bantuan</th>
                              <th class="text-center" rowspan="2">Sumbangkan</th>
                              <th class="text-center" rowspan="2">Baik</th>
                              <th class="text-center" rowspan="2">Rusak</th>
                              <th class="text-center" rowspan="2">Rusak</th>
                              <th class="text-center" rowspan="2">Dijual</th>
                              <th class="text-center" rowspan="2">Disumbangkan</th>
                              <th class="text-center" rowspan="2">Tgl Penghapusan</th>
                              <th class="text-center" rowspan="2">Baik</th>
                              <th class="text-center" rowspan="2">Rusak</th>
                            </tr>
                            <tr>
                              <th>Pemerintah</th>
                              <th>Provinsi</th>
                              <th>Kab/Kota</th>
                            </tr>
                            <tr>
                              <th class="text-center">1</th>
                              <th class="text-center">2</th>
                              <th class="text-center">3</th>
                              <th class="text-center">4</th>
                              <th class="text-center">5</th>
                              <th class="text-center">6</th>
                              <th class="text-center">7</th>
                              <th class="text-center">8</th>
                              <th class="text-center">9</th>
                              <th class="text-center">10</th>
                              <th class="text-center">11</th>
                              <th class="text-center">12</th>
                              <th class="text-center">13</th>
                              <th class="text-center">14</th>
                              <th class="text-center">15</th>
                              <th class="text-center">16</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($data as $uraian => $asset): ?>
                            <tr>
                              <td></td>
                              <td><?= $uraian ?></td>
                              <td><?= count($asset['Pembelian Sendiri']) ?></td>
                              <td><?= count($asset['Bantuan Pemerintah']) ?></td>
                              <td><?= count($asset['Bantuan Provinsi']) ?></td>
                              <td><?= count($asset['Bantuan Kabupaten']) ?></td>
                              <td><?= count($asset['Sumbangan']) ?></td>
                              <td><?= count($asset['awal_baik']) ?></td>
                              <td><?= count($asset['awal_rusak']) ?></td>
                              <td><?= count($asset['hapus_rusak']) ?></td>
                              <td><?= count($asset['hapus_jual']) ?></td>
                              <td><?= count($asset['hapus_sumbang']) ?></td>
                              <td><?= $asset['tgl_hapus'] ?></td>
                              <td><?= count($asset['akhir_baik']) ?></td>
                              <td><?= count($asset['akhir_rusak']) ?></td>
                              <td>
                                <ul>
                                  <?php foreach ($$asset->keterangan as $ket): ?>
                                  <li><?= $ket ?></li>
                                  <?php endforeach ?>
                                </ul>
                              </td>

                            </tr>
                            <?php endforeach ?>
                          </tbody>
                          <tbody>

                          </tbody>

                        </table>
                      </div>


                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('inventaris/inventaris_global_dialog_unduh') ?>
              <?php $this->load->view('inventaris/inventaris_global_dialog_cetak') ?>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>
<?php $this->load->view('global/confirm_delete');?>
<script>
$("#form_cetak").click(function(event) {
  var link = '<?= site_url("inventaris_jalan/cetak"); ?>' + '/' + $('#tahun_pdf').val() + '/' + $(
    '#penandatangan_pdf').val();
  window.open(link, '_blank');
});
$("#form_download").click(function(event) {
  var link = '<?= site_url("inventaris_jalan/download"); ?>' + '/' + $('#tahun').val() + '/' + $('#penandatangan')
    .val();
  window.open(link, '_blank');
});

$(document).ready(function() {

  $(document).ready(function() {
    $('#tabelpermendagri').DataTable({
      lengthChange: false,
      searching: false,
      info: false
    });

  });


  $("#form_cetak").click(function(event) {
    var link = 'http://localhost/afila/premium/index.php/laporan_inventaris/cetak' + '/' + $('#tahun_pdf')
      .val() + '/' + $('#penandatangan_pdf').val();
    window.open(link, '_blank');
    // alert('fell');
  });

  $("#form_download").click(function(event) {
    var link = 'http://localhost/afila/premium/index.php/laporan_inventaris/download' + '/' + $('#tahun')
      .val() + '/' + $('#penandatangan').val();
    window.open(link, '_blank');
    // alert('fell');
  });
});
</script>