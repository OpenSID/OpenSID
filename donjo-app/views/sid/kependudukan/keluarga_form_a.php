<div class="content-wrapper">
	<section class="content-header">
		<h1>Biodata Anggota Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
			<li class="active">Biodata Anggota Keluarga</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
    <form id="mainform" name="mainform" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3">
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <?php if ($penduduk['foto']): ?>
                    <img class="penduduk profile-user-img img-responsive img-circle" src="<?= AmbilFoto($penduduk['foto'])?>" alt="Foto">
                  <?php else: ?>
                    <img class="penduduk profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/user_pict/kuser.png" alt="Foto">
                  <?php endif; ?>
                  <br/>
                  <p class="text-muted text-center"> (Kosongkan jika tidak ingin mengubah foto)</p>
                  <br/>
                  <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="file_path" name="foto">
                    <input type="file" class="hidden" id="file" name="foto">
                    <input type="hidden" name="old_foto" value="<?= $penduduk['foto']?>">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class='box box-primary'>
                <div class='box-body'>
                  <div class="row">
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>DATA KELUARGA :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label >No. KK </label>
                        <input class="form-control input-sm" type="text" value="<?= $kk['no_kk']?>" disabled></input>
                        <input name="id_kk" type="hidden" value="<?= $id_kk?>">
                        <input name="kk_level" type="hidden" value="0">
                        <input name="id_cluster" type="hidden" value="<?= $kk['id_cluster']?>">
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label>Kepala KK</label>
                        <input class="form-control input-sm" type="text" value="<?= $kk['nama']?>" disabled></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class='form-group'>
                        <label>Alamat </label>
                        <input class="form-control input-sm" type="text" value="<?= $kk['alamat']?> Dusun <?= $kk['dusun']?> - RW <?= $kk['rw']?> - RT <?= $kk['rt']?>" disabled></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>DATA ANGGOTA :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="nik">NIK </label>
                        <input id="nik"  name="nik" class="form-control input-sm required" type="text" placeholder="Nomor NIK" value="<?= $penduduk['nik']?>"></input>
                        <input name="nik_lama" type="hidden" value="<?= $_SESSION['nik_lama']?>"/>
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="nama">Nama Lengkap <code> (Tanpa Gelar )</code> </label>
                        <input id="nama" name="nama" class="form-control input-sm required" type="text" placeholder="Nama Lengkap" value="<?= strtoupper($penduduk['nama'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class='form-group'>
                        <label for="nama">Status Kepemilikan KTP</label>
                        <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                            <thead class="bg-gray disabled color-palette">
                              <tr>
                                <th width='50%'>Wajib KTP</th>
                                <th>KTP Elektrtonik</th>
                                <th>Status Rekam</th>
                                <th>Tag ID Card</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><?= strtoupper($penduduk['wajib_ktp'])?></td>
                                <td>
                                  <select name="ktp_el" class="form-control input-sm">
                                  <option value="">Pilih KTP-EL</option>
                                  <?php foreach ($ktp_el as $id => $nama): ?>
                                    <option value="<?= $id?>" <?php if (strtolower($penduduk['ktp_el'])==$nama): ?>selected<?php endif; ?>><?= strtoupper($nama)?></option>
                                  <?php endforeach;?>
                                  </select>
                                </td>
                                <td>
                                  <select name="status_rekam" class="form-control input-sm">
                                  <option value="">Pilih Status Rekam</option>
                                  <?php foreach ($status_rekam as $id => $nama): ?>
                                    <option value="<?= $id?>" <?php if (strtolower($penduduk['status_rekam'])==$nama): ?>selected<?php endif; ?>><?= strtoupper($nama)?></option>
                                  <?php endforeach;?>
                                  </select>
                                </td>
                                <td>
                                  <input name="tag_id_card" class="form-control input-sm digits" type="text" minlength="10" maxlength="15" placeholder="Tag Id Card" value="<?= $penduduk['tag_id_card']?>"></input>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="no_kk_sebelumnya">Nomor KK Sebelumnya</label>
                        <input id="no_kk_sebelumnya" name="no_kk_sebelumnya" class="form-control input-sm" type="text" placeholder="No KK Sebelumnya" value="<?= strtoupper($penduduk['no_kk_sebelumnya'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="kk_level">Hubungan Dalam Keluarga</label>
                        <select class="form-control input-sm required" name="kk_level">
                          <option value="">Pilih Hubungan Keluarga</option>
                          <?php foreach ($hubungan as $data): ?>
                            <option value="<?= $data['id']?>"<?php if ($penduduk['kk_level']==$data['id']): ?> selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="sex">Jenis Kelamin </label>
                        <select class="form-control input-sm required" name="sex">
                          <option value="">Jenis Kelamin</option>
                          <option value="1" <?php if ($penduduk['id_sex'] == '1'): ?>selected<?php endif; ?>>Laki-Laki</option>
                          <option value="2" <?php if ($penduduk['id_sex'] == '2'): ?>selected<?php endif; ?> >Perempuan</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-7'>
                      <div class='form-group'>
                        <label for="agama_id">Agama</label>
                        <select class="form-control input-sm required" name="agama_id">
                          <option value="">Pilih Agama</option>
                          <?php foreach ($agama as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['agama_id']==$data['id']): ?> selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-5'>
                      <div class='form-group'>
                        <label for="status">Status Penduduk </label>
                        <select class="form-control input-sm required" name="status">
                          <option value="1" <?php if ($penduduk['status'] == "TETAP" OR $penduduk['status'] == "1" OR $penduduk['status'] == ""): ?>selected<?php endif; ?>>Tetap</option>
                          <option value="2" <?php if ($penduduk['status'] == "TIDAK AKTIF" OR $penduduk['status'] == "2"): ?>selected<?php endif; ?>>Tidak Tetap</option>
                          <option value="3" <?php if ($penduduk['status'] == "PENDATANG" OR $penduduk['status'] == "3"): ?>selected<?php endif; ?> >Pendatang</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>DATA KELAHIRAN :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="akta_lahir">Nomor Akta Kelahiran </label>
                        <input id="akta_lahir" name="akta_lahir" class="form-control input-sm" type="text" placeholder="Nomor Akta Kelahiran" value="<?= $penduduk['akta_lahir']?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="tempatlahir">Tempat Lahir</label>
                        <input id="tempatlahir" name="tempatlahir" class="form-control input-sm" type="text" placeholder="Tempat Lahir" value="<?= strtoupper($penduduk['tempatlahir'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="tanggallahir">Tanggal Lahir</label>
                        <div class="input-group input-group-sm date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input class="form-control input-sm pull-right" id="tgl_1" name="tanggallahir" type="text" value="<?= $penduduk['tanggallahir']?>">
                        </div>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="waktulahir">Waktu Kelahiran </label>
                        <div class="input-group input-group-sm date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input class="form-control input-sm pull-right" id="jammenit_1" name="waktu_lahir" type="text" value="<?= $penduduk['waktu_lahir']?>">
                        </div>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="tempat_dilahirkan">Tempat Dilahirkan</label>
                        <select class="form-control input-sm" name="tempat_dilahirkan">
                          <option value="">Pilih Tempat Dilahirkan</option>
                          <?php foreach ($tempat_dilahirkan as $id => $nama): ?>
                            <option value="<?= $id?>" <?php if ($penduduk['tempat_dilahirkan']==$id): ?>selected<?php endif; ?>><?= strtoupper($nama)?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-12'>
					            <div class='row'>
                        <div class='col-sm-4'>
                          <div class='form-group'>
                            <label for="jenis_kelahiran">Jenis Kelahiran</label>
                            <select class="form-control input-sm" name="jenis_kelahiran">
                              <option value="">Pilih Jenis Kelahiran</option>
                              <?php foreach ($jenis_kelahiran as $id => $nama): ?>
                                <option value="<?= $id?>" <?php if ($penduduk['jenis_kelahiran']==$id): ?>selected<?php endif; ?>><?= strtoupper($nama)?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class='col-sm-4'>
                          <div class='form-group'>
                            <label for="kelahiran_anak_ke">Anak Ke <code>Isi dengan angka</code></label>
                            <input id="kelahiran_anak_ke" name="kelahiran_anak_ke" class="form-control input-sm" type="text" placeholder="Anak Ke" value="<?= strtoupper($penduduk['kelahiran_anak_ke'])?>"></input>
                          </div>
                        </div>
                        <div class='col-sm-4'>
                          <div class='form-group'>
                            <label for="penolong_kelahiran">Penolong Kelahiran</label>
                            <select class="form-control input-sm" name="penolong_kelahiran">
                              <option value="">Pilih Penolong Kelahiran</option>
                              <?php foreach ($penolong_kelahiran as $id => $nama): ?>
                                <option value="<?= $id?>" <?php if ($penduduk['penolong_kelahiran']==$id): ?>selected<?php endif; ?>><?= strtoupper($nama)?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                      </div>
				            </div>
                    <div class='col-sm-12'>
                      <div class='row'>
                        <div class='col-sm-4'>
                          <div class='form-group'>
                            <label for="berat_lahir">Berat Lahir <code>( Kg )</code></label>
                            <input id="berat_lahir" name="berat_lahir" class="form-control input-sm" type="text" placeholder="Berat Lahir" value="<?= strtoupper($penduduk['berat_lahir'])?>"></input>
                          </div>
                        </div>
                        <div class='col-sm-4'>
                          <div class='form-group'>
                            <label for="panjang_lahir">Panjang Lahir <code>( cm )</code></label>
                            <input id="panjang_lahir" name="panjang_lahir" class="form-control input-sm" type="text" placeholder="Panjang Lahir" value="<?= strtoupper($penduduk['panjang_lahir'])?>"></input>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>PENDIDIKAN DAN PEKERJAAN :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="pendidikan_kk_id">Pendidikan Dalam KK </label>
                        <select class="form-control input-sm" name="pendidikan_kk_id">
                          <option value="">Pilih Pendidikan (Dalam KK) </option>
                          <?php foreach ($pendidikan_kk as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['pendidikan_kk_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh </label>
                        <select class="form-control input-sm" name="pendidikan_sedang_id" >
                          <option value="">Pilih Pendidikan</option>
                          <?php foreach ($pendidikan_sedang as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['pendidikan_sedang_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="pekerjaan_id">Pekerjaaan</label>
                        <select class="form-control input-sm required" name="pekerjaan_id">
                          <option value="">Pilih Pekerjaan</option>
                          <?php foreach ($pekerjaan as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['pekerjaan_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>DATA KEWARGANEGARAAN :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="warganegara_id">Status Warga Negara</label>
                        <select class="form-control input-sm required" name="warganegara_id">
                          <option value="">Pilih Warga Negara</option>
                          <?php foreach ($warganegara as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['warganegara_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="dokumen_pasport">Nomor Paspor </label>
                        <input id="dokumen_pasport"  name="dokumen_pasport" class="form-control input-sm" type="text" placeholder="Nomor Paspor" value="<?= strtoupper($penduduk['dokumen_pasport'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="tanggalpasport">Tgl Berakhir Paspor</label>
                        <div class="input-group input-group-sm date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input class="form-control input-sm pull-right" id="tgl_2" name="dokumen_pasport" type="text" value="<?= $penduduk['dokumen_pasport']?>">
                        </div>
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="dokumen_kitas">Nomor KITAS/KITAP </label>
                        <input id="dokumen_kitas"  name="dokumen_kitas" class="form-control input-sm" type="text" placeholder="Nomor KITAS/KITAP" value="<?= strtoupper($penduduk['dokumen_kitas'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>DATA ORANG TUA :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="ayah_nik"> NIK Ayah </label>
                        <input id="ayah_nik"  name="ayah_nik"  class="form-control input-sm" type="text" placeholder="Nomor NIK Ayah"  value="<?= $penduduk['ayah_nik']?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="nama_ayah">Nama Ayah </label>
                        <input id="nama_ayah" name="nama_ayah" class="form-control input-sm" type="text" placeholder="Nama Ayah" value="<?= strtoupper($penduduk['nama_ayah'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="ibu_nik"> NIK Ibu </label>
                        <input id="ibu_nik"  name="ibu_nik"  class="form-control input-sm" type="text" placeholder="Nomor NIK Ibu" value="<?= $penduduk['ibu_nik']?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="nama_ibu">Nama Ibu </label>
                        <input id="nama_ibu" name="nama_ibu" class="form-control input-sm" type="text" placeholder="Nama Ibu"  value="<?= strtoupper($penduduk['nama_ibu'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>ALAMAT :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>

                      <div class='form-group'>
                        <label for="lokasi">Lokasi Tempat Tinggal </label>

                        <a href="<?=site_url("penduduk/ajax_penduduk_maps/$p/$o/$penduduk[id]/1")?>" title="Lokasi <?= $penduduk['nama']?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Lokasi <?= $penduduk['nama']?>" class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map-marker'></i> Cari Lokasi Tempat Tinggal</a>

                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="telepon"> Nomor Telepon </label>
                        <input id="telepon"  name="telepon"  class="form-control input-sm" type="text" placeholder="Nomor Telepon"  value="<?= $penduduk['telepon']?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class='form-group'>
                        <label for="alamat_sebelumnya">Alamat Sebelumnya </label>
                        <input id="alamat_sebelumnya" name="alamat_sebelumnya" class="form-control input-sm" type="text" placeholder="Alamat Sebelumnya" value="<?= strtoupper($penduduk['alamat_sebelumnya'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class='form-group'>
                        <label for="alamat_sekarang">Alamat Sekarang </label>
                        <input id="alamat_sekarang" name="alamat_sekarang" class="form-control input-sm" type="text" placeholder="Alamat Sekarang" value="<?= strtoupper($penduduk['alamat_sekarang'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>STATUS PERKAWINAN :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="status_kawin">Status Perkawinan</label>
                        <select class="form-control input-sm required" name="status_kawin">
                          <option value="">Pilih Status Perkawinan</option>
                          <?php foreach ($kawin as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['status_kawin']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <?php if ($penduduk['agama_id']==0 OR is_null($penduduk['agama_id'])): ?>
                          <label for="akta_perkawinan">No. Akta Nikah (Buku Nikah)/Perkawinan </label>
                        <?php elseif ($penduduk['agama_id']==1): ?>
                          <label for="akta_perkawinan">No. Akta Nikah (Buku Nikah) </label>
                        <?php else: ?>
                          <label for="akta_perkawinan">No. Akta Perkawinan </label>
                        <?php endif; ?>
                          <input id="akta_perkawinan" name="akta_perkawinan" class="form-control input-sm" type="text" placeholder="Nomor Akta Perkawinan" value="<?= $penduduk['akta_perkawinan']?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="tanggalperkawinan">Tanggal Perkawinan</label>
                        <div class="input-group input-group-sm date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input class="form-control input-sm pull-right" id="tgl_3" name="tanggalperkawinan" type="text" value="<?= $penduduk['tanggalperkawinan']?>">
                        </div>
                      </div>
                    </div>
                    <div class='col-sm-8'>
                      <div class='form-group'>
                        <label for="akta_perceraian">Akta Perceraian </label>
                        <input id="akta_perceraian" name="akta_perceraian" class="form-control input-sm" type="text" placeholder="Akta Perceraian" value="<?= strtoupper($penduduk['akta_perceraian'])?>"></input>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="tanggalperceraian">Tanggal Perceraian </label>
                        <div class="input-group input-group-sm date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input class="form-control input-sm pull-right" id="tgl_4" name="tanggalperceraian" type="text" value="<?= $penduduk['tanggalperceraian']?>">
                        </div>
                      </div>
                    </div>
                    <div class='col-sm-12'>
                      <div class="form-group subtitle_head">
                        <label class="text-right"><strong>DATA KESEHATAN :</strong></label>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="golongan_darah_id">Golongan Darah</label>
                        <select class="form-control input-sm" name="golongan_darah_id">
                          <option value="">Pilih Golongan Darah</option>
                          <?php foreach ($golongan_darah as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['golongan_darah_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="cacat_id">Cacat</label>
                        <select class="form-control input-sm" name="cacat_id" >
                          <option value="">Pilih Jenis Cacat</option>
                          <?php foreach ($cacat as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['cacat_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="sakit_menahun_id">Sakit Menahun</label>
                        <select class="form-control input-sm" name="sakit_menahun_id">
                          <option value="">Pilih Sakit Menahun</option>
                          <?php foreach ($sakit_menahun as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['sakit_menahun_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="cara_kb_id">Akseptor KB</label>
                        <select class="form-control input-sm" name="cara_kb_id" >
                          <option value="">Pilih Cara KB Saat Ini</option>
                          <?php foreach ($cara_kb as $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($penduduk['cara_kb_id']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    <div class='col-sm-4'>
                      <div class='form-group'>
                        <label for="hamil">Status Kehamilan </label>
                        <select class="form-control input-sm" name="hamil">
                          <option value="">Pilih Status Kehamilan</option>
                          <option value="0" <?php if ($penduduk['hamil'] == '0'): ?>selected<?php endif; ?>>Tidak Hamil</option>
                          <option value="1" <?php if ($penduduk['hamil'] == '1'): ?>selected<?php endif; ?> >Hamil</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class='box-footer'>
                  <div class='col-xs-12'>
                    <button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
                    <button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
                  </div>
                </div>
                <div  class="modal fade" id="rumah-penduduk" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class='modal-dialog'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Cari Lokasi Tempat Tinggal</h4>
                      </div>
                      <div class="fetched-data"></div>
                    </div>
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
