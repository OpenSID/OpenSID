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
 * *
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

<?php
/*
 * List Penduduk
 *
 * */
echo "
<script>
	$(document).ready(function() {
		var nik = {};
		nik.results = [";
		if($penduduk){
			foreach ($penduduk as $item){
				if(strlen($item["id"])>0){
					echo "{id: ".$item["id"].", name:\"".$item["nama"]."\",info:\"".$item["info"]."\"},\n";
				}
			}
		}
		echo "
		];

		$('#nik').flexbox(nik, {
			resultTemplate: '<div><label>Nama : </label>{name}</div><div><label>Alamat : </label>{info}</div>',
			watermark: \"Cari nama di sini..\",
			width: 400,
			noResultsText :'Tidak ada no nik yang sesuai..',
			onSelect: function() {
				$('#'+'main').submit();
		}
		});
	});
</script>
";
/*
 * Form Add/Edit
 *
 * */

if($persil_detail>0){
	if(isset($persil_detail["id"])){
		$nik = $persil_detail["nik"];
		$nama = $persil_detail["nopersil"];
		$cid = $persil_detail["persil_jenis_id"];
		$sid = $persil_detail["persil_peruntukan_id"];
		$pid = $persil_detail["id_clusterdesa"];
		$kelas = $persil_detail["kelas"];
		$luas = $persil_detail["luas"];
		$sppt = $persil_detail["no_sppt_pbb"];
		$id = $persil_detail["id"];
	}else{
		$nama = "";
		$cid = 0;
		$sid = 0;
		$pid = 0;
		$kelas = "";
		$luas = 0;
		$sppt = "";
		$cid = 0;
		$id = 0;
	}
}else{
	$nama = "";
	$cid = 0;
	$sid = 0;
	$pid = 0;
	$kelas = "";
	$luas = 0;
	$sppt = "";
	$cid = 0;
	$id = 0;
}
echo "
	<fieldset>
	<legend>Formulir Penambahan/Pembaruan Data Peruntukan Persil</legend>
		<fieldset>
			<legend>Pemilik Persil</legend>
			<div>
				<form action=\"\" id=\"main\" name=\"main\" method=\"POST\">
				<label>Cari Nama Penduduk dari Database ".ucwords($this->setting->sebutan_desa)."</label>
				<div id=\"nik\" name=\"nik\" class=\"form-control\"></div>
				</form>
			</div>
		</fieldset>
";
echo form_open('data_persil/simpan_persil')."\n";
if($pemilik){
	echo "
	<div class=\"form-group\">
		<fieldset>
			<legend>Data Pemilik</legend>
			<input type=\"hidden\" name=\"nik\" value=\"".$pemilik["nik"]."\"/>
			<dl>
				<dt>Nama Penduduk</dt>
					<dd>: ".$pemilik["nama"]."</dd>
				<dt>NIK</dt>
					<dd>: ".$pemilik["nik"]."</dd>
				<dt>Alamat</dt>
					<dd>: RT ".$pemilik["rt"]." / RT ".$pemilik["rw"]." - ".strtoupper($pemilik["dusun"])."</dd>
			</dl>
		</fieldset>
	</div>
	";
}
echo "
	<div class=\"form-group\">
		<label>NOMOR PERSIL</label>
		<input type=\"text\" class=\"form-control\" name=\"nama\" id=\"nama\" placeholder=\"Tuliskan Nomor Persil\" value=\"".$nama."\"/>
	</div>
	<div class=\"form-group\">
		<label>KETERANGAN SURAT</label>
		<select class=\"form-control\" id=\"cid\" name=\"cid\">";
			foreach($persil_jenis as $key=>$item){
				$strC = ($key==$cid)? "selected=\"selected\"":"";
				echo "<option value=\"".$key."\" ".$strC.">".$item[0]."</option>";
			}
			echo "
		</select>
	</div>
	<div class=\"form-group\">
		<label>LUAS TANAH (M<sup>2</sup>)</label>
		<input type=\"text\" class=\"form-control\" name=\"luas\" id=\"luas\" placeholder=\"Tuliskan Luas Tanah dalam meter persegi\" value=\"".$luas."\"/>
	</div>
	<div class=\"form-group\">
		<label>KELAS TANAH</label>
		<input type=\"text\" class=\"form-control\" name=\"kelas\" id=\"kelas\" placeholder=\"Tuliskan Kelas Tanah\" value=\"".$kelas."\"/>
	</div>
	<div class=\"form-group\">
		<label>PERUNTUKAN</label>
		<select class=\"form-control\" id=\"sid\" name=\"sid\">";
			foreach($persil_peruntukan as $key=>$item){
				$strC = ($key==$sid)? "selected=\"selected\"":"";
				echo "<option value=\"".$key."\" ".$strC.">".$item[0]."</option>";
			}
			echo "
		</select>
	</div>
	<div class=\"form-group\">
		<label>LOKASI TANAH</label>
		<select class=\"form-control\" id=\"pid\" name=\"pid\">";
			foreach($persil_lokasi as $key=>$item){
				$strC = ($key==$pid)? "selected=\"selected\"":"";
				echo "<option value=\"".$item["id"]."\" ".$strC.">".strtoupper($item["dusun"])." - RW ".$item["rw"]." / RT ".$item["rt"]."</option>";
			}
			echo "
		</select>
	</div>
	<div class=\"form-group\">
		<label>NOMOR SPPT PBB</label>
		<input type=\"text\" class=\"form-control\" name=\"sppt\" id=\"sppt\" placeholder=\"Tuliskan Nomor SPPT PBB\" value=\"".$sppt."\"/>
	</div>

	<div class=\"form-group\" style=\"margin-bottom:3em;\">
		<div class=\"uibutton-group\">
		<input type=\"hidden\" name=\"id\" value=\"".$id."\"/>
		<input type=\"submit\" class=\"uibutton confirm\" name=\"tombol\" id=\"tombol\" value=\"Simpan\"/>
		<input type=\"reset\" class=\"uibutton\" name=\"tombolreset\" id=\"tombolreset\" value=\"Batal\"/>
		</div>
	</div>

";
echo "</form>
</fieldset>";
?>
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
