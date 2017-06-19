<script>
$(function(){
    var nik = {};
    nik.results = [
		<?php foreach($list_penduduk as $data){?>
	   {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
		<?php }?>
		    ];
nik.total = nik.results.length;

$('#id_penduduk').flexbox(nik, {
	resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
	watermark: 'Ketik no nik di sini..',
    width: 260,
    noResultsText :'Tidak ada no nik yang sesuai..',
	    onSelect: function() {
		$('#'+'main').submit();
    }  
});
$("#nik_detail").show();
});
</script>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Input Anggota</h3>
</div>
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
			<tr>
                <th width=100>NIK / Nama Penduduk</th>
                <td>
                    <div id="id_penduduk" name="id_penduduk"></div>
                </td>
            </tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>kelompok" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
