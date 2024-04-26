<div class="content-wrapper">
    <section class="content-header">
        <h1>Catatan Peristiwa</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
            <li class="active">Catatan Peristiwa</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="box box-info">
            <form id="mainform" name="mainform" method="post">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if (can('h') && data_lengkap()): ?>
                                <a href="#confirm-status" title="Kembalikan Status" data-body="<?= $pertanyaan; ?>" onclick="aksiBorongan('mainform', '<?=site_url('penduduk_log/kembalikan_status_all')?>')" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-undo'></i> Kembalikan Status Terpilih</a>
                            <?php endif; ?>
                            <a href="<?= site_url("penduduk_log/ajax_cetak/{$o}/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
                            <a href="<?= site_url("penduduk_log/ajax_cetak/{$o}/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
                            <a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="row">
                            <div class="col-sm-9">
                                <select class="form-control input-sm" name="kode_peristiwa" onchange="formAction('mainform', '<?=site_url('penduduk_log/filter/kode_peristiwa')?>')">
                                    <option value="">Jenis Peristiwa</option>
                                    <?php foreach ($list_jenis_peristiwa as $data): ?>
                                        <?php if (strtolower($data['nama']) != 'hidup'): ?>
                                            <option value="<?= $data['id']?>" <?php selected($kode_peristiwa, $data['id']); ?>><?= set_ucwords($data['nama'])?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-control input-sm" name="tahun" onchange="formAction('mainform','<?= site_url('penduduk_log/tahun_bulan')?>')" width="100%">
                                    <option value="">Pilih tahun</option>
                                    <?php for ($t = $tahun_log_pertama; $t <= date('Y'); $t++): ?>
                                        <option value=<?= $t ?> <?php selected($tahun, $t); ?>><?= $t ?></option>
                                    <?php endfor; ?>
                                </select>
                                <select class="form-control input-sm" name="bulan" onchange="formAction('mainform','<?= site_url('penduduk_log/tahun_bulan')?>')" width="100%">
                                    <option value="">Pilih bulan</option>
                                    <?php foreach (bulan() as $no_bulan => $nama_bulan): ?>
                                        <option value=<?= $no_bulan ?> <?php selected($bulan, $no_bulan); ?>><?= $nama_bulan ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-control input-sm" name="sex" onchange="formAction('mainform','<?= site_url('penduduk_log/filter/sex')?>')">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <?php foreach ($list_sex as $data): ?>
                                        <option value="<?= $data['id']?>" <?php selected($sex, $data['id']); ?>><?= set_ucwords($data['nama'])?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-control input-sm" name="agama" onchange="formAction('mainform','<?= site_url('penduduk_log/filter/agama')?>')">
                                    <option value="">Pilih Agama</option>
                                    <?php foreach ($list_agama as $data): ?>
                                        <option value="<?= $data['id']?>" <?php selected($agama, $data['id']); ?>><?= set_ucwords($data['nama'])?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('penduduk_log/dusun')?>')">
                                    <option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
                                    <?php foreach ($list_dusun as $data): ?>
                                        <option value="<?= $data['dusun']?>" <?php selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun'])?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($dusun): ?>
                                    <select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('penduduk_log/rw')?>')" >
                                        <option value="">Pilih RW</option>
                                        <?php foreach ($list_rw as $data): ?>
                                            <option value="<?= $data['rw']?>" <?php selected($rw, $data['rw']); ?>><?= set_ucwords($data['rw'])?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                                <?php if ($rw): ?>
                                    <select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('penduduk_log/rt')?>')">
                                        <option value="">Pilih RT</option>
                                        <?php foreach ($list_rt as $data): ?>
                                            <option value="<?= $data['rt']?>" <?php selected($rt, $data['rt']); ?>><?= set_ucwords($data['rt'])?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm pull-right">
                                    <input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url('penduduk_log/filter/cari')?>');$('#'+'mainform').submit();}">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url('penduduk_log/filter/cari')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <?php if ($judul_statistik) : ?>
                                        <h5 class="box-title text-center"><b><?= $judul_statistik; ?></b></h5>
                                    <?php endif; ?>
                                    <table class="table table-bordered dataTable table-hover">
                                        <thead class="bg-gray disabled color-palette">
                                            <tr>
                                                <th>No</th>
                                                <th><input type="checkbox" id="checkall"/></th>
                                                <th>Aksi</th>
                                                <th>Foto</th>
                                                <?php if ($o == 2): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/1")?>">NIK <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                <?php elseif ($o == 1): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/2")?>">NIK <i class='fa fa-sort-desc fa-sm'></i></span></a></th>
                                                <?php else: ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/1")?>">NIK <i class='fa fa-sort fa-sm'></i></a></th>
                                                <?php endif; ?>
                                                <?php if ($o == 4): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                <?php elseif ($o == 3): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                                <?php else: ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
                                                <?php endif; ?>
                                                <?php if ($o == 6): ?>
                                                    <th nowrap><a href="<?= site_url("penduduk_log/index/{$p}/5")?>">No. KK / Nama KK <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                <?php elseif ($o == 5): ?>
                                                    <th nowrap><a href="<?= site_url("penduduk_log/index/{$p}/6")?>">No. KK / Nama KK <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                                <?php else: ?>
                                                    <th nowrap><a href="<?= site_url("penduduk_log/index/{$p}/5")?>">No. KK / Nama KK <i class='fa fa-sort fa-sm'></i></a></th>
                                                <?php endif; ?>
                                                <th><?= ucwords($this->setting->sebutan_dusun)?></th>
                                                <th>RW</th>
                                                <th>RT</th>
                                                <?php if ($o == 8): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/7")?>">Umur <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                <?php elseif ($o == 7): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/8")?>">Umur <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                                <?php else: ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/7")?>">Umur <i class='fa fa-sort fa-sm'></i></a></th>
                                                <?php endif; ?>
                                                <th>Status Menjadi</th>
                                                <?php if ($o == 10): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/9")?>">Tanggal Peristiwa <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                <?php elseif ($o == 9): ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/10")?>">Tanggal Peristiwa <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                                <?php else: ?>
                                                    <th><a href="<?= site_url("penduduk_log/index/{$p}/9")?>">Tanggal Peristiwa <i class='fa fa-sort fa-sm'></i></a></th>
                                                <?php endif; ?>
                                                <th>Tanggal Lapor</th>
                                                <th>Catatan Peristiwa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($main as $data): ?>
                                            <?php if ($data['created_at']): ?>
                                                <tr>
                                                    <td><?= $data['no']?></td>
                                                    <td>
                                                        <input type="checkbox" name="id_cb[]" value="<?= $data['id_log']?>" />
                                                    </td>
                                                    <td class="aksi">
                                                        <a href="<?= site_url("penduduk_log/edit/{$p}/{$o}/{$data['id_log']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Log Penduduk" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Log Penduduk" ><i class="fa fa-edit"></i></a>
                                                        <?php if ($data['kode_peristiwa'] != 5 && $data['kode_peristiwa'] != 1 && $data['kode_peristiwa'] != 6 && $data['kode_peristiwa']): ?>
                                                            <?php if (data_lengkap()) : ?>
                                                                <a href="#" data-href="<?= site_url("penduduk_log/kembalikan_status/{$data['id_log']}")?>" class="btn bg-olive btn-flat btn-sm" title="Kembalikan Status"  data-remote="false"  data-toggle="modal" data-target="#confirm-status"><i class="fa fa-undo"></i></a>
                                                                <?php if ($data['kembali_datang'] && $data['is_log_pergi_terakhir'] && in_array($data['status_dasar'], [3, 6])): ?>
                                                                    <a href="<?= site_url("penduduk_log/ajax_kembalikan_status_pergi/{$data['id_log']}")?>" class="btn bg-purple btn-flat btn-sm" title="Datang Kembali"  data-remote="false"  data-toggle="modal" data-target="#modalBox" data-title="Kembalikan Penduduk"><i class="fa fa-angle-double-left"></i></a>
                                                                <?php endif ?>
                                                            <?php endif ?>
                                                        <?php endif ?>
                                                        <?php if ($data['kode_peristiwa'] == 2) : ?>
                                                            <a target="_blank" href="<?= site_url("penduduk_log/dokumen/{$data['id_log']}") ?>" class="btn btn-info btn-flat btn-sm" title="Lihat File Akta Kematian"><i class="fa fa-eye"></i></a>
                                                        <?php endif ?>

                                                            <?php
                                                                switch($data['kode_peristiwa']) {
                                                                    case 1:
                                                                        $suratTerkait = json_decode(setting('surat_kelahiran_terkait_penduduk'), 1);
                                                                        break;

                                                                    case 2:
                                                                        $suratTerkait = json_decode(setting('surat_kematian_terkait_penduduk'), 1);
                                                                        break;

                                                                    case 3:
                                                                        $suratTerkait = json_decode(setting('surat_pindah_keluar_terkait_penduduk'), 1);
                                                                        break;

                                                                    case 4:
                                                                        $suratTerkait = json_decode(setting('surat_hilang_terkait_penduduk'), 1);
                                                                        break;

                                                                    case 5:
                                                                        $suratTerkait = json_decode(setting('surat_pindah_masuk_terkait_penduduk'), 1);
                                                                        break;

                                                                    case 6:
                                                                        $suratTerkait = json_decode(setting('surat_pergi_terkait_penduduk'), 1);
                                                                        break;
                                                                }
                                                            ?>
                                                            <?php if($suratTerkait): ?>
                                                                <?php foreach($suratTerkait as $item): ?>
                                                                    <a target="_blank" href="<?= site_url("surat/form/{$item}") ?>#<?= $data['id'] ?>#<?= $data['nik'] ?>#<?= $data['nama'] ?>" class="btn btn-social bg-purple btn-flat btn-sm" title="<?=  str_replace('-', ' ', $item) ?>"><i class="fa fa-file-word-o"></i><?=  str_replace('-', ' ', $item) ?></a>
                                                                <?php endforeach ?>
                                                            <?php endif ?>
                                                    </td>
                                                    <td class="padat">
                                                        <img class="penduduk_kecil" src="<?= AmbilFoto($data['foto'], '', $data['id_sex']); ?>" alt="Foto Penduduk"/>
                                                    </td>
                                                    <td>
                                                        <a href="<?= site_url("penduduk/detail/{$p}/{$o}/{$data['id']}")?>" id="test" name="<?= $data['id']?>"><?= $data['nik']?></a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= site_url("penduduk/detail/{$p}/{$o}/{$data['id']}")?>"><?= strtoupper($data['nama'])?></a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= site_url("keluarga/kartu_keluarga/{$data['id_kk']}")?>"><?= $data['no_kk']?> </a> <br>
                                                        <?= $data['nama_kk']?>
                                                    </td>
                                                    <td><?= $data['dusun']?></td>
                                                    <td><?= $data['rw']?></td>
                                                    <td><?= $data['rt']?></td>
                                                    <td><?= $data['umur_pada_peristiwa']?></td>
                                                    <td><?= $data['nama_peristiwa']?></td>
                                                    <td><?= tgl_indo($data['tgl_peristiwa'])?></td>
                                                    <td><?= tgl_indo($data['tgl_lapor'])?></td>
                                                    <td><?= $data['catatan']?></td></td>
                                                </tr>
                                            <?php else: ?>
                                                <tr>
                                                    <td><?= $data['no']?></td>
                                                    <td>
                                                        <input type="checkbox" name="id_cb[]" value="<?= $data['id_log']?>" />
                                                    </td>
                                                    <td class="aksi"></td>
                                                    <td class="padat">
                                                        <img class="penduduk_kecil" src="<?= AmbilFoto($data['foto'], '', $data['id_sex']); ?>" alt="Foto Penduduk"/>
                                                    </td>
                                                    <td><?= $data['nik_hapus']?></td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td><?= tgl_indo($data['tgl_peristiwa'])?></td>
                                                    <td><?= tgl_indo($data['tgl_lapor'])?></td>
                                                    <td>Data telah dihapus.</td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
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
    </section>
</div>
<?php $this->load->view('global/konfirmasi', ['pertanyaan' => 'Apakah Anda yakin ingin mengembalikan status data penduduk ini?<br> Perubahan ini akan mempengaruhi laporan penduduk bulanan.']); ?>
<script>
    $(function() {
        var keyword = <?= $keyword ?> ;
        $("#cari").autocomplete({
            source: keyword,
            maxShowItems: 10,
        });
    });
</script>
