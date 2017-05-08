<script type="text/javascript">
	sts_bs("JWS",[20080623,"<?php echo base_url().LOKASI_FOTO_ARTIKEL?>","","blank.gif",7,1,1,1,"630px","right",0,2,150,90,0,90,1,0,90,2000,2,15,0,"",-2,0],["ItBS","ItBW","ItBC","GBgC","GBgI","GBgR"]);
	sts_tbd([0],["BS","BW","BC","CnSz","LtCn","RtCn","RbCn","LbCn","TBgC","TBgI","TBgR","RiBgC","RiBgI","RiBgR","BtBgC","BtBgI","BtBgR","LBgC","LBgI","LBgR"]);
	<?php $i=0;foreach($slide AS $data){
	if($data['gambar']!=''){if(is_file(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar'])){
		list($img_w,$img_h) = getimagesize(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar']); $w=(140/$img_w)*$img_h;$w=90;
		if($i==0){
		?>
	sts_ai("i<?php echo $i?>",[1,"","","_self","kecil_<?php echo $data['gambar']?>",146,<?php echo $w?>,"center"],["ItBgC","OtF","OtFC","OtFD","OvF","OvFC","OvFD"],"i0","i0");
	<?php }else{?>
	sts_ai("i<?php echo $i?>",[,,,,"kecil_<?php echo $data['gambar']?>",146,<?php echo $w?>,"center"],[],"i<?php echo $i?>","i<?php echo $i?>");
	<?php } $i++;} } } ?>
	sts_es();
</script>