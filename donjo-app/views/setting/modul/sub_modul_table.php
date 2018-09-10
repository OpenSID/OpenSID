<div class="content-wrapper">
  <section class="content-header">
		<h1>Manajemen Sub modul</h1>
		<ol class="breadcrumb">
      <li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('modul')?>"> Daftar Modul</a></li>
			<li class="active">Manajemen Sub Modul</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
    <form id="mainform" name="mainform" action="" method="post">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
						  <a href="<?= site_url()?>modul" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Modul</a>
					  </div>
            <div class="box-header with-border">
						 <strong> Modul Utama : <?=$modul['modul']?></strong>
					  </div>
            <div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable table-hover">
                      <thead class="bg-gray disabled color-palette">
                        <tr>
                          <th><input type="checkbox" id="checkall"/></th>
                          <th>No</th>
                          <th>Aksi</th>
                          <th width="50%">Nama Sub Modul</th>
                          <th>Aktif</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($submodul as $data): ?>
                          <tr>
                            <td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
                            <td><?=$data['no']?></td>
                            <td>
                              <a href="<?=site_url("modul/form/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data" ><i class="fa fa-edit"></i></a>
                            </td>
                            <td><?=$data['modul']?></td>
                            <td>
                              <?php	if ($data['aktif']==1): ?>Aktif<?php else: ?>Tidak Aktif <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
		</form>
	</section>
</div>
