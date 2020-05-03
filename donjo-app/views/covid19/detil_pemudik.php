<div class="content-wrapper">
  <section class="content-header">
    <h1>Detil Pemudik</h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('covid19')?>"><i class="fa fa-home"></i> Data Pemudik</a></li>
      <li class="active">Detil Pemudik</li>
    </ol>
  </section>
  <section class="content" id="maincontent">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">

          <div class="box-header with-border">
            <a href="<?= site_url('covid19')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Data Pemudik</a>
            <?php if ($penduduk['id_status'] === '2' OR $penduduk['id_status'] === '3'): ?>
              <a href="#" class="btn btn-social btn-success btn-sm" data-toggle="modal" data-target="#edit-warga">
                <i class="fa fa-edit"></i>
                Ubah Data Penduduk Non Domisili
              </a>
            <?php endif ?>
          </div>

          <div class="box-header with-border">
            <h3 class="box-title">Detil Pemudik</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered" >
                <tbody>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >NIK /Nama</td>
                    <td> <?= $terdata["terdata_nama"]." / ".$terdata["terdata_info"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Alamat</td>
                    <td> <?= $individu['alamat_wilayah']; ?> </td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Tempat Tanggal Lahir (Umur)</td>
                    <td> <?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun) </td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Pendidikan</td>
                    <td> <?= $individu['pendidikan']?> </td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Warganegara / Agama</td>
                    <td> <?= $individu['warganegara']?> / <?= $individu['agama']?> </td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Asal Pemudik</td>
                    <td> <?= $terdata["asal_mudik"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Tiba Tanggal</td>
                    <td> <?= $terdata["tanggal_datang"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Tujuan Mudik</td>
                    <td> <?= $terdata["tujuan_mudik"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Durasi Mudik</td>
                    <td> <?= $terdata["durasi_mudik"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >HP</td>
                    <td> <?= $terdata["no_hp"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Email</td>
                    <td> <?= $terdata["email"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Status Covid-19</td>
                    <td> <?= $terdata["status_covid"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Keluhan Kesehatan</td>
                    <td> <?= $terdata["keluhan_kesehatan"]?></td>
                  </tr>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
                    <td> <?= $terdata["keterangan"]?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class='modal fade' id='edit-warga' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='myModalLabel'><i class='fa fa-plus text-green'></i> Ubah Penduduk Pendatang / Tidak Tetap</h4>
      </div>

      <div class='modal-body'>
        <div class="row">
          <?php include("donjo-app/views/covid19/form_isian_penduduk.php"); ?>
        </div>
      </div>

      <div class='modal-footer'>
        <button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
        <a class='btn-ok'>
          <button type="submit" class="btn btn-social btn-flat btn-success btn-sm" onclick="$('#'+'form_penduduk').submit();"><i class='fa fa-trash-o'></i> Simpan</button>
        </a>
      </div>
    </div>
  </div>
</div>