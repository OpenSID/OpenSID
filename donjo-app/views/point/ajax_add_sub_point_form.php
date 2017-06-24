<form id="validasi" action="<?php  echo $form_action?>" method="POST" enctype="multipart/form-data">
<table style="width:100%">
<tr>
<th width="100">Nama Point</th>
<td><input class="inputbox" type="text" name="nama" value="<?php  echo $point['nama']?>" size="40"/></td>
</tr>
			<tr>
				<th>Simbol</th>
				<td>
					<div id="simbol" style="float:left;padding-top:6px;"></div>
					<div style="float:left;margin-left:10px;">
						<?php if($point['simbol']!=""){?>
						<img src="<?php  echo base_url(); ?>assets/images/gis/point/<?php  echo $point['simbol']?>">
						<?php }else{?>
						<img src="<?php  echo base_url(); ?>assets/images/gis/point/default.png">
						<?php }?>
					</div>
				</td>
			</tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>

<script>
	$(function(){
		var nik = {};
		nik.results = [
			<?php  foreach($simbol as $data){?>
				{id:'<?php  echo $data['simbol']?>',name:"<?php  echo $data['simbol']?>",info:'<img src="<?php  echo base_url(); ?>assets/images/gis/point/<?php  echo $data['simbol']?>">'},
			<?php  }?>
		];
		
		nik.total = nik.results.length;
		$('#simbol').flexbox(nik, {
			resultTemplate: '<div style=height:33px;margin-top:-4px;>{info}</div><div style="display:none;">{name}</div>',
			watermark: <?php  if($point){?>'<?php  echo $point['simbol']?>'<?php  }else{?>'Ketik nama simbol di sini..'<?php  }?>,
			width: 100,
			noResultsText :'...'
			//onSelect: function() {
			//	$('#'+'main').submit();
		//}  
		});
	});
</script>