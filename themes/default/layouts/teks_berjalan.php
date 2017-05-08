<div id="scroller">
	<script type="text/javascript">
	sts_bs("jwscroller2e8e",[20080623,"images/","","blank.gif",3,1,1,1,"","left",3,1,962,0,0,0,0,0,0,0,1,27,0,"",-1,10],["none",1]);
	sts_tbd([1],["double",2,"#0099CC #FFFFFF",5,"round_tl.gif","round_tr.gif","round_br.gif","round_bl.gif","transparent","round_t.gif","repeat","transparent","round_r.gif","repeat","transparent","round_b.gif","repeat","transparent","round_l.gif","repeat"]);
	<?php $tb=0;foreach($teks_berjalan AS $data){?>
	sts_ai("i<?php echo $tb;?>",[0,"<?php echo fixTag($data['isi']);?>","","_self","",0,0,"center"],["transparent","bold 8pt Arial","#ffffff","none","bold 8pt Arial","#ffff00","none"]);
	<?php $tb++;} ?>
	sts_es();
	</script>
</div>