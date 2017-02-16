<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama Kategori</th>
<td><input class="inputbox" type="text" name="nama" value="<?php echo $point['nama']?>" size="40"/></td>
</tr>
			<tr>
				<th>Simbol</th>
				<td>
					<div id="simbol" style="float:left;padding-top:6px;"></div>
					<div style="float:left;margin-left:10px;">
						<?php if($point['simbol']!=""){?>
						<img src="<?php echo base_url(); ?>assets/images/gis/point/<?php echo $point['simbol']?>">
						<?php }else{?>
						<img src="<?php echo base_url(); ?>assets/images/gis/point/default.png">
						<?php }?>
					</div>
				</td>
			</tr>
<?php 
?>
</table>
</div>
 
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>point" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">

<button class="uibutton confirm" type="submit" >Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
<script>
	$(function(){
		var nik = {};
		nik.results = [
			<?php foreach($simbol as $data){?>
				{id:'<?php echo $data['simbol']?>',name:"<?php echo $data['simbol']?>",info:'<img src="<?php echo base_url(); ?>assets/images/gis/point/<?php echo $data['simbol']?>">'},
			<?php }?>
		];
		
		nik.total = nik.results.length;
		$('#simbol').flexbox(nik, {
			resultTemplate: '<div style=height:33px;margin-top:-4px;>{info}</div><div style="display:none;">{name}</div>',
			watermark: <?php if($point){?>'<?php echo $point['simbol']?>'<?php }else{?>'Ketik nama simbol di sini..'<?php }?>,
			width: 100,
			noResultsText :'...'
			
			//	$('#'+'main').submit();
		
		});
	});
</script>