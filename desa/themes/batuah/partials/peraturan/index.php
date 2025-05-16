<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="col-default">
	<div class="bodypage bgwhite bordergrey1">
		<div class="headstat-lebar flexcenter">
			<img src="<?= base_url("$this->theme_folder/$this->theme/images/peraturan.png") ?>"/>
			<div>
			<h1 class="color-1">Produk Hukum</h1>
			<h2><?= ucwords($this->setting->sebutan_desa); ?></h2>
			</div>
		</div>
		<div class="pagebox">
			<div class="rowsame margin-minlr-5">
				<div class="colsame-2">
					<label style="font-size:95%;margin:0;padding:0;line-height:1.2;">Tahun</label>
					<select class="form-control input-sm" id="tahun" name="tahun">
						<option selected value="">Semua Tahun</option>
						<?php foreach ($pilihan_tahun as $tahun) : ?>
							<option value="<?= $tahun ?>"><?= $tahun ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="colsame-2">
					<label style="font-size:95%;margin:0;padding:0;line-height:1.2;"> Kategori</label>
					<select class="form-control input-sm" id="kategori" name="kategori">
						<option selected value="">Semua Kategori</option>
						<?php foreach ($pilihan_kategori as $id => $kategori) : ?>
							<option value="<?= $id ?>"><?= $kategori ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered customtables" id="tabeldata">
				
					<thead>
						<tr class="heading-module bgcolor2">
						
							<th><center>No</center></th>
							<th><center>Judul Produk Hukum</center></th>
							<th><center>Kategori</center></th>
							<th><center>Tahun</center></th>
							<th><center>Aksi</center></th>
						
						</tr>
					</thead>
					
				</table>
			</div>
		</div>
	</div>
</div>	
		
<script>
    $(document).ready(function() {
        var TableData = $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('fweb/peraturan/datatables') ?>",
                data: function(req) {
                    req.tahun    = $('#tahun').val();
                    req.kategori = $('#kategori').val();
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%',
                },
                {
                    data: 'nama',
                    name: 'nama',
					orderable: false,
                    searchable: false,
                    width: '50%',
                },
                {
                    data: 'kategori_dokumen',
                    name: 'kategori_dokumen',
					orderable: false,
                    searchable: false,
                    width: '10%',
                },
                {
                    data: 'tahun',
                    name: 'tahun',
					orderable: false,
                    searchable: false,
                    width: '10%',
                },
				
				
                {
                    data: function (data) {
                        return '<a style="color:#fff !important;border-radius:3px !important;padding:3px 10px !important;" href="<?= site_url('dokumen_web/unduh_berkas/') ?>' + data.id + '" class="bgcolor-1 flexcenter" style="color:#fff;" target="_blank" rel="noopener noreferrer">Unduh</a>';
                    },
                    name: 'aksi',
                    searchable: false,
                    orderable: false,
                    width: '10%',
                },
            ],
            order: [
                [3, 'asc']
            ]
        });

        $('select[name="tahun"]').on('change', function() {
            $(this).val();
            TableData.ajax.reload();
        });

        $('select[name="kategori"]').on('change', function() {
            $(this).val();
            TableData.ajax.reload();
        });
    });
</script>

