<script>
(function() {
	var opsi_width = (parseInt($('#opsi').width())/2)-10;
	$('#opsi div').css('width',opsi_width);
	$('#opsi label').css('width',opsi_width-36);
	$('#opsi input:checked').parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
	$('#opsi input').change(function(){
		if ($(this).is(':checked')){
			$(this).parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
		} else {
			$(this).parent().css({'background':'#fafafa','border':'1px solid #ddd'});
		}
	});	
	$('#opsi label').click(function(){
		$(this).prev().trigger('click');
	})
	 
})();
</script>
<style>
	#opsi div{
		margin:1px 0;
		background:#fafafa;
		border:1px solid #ddd;
	}
	#opsi input{
		vertical-align:middle;
		margin:0px 2px;
	}
	#opsi label{
		padding:4px 10px 0px 2px;
		font-size:11px;
		line-height:12px;
		font-weight:normal;
	}
</style>
<form method="post" action="<?php echo $form_action?>" >
	<input type="hidden" name="rt" value="">
	<table width="100%">
		<?php $last="";foreach($main AS $data){?>
		<?php 
			if($data['pertanyaan']!=$last){?></td></tr><tr><td><label><?php echo $data['pertanyaan']?></label></td></tr>
			<tr>
				<td id="opsi">
				<div style="display:inline-block;">
					<input type="checkbox" name="id_cb[]" value="<?php echo $data['id_jawaban']?>"<?php if($data['cek']){echo " checked";}?>>
					<label><?php echo $data['kode_jawaban'].". ".$data['jawaban']?></label>
				</div>
		<?php 
		}else{?>
			<div style="display:inline-block;">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id_jawaban']?>"<?php if($data['cek']){echo " checked";}?>>
				<label><?php echo $data['kode_jawaban'].". ".$data['jawaban']?></label>
			</div>
			<?php
			}
			$last=$data['pertanyaan'];
		}
		?>
	</table>
	<div class="buttonpane" style="text-align: right; width:600px;bottom:0px;">
		<div class="uibutton-group">
			<button class="uibutton" type="button" onclick="$(this).closest('.ui-dialog-content').dialog('close');">Batal</button>
			<button class="uibutton confirm" type="submit">Lanjut</button>
		</div>
	</div>
</form>