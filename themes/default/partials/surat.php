<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="artikel">

<?php if($_SESSION['sukses']==1){echo "Data telah terkirim, dan akan segera kami proses";unset($_SESSION['sukses']);} ?>
<form class='contact_form' id="validasi" action="<?= site_url()?>lapor_web/insert" method="POST" enctype="multipart/form-data">

<div class="single_bottom_rightbar wow fadeInDown animated"> 
	<h2>Kirim Permohonan Surat</h2> </div>
<table class="form">

<input class="form-group" type="hidden" name="owner" value="<?= $_SESSION['nama']?>"/>
<input class="form-group" type="hidden" readonly="readonly" name="email" value="<?= $_SESSION['nik']?>"/>
<tr>
    <th valign="top" width="30%">Permohonan Surat</th>
    <td>
        <select class="form-group" name="surat">
            <option> -- Pilih Layanan -- </option>
            <?php foreach ($menu_surat2 AS $data): ?>
            <option value="Surat <?= $data['nama']?>"><?= $data['nama']?></option>
            <?php endforeach;?>
        </select>
    </td>
</tr>
<tr>
<th valign="top">Keterangan Tambahan</th>
<td> <textarea name="komentar" rows="10" cols="60" placeholder="Ketik di sini untuk memberikan keterangan tambahan.">
</textarea>
</td>
</tr>
<tr>
<th>Nomor HP Aktif</th>
<td> <input class="form-group" type="text" name="hp" placeholder="ketik di sini" size="30"/></td>
</tr>

<tr>
<td colspan="2"><input type="submit" value="Kirim"> </td>
</tr>

</table>
</form>
</div>
