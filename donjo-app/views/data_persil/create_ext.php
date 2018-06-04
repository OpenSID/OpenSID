<?php
/*
 * Copyright 2015 Isnu Suntoro <isnusun@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */

?>
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('data_persil/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<legend>Pengelolaan Data Persil <?php echo $desa['nama_desa'];?></legend>
			<div id="contentpane">
				<div id="maincontent" class="ui-layout-center" style="padding:0 3em 0 0;">

			<?php
				if($_SESSION["success"]==1){
					echo "
					<div>
					".$_SESSION["pesan"]."
					</div>";
					$_SESSION["success"]==0;
				}
			?>

				<form name='mainform' action="<?= site_url('data_persil/simpan_persil') ?>" method="POST">
					<input name="nik" type="hidden" value="<?= NIK_LUAR_DESA ?>">
					<input name="jenis_pemilik" type="hidden" value="2">
					<div class="form-group">
						<label>NAMA PEMILIK</label>
						<input type="text" class="form-control" name="pemilik_luar" id="kelas" placeholder="Tuliskan Nama Pemilik" value="<?= $persil_detail['pemilik_luar'] ?>"/>
					</div>
					<div class="form-group">
						<label>NOMOR PERSIL</label>
						<input type="text" class="form-control" name="nama" id="nama" placeholder="Tuliskan Nomor Persil" value="<?= $persil_detail["nopersil"] ?>"/>
					</div>
					<div class="form-group">
						<label>ALAMAT PEMILIK</label>
						<input type="text" class="form-control" name="alamat_luar" id="alamat_luar" placeholder="Tuliskan Alamat Pemilik" value="<?= $persil_detail["alamat_luar"] ?>"/>
					</div>
					<div class="form-group">
						<label>KETERANGAN SURAT</label>
						<select class="form-control" id="cid" name="cid">";
							<?php foreach($persil_jenis as $key=>$item): ?>
								<?php $strC = ($key==$persil_detail["persil_jenis_id"])? 'selected="selected"' : ""; ?>
								<option value="<?= $key?>" <?= $strC ?>><?= $item[0] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>LUAS TANAH (M<sup>2</sup>)</label>
						<input type="text" class="form-control" name="luas" id="luas" placeholder="Tuliskan Luas Tanah dalam meter persegi" value="<?= $persil_detail["luas"] ?>"/>
					</div>
					<div class="form-group">
						<label>KELAS TANAH</label>
						<input type="text" class="form-control" name="kelas" id="kelas" placeholder="Tuliskan Kelas Tanah" value="<?= $persil_detail["kelas"] ?>"/>
					</div>
					<div class="form-group">
						<label>PERUNTUKAN</label>
						<select class="form-control" id="sid" name="sid">";
							<?php foreach($persil_peruntukan as $key=>$item): ?>
								<?php $strC = ($key==$persil_detail["persil_peruntukan_id"])? "selected='selected'":""; ?>
								<option value="<?= $key ?>" <?= $strC ?>><?= $item[0] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>LOKASI TANAH</label>
						<select class="form-control" id="pid" name="pid">"
							<?php foreach($persil_lokasi as $key=>$item): ?>
								<?php $strC = ($item['id']==$persil_detail["id_clusterdesa"])? "selected='selected'" : ""; ?>
								<option value="<?= $item["id"] ?>" <?= $strC ?>> <?= strtoupper($item["dusun"])." - RW ".$item["rw"]." / RT ".$item["rt"] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>NOMOR SPPT PBB</label>
						<input type="text" class="form-control" name="sppt" id="sppt" placeholder="Tuliskan Nomor SPPT PBB" value="<?= $persil_detail["no_sppt_pbb"] ?>"/>
					</div>

					<div class="form-group" style="margin-bottom:3em;">
						<div class="uibutton-group">
							<input type="hidden" name="id" value="<?= $persil_detail["id"] ?>"/>
							<input type="submit" class="uibutton confirm" name="tombol" id="tombol" value="Simpan"/>
							<input type="reset" class="uibutton" name="tombolreset" id="tombolreset" value="Batal"/>
						</div>
					</div>
				</form>

				<div style="height:10em;"></div>
				</div>
			</div>
		</td>
		<td style="width:250px;" class="contentpane">
		<?php
		$this->load->view('data_persil/panduan.php');
		?>
		</td>
	</tr>
</table>
</div>
