<div class="box box-info">
    <div class="box-header with-border">
        <a href="<?= site_url('bumindes_pembangunan/form_tanah_kas_desa')?>"
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
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabel4" class="table table-bordered dataTable table-hover">
                                <thead class="bg-gray">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Aksi</th>
                                        <th class="text-center">Asal</th>
                                        <th class="text-center">No. Letter C / Persil</th>
                                        <th class="text-center">Kelas</th>
                                        <th class="text-center">Perolehan / Jenis TKD</th>
                                        <th class="text-center">Lokasi</th>
                                        <th class="text-center">Luas (M<sup>2</sup>)</th>
                                        <th class="text-center">Patok Batas</th>
                                        <th class="text-center">Papan Nama</th>
                                        <th class="text-center">Tanggal Perolehan</th>
                                        <th class="text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($main as $data): ?>
                                    <?php if ($data->status == "1"): ?>
                                    <tr style='background-color:#cacaca'>
                                        <?php else: ?>
                                    <tr>
                                        <?php endif; ?>
                                        <td></td>
                                        <td nowrap>
                                            <!-- <?php if ($data->status == "0"): ?>
                                            <a href="<?= site_url('inventaris_tanah/form_mutasi/'.$data->id); ?>"
                                                title="Mutasi Data" class="btn bg-olive btn-flat btn-sm"><i
                                                    class="fa fa-external-link-square"></i></a>
                                            <?php endif; ?> -->
                                            <a href="<?= site_url('bumindes_pembangunan/view_tanah_kas_desa/'.$data->id); ?>"
                                                title="Lihat Data" class="btn bg-info btn-flat btn-sm"><i
                                                    class="fa fa-eye"></i></a>
                                            <a href="<?= site_url('bumindes_pembangunan/edit_tanah_kas_desa/'.$data->id); ?>"
                                                title="Edit Data" class="btn bg-orange btn-flat btn-sm"><i
                                                    class="fa fa-edit"></i> </a>
                                            <a href="#"
                                                data-href="<?= site_url("bumindes_pembangunan/delete_tanah_kas_desa/$data->id")?>"
                                                class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal"
                                                data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                        <td><?= $data->nama_pemilik_asal;?></td>
                                        <td><?= $data->letter_c;?><br><?= $data->persil;?></td>
                                        <td><?= $data->kelas;?></td>
                                        <td><?= $data->perolehan_tkd;?><br><?= $data->jenis_tkd;?></td>
                                        <td><?= $data->lokasi;?></td>
                                        <td><?= $data->luas;?></td>
                                        <td><?php if($data->patok==1){echo "Ada";}else{echo "Tidak Ada";}?></td>                                      
                                        <td><?php if($data->papan_nama==1){echo "Ada";}else{echo "Tidak Ada";}?></td>                                                                                                             
                                        <td><?= $data->tanggal_perolehan;?></td>                                      
                                        <td><?= $data->keterangan;?></td>                                      
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="unduhBox" class="modal fade" role="dialog" style="padding-top:30px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Unduh Buku Tanah Kas Desa</h4>
                    </div>
                    <formtarget="_blank" class="form-horizontal" method="get">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="container-fluid">
                                    <label class="control-label required" for="tgl_cetak">Tanggal Unduh</label>                                    
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control input-sm required" id="tgl_1" name="tgl_cetak" type="text" value="<?= date('d-m-Y');?>">
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"
                                data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_download"
                                name="form_download" data-dismiss="modal"><i class='fa fa-check'></i> Unduh</button>
                        </div>

                        </form>
                </div>
            </div>
        </div>
        <div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cetak Buku Tanah Kas Desa</h4>
                    </div>
                    <formtarget="_blank" class="form-horizontal" method="get">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="container-fluid">
                                    <label class="control-label required" for="tgl_cetak">Tanggal Cetak</label>                                    
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control input-sm required" id="tgl_2" name="tgl_cetak" type="text" value="<?= date('d-m-Y');?>">
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"
                                data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_cetak"
                                name="form_cetak" data-dismiss="modal"><i class='fa fa-check'></i> Cetak</button>
                        </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('global/confirm_delete');?>
<script>
    $("#form_cetak").click(function (event) {
        var link = '<?= site_url("bumindes_pembangunan/cetak_tanah_kas_desa"); ?>'+ '/' + $('#tgl_2').val()+ '/cetak';
        window.open(link, '_blank');
    });
    $("#form_download").click(function (event) {
        var link = '<?= site_url("bumindes_pembangunan/cetak_tanah_kas_desa"); ?>'+ '/' + $('#tgl_1').val()+ '/unduh';
        window.open(link, '_blank');
    });
</script>