<div class="box box-info">
    <div class="box-header with-border">
    <a href="<?= site_url($this->controller . "/clear/$kategori") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
    </div>
    <div class="box-body with-border">
        <div class="row">
            <div class="col-sm-12">
                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <form id="mainform" action="<?=site_url("bumindes_arsip/$kategori")?>" name="mainform" method="post">
                        <div class="row">
                            <div class="col-sm-8">
                                <select class="form-control input-sm" name="jenis" onchange="$('#mainform').submit()">
                                    <option value="0">Pilih Jenis Dokumen</option>
                                    <?php foreach($list_jenis as $key => $jenis):?>
                                        <option value="<?=$key?>" <?=($this->session->data_filter_jenis==$key)? 'selected' : '' ?>><?=strtoupper(str_replace('_', ' ',$jenis))?></option>
                                    <?php endforeach ?>
                                </select>
                                <select class="form-control input-sm" name="tahun" onchange="$('#mainform').submit()">
                                    <option value="0">Pilih Tahun</option>
                                    <?php foreach($list_tahun as $tahun): ?>
                                        <option value="<?=$tahun?>" <?=($this->session->data_filter_tahun==$tahun) ? 'selected': ''?>><?=$tahun?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div class="box-tools">
                                    <div class="input-group input-group-sm pull-right">
                                        <input name="cari" id="cari" class="form-control" placeholder="<?=($sess_cari=$this->session->data_filter_cari)? $sess_cari:'Cari...' ?>" type="text" value="">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable table-hover">
										<thead class="bg-gray color-palette">
											<tr>
												<th>NO</th>
												<th width="10%">Nomor Dokumen</th>
												<th>Tanggal Dokumen</th>
												<th width="25%">Nama Dokumen</th>
                                                <th>Jenis Dokumen</th>
												<th>Lokasi Arsip</th>
												<th width="20%">Tindakan</th>
											</tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($main): ?>
                                            <?php foreach ($main as $key => $data): ?>
                                                <tr>
                                                    <td class="padat"><?= ($key+$paging->offset+1); ?></td>
                                                    <td><?= $data['no']?></td>
                                                    <td><?= $data['tanggal_dokumen']?></td>
                                                    <td><?= $data['nama_dokumen']?></td>
                                                    <td><?= strtoupper(str_replace('_', ' ', $data['jenis']))?></td>
                                                    <td><?= $data['lokasi_arsip']?></td>
                                                    <td align="right">
                                                        <?php $kat = ($kategori=='surat_desa') ? $data['jenis'] : $kategori ?>
                                                        <?php if($kategori == 'layanan_surat'):?>
                                                            <a href="<?= site_url('keluar/unduh/rtf/').$data['id']?>" class="btn bg-black btn-flat btn-sm" title="Unduh Berkas"><i class="fa fa-download">&nbsp;</i></a>
                                                            <?php if($data['lampiran']):?>
                                                                <a href="<?= site_url('keluar/unduh/lampiran/').$data['id']?>" class="btn bg-blue btn-flat btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip">&nbsp;</i></a>
                                                            <?php endif ?>
                                                        <?php else: ?>
                                                            <a href="<?= site_url('bumindes_arsip/tindakan_lihat/').$kat.'/'.$data['id'].'/lihat' ?>" target="_blank" class="btn bg-blue btn-flat btn-sm" title="Lihat Berkas"><i class="fa fa-eye">&nbsp;</i></a>
                                                            <a href="<?= site_url('bumindes_arsip/tindakan_lihat/').$kat.'/'.$data['id'].'/unduh' ?>" class="btn bg-black btn-flat btn-sm" title="Unduh Berkas"><i class="fa fa-download">&nbsp;</i></a>
                                                        <?php endif ?>
                                                        <a href="<?= site_url('bumindes_arsip/tindakan_ubah/').$kat.'/'.$data['id'].'/ubah/'.$page?>" class="btn bg-yellow btn-flat btn-sm" title="Ubah Lokasi Arsip" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Lokasi Arsip"><i class="fa fa-edit">&nbsp;</i></a>
                                                        <a href="<?= site_url($data['modul_asli'])?>" class="btn bg-green btn-flat btn-sm" title="Tampilkan di modul aslinya"><i class="fa fa-list">&nbsp;</i></a>
                                                        <a href="<?= site_url('bumindes_arsip/tindakan_ubah/').$kat.'/'.$data['id'].'/hapus/'.$page ?>" class="btn bg-red btn-flat btn-sm" title="Hapus Dokumen" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Hapus Dokumen"><i class="fa fa-trash-o">&nbsp;</i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td class="text-center" colspan="7">Data Tidak Tersedia</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php $this->load->view('global/paging'); ?>
                </div>
            </div>
        </div>
    </div>
</div>