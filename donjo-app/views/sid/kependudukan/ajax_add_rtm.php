<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<script>
		$(function () {
			let penduduk = <?=json_encode($penduduk)?>;
			$('#ajax_add_rtm_nik_kepala').select2();
			$('#ajax_add_rtm_nik_kepala').on('change', function(){
				let value = $('#ajax_add_rtm_nik_kepala option:selected').val();
				let search_kepala = penduduk.find(function(item){
					return item['id'] == value;
				});
				let filter = penduduk.filter(function(item){
					return item['id_kk'] == search_kepala['id_kk'];
				});
				// kosongkan tabel
				$('#ajax_add_rtm_anggota').empty();
				for(let i in filter){
					// hanya tampilkan yg bukan terpilih dalam keluarga
					if(filter[i]['id'] != value){
						$('#ajax_add_rtm_anggota').append(`<tr><td><input type='checkbox' checked="checked" name='anggota_kk[]' value="` + filter[i]['id'] + `"/></td><td>` + filter[i]['nik'] + ` - ` + filter[i]['nama'] + `</td></tr>`)
					}
				}
			});
		})
	</script>
	<form action="<?= $form_action?>" method="post" id="validasi">
		<div class='modal-body'>
			<div class="form-group">
				<label for="nik">Kepala Rumah Tangga</label>
				<select class="form-control input-sm select2 required"  id="ajax_add_rtm_nik_kepala" name="nik_kepala" style="width:100%;">
					<option option value="">-- Silakan Cari NIK / Nama Penduduk--</option>
					<?php foreach ($penduduk as $data): ?>
						<option value="<?= $data['id']?>">NIK :<?= $data['nik'] . ' - ' . $data['nama']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
				Silakan cari nama / NIK dari data penduduk yang sudah terinput.
				Penduduk yang dipilih otomatis berstatus sebagai Kepala Rumah Tangga baru tersebut.
			</p>

			<hr>
			<p><strong>Centang anggota keluarga dibawah ini untuk disertakan sebagai anggota rumah tangga</strong></p>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped table-sm">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Nama</th>
						</tr>
					</thead>
					<tbody id="ajax_add_rtm_anggota">
					</tbody>
				</table>
			</div>

			<div class="form-group">
				<label for="bdt">BDT</label>
				<input class="form-control input-sm angka" type="text" placeholder="BDT" name="bdt" value="<?= $kk['bdt']; ?>" minlength="16" maxlength="16"/>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</form>
<?php endif; ?>

