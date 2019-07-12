<div class="form-group">
	<label for="nik"  class="col-sm-3 control-label">NIK / Nama</label>
	<div class="col-sm-6 col-lg-4">
    	<select class="form-control required input-sm select2-nik" id="nik" name="nik" style ="width:100%;" onchange="formAction('main')">
			<option value="">--  Cari NIK / Tag ID Card / Nama Penduduk --</option>
			<?php 
				if ($this->uri->segment(3) == "surat_ket_lahir_mati") {
					$datas = $perempuan;
				}else{
					$datas = $penduduk;
				}
			?>
				<?php foreach ($datas as $data): ?>
					<option value="<?= $data['id']?>" <?php selected($individu['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
				<?php endforeach;?>
		</select>
	</div>
</div>