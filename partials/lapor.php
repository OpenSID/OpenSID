<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="single_page_area">
<h2 class='post_titile'>Layanan Masyarakat</h2>

<?php if($_SESSION['sukses']==1){echo "Data telah terkirim, dan akan segera kami proses";unset($_SESSION['sukses']);} ?>
<form class='contact_form' id="validasi" action="<?= site_url()?>lapor_web/insert" method="POST" enctype="multipart/form-data">

<div class="single_bottom_rightbar wow fadeInDown animated"> 
	<h2>Kirim Laporan</h2> </div>
<table class="form">

<tr>
<th width="20%">Pengirim </th>
<td> <input class="form-group" type="text" readonly="readonly" name="owner" value="<?php echo $_SESSION['nama']?>" size="30"/></td>
</tr>

<tr>
<th>NIK</th>
<td> <input class="form-group" type="text" readonly="readonly" name="email" value="<?php echo $_SESSION['nik']?>" size="30"/></td>
</tr>

<tr>
<th valign="top">Isi Laporan </th>
<td> <textarea name="komentar" rows="10" cols="60" placeholder="Ketik di sini. (misalnya ingin melaporkan perubahan data kependudukan anda).">
</textarea>
</td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="Kirim"> </td>
</tr>

</table>
</form>
</div>
