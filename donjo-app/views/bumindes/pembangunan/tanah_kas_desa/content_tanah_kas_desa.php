<div class="box box-info">
    <div class="box-header with-border">
        <a href="<?= site_url('bumindes_tanah_kas_desa/form')?>"
            class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Tambah Data Baru">
            <i class="fa fa-plus"></i>Tambah Data
        </a>       
        <a href="<?= site_url($this->controller."/ajax_cetak_tanah_kas_desa/cetak"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Tanah Kas Desa" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Tanah Kas Desa"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url($this->controller."/ajax_cetak_tanah_kas_desa/unduh"); ?>" title="Unduh Buku Tanah Kas Desa" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Tanah Kas Desa" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Tanah Kas Desa"><i class="fa fa-download"></i> Unduh</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabel-tanahkasdesa" class="table table-bordered dataTable table-hover">
                                <thead class="bg-gray">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th width="120" class="text-center">Aksi</th>
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
                                </tbody>                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('global/confirm_delete');?>

<script>
    $(document).ready(function() {
		let tabelTanahKasDesa = $('#tabel-tanahkasdesa').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [
				[2, 'asc'],
			],
			'columnDefs': [{
				'orderable': false,
				'targets': [0, 1, 3, 4, 5, 6, 7, 8, 9, 11],
			}],
			'ajax': {
				'url': "<?= site_url('bumindes_tanah_kas_desa') ?>",
				'method': 'POST',
				'data': function(d) {
				}
			},
			'columns': [
				{
					'data': null,
				},
				{
					'data': function(data) {
						return `
                            <a href="<?= site_url('bumindes_tanah_kas_desa/view_tanah_kas_desa/') ?>${data.id}" title="Lihat Data" class="btn bg-info btn-flat btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="<?= site_url('bumindes_tanah_kas_desa/form/') ?>${data.id}" title="Edit Data" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i> </a>
                            <a href="#" data-href="<?= site_url('bumindes_tanah_kas_desa/delete_tanah_desa/') ?>${data.id}" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                        	`
					}
				},
				{
                    'data': function(data) 
                    {
                       return data.nama_pemilik_asal;
                    }
				},
				{
                    'data': function(data)
                    {
                        var result =  `${data.letter_c} | ${data.persil}`;
                        return result;
                    }
				},
				{
					'data': 'kelas',
				},
				{                    
                    'data': function(data)
                    {
                        var result =  `${data.perolehan_tkd} | ${data.jenis_tkd}`;
                        return result;
                    }
				},
				{
					'data': 'lokasi'
                },
                {
					'data': 'luas'
                },
                {
                    'data': function(data) 
                    {
                        if(data.patok==1)
                        {
                            return 'Ada'
                        }else{
                            return 'Tidak Ada'
                        }
                    }
                },
                {
					'data': function(data) 
                    {
                        if(data.papan_nama==1)
                        {
                            return 'Ada'
                        }else{
                            return 'Tidak Ada'
                        }
                    }
				},
				{
					'data': 'tanggal_perolehan'
				},
				{
					'data': 'keterangan'
				},
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabelTanahKasDesa.on('draw.dt', function() {
			let PageInfo = $('#tabel-tanahkasdesa').DataTable().page.info();
			tabelTanahKasDesa.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});
    });
</script>
