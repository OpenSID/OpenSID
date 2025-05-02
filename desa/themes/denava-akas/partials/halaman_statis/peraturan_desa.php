<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="container-page mb-20">
		<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
			<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/></div>
			<h2>PRODUK HUKUM</h2>
		</div>
		<div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
			<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
				<div class="col-md-3">
					<div class="form-group">
						<label for="kategori">Kategori</label>
						<select class="form-control input-sm" id="kategori" name="kategori">
							<option selected value="">Semua Kategori</option>
							<?php foreach ($pilihan_kategori as $id => $kategori) : ?>
								<option value="<?= $id ?>"><?= $kategori ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="tahun">Tahun</label>
						<select class="form-control input-sm" id="tahun" name="tahun">
							<option value="">Semua</option>
							<?php foreach ($pilihan_tahun as $tahun) : ?>
								<option value="<?= $tahun ?>"><?= $tahun ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered" id="tabeldata">
							<thead>
								<tr>
									<th>No</th>
									<th>Judul Produk Hukum</th>
									<th>Kategori</th>
									<th>Tahun</th>
									<th>Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function() {
        var url = "<?= site_url('fweb/peraturan/datatables')?>";
		TableData = $('#tabeldata').DataTable({
			'responsive': true,
			'processing': true,
			'serverSide': true,
			"pageLength": 10,
          	'language': {
				'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
			},
			"ajax": {
				"url": url,
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
                    width: '50%',
                },
                {
                    data: 'kategori_dokumen',
                    name: 'kategori_dokumen',
                    width: '10%',
                },
                {
                    data: 'tahun',
                    name: 'tahun',
                    width: '10%',
                },
                {
                    data: function (data) {
						if (data.url != null) {
                            return `<button onclick="window.location.href='${data.url}'" class="btn btn-primary btn-block" target="_blank" rel="noopener noreferrer">Lihat</button>`;
                        }
                        return '<a href="<?= site_url('dokumen_web/unduh_berkas/') ?>' + data.id + '" class="btn btn-primary btn-block" target="_blank" rel="noopener noreferrer">Unduh</a>';
                    },
                    name: 'aksi',
                    searchable: false,
                    orderable: false,
                    width: '10%',
                },
            ],
            order: [
                [3, 'desc']
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
