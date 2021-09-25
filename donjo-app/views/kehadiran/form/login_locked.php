<p class="text-warning" align="center" style='margin:20px 0 40px'>
	Maaf, saat ini login tidak dapat dilakukan karena hari ini
	<?=$locked['status']==1?'Tanggal Merah':$locked['detail'];?>
</p>
<div> 
<p class="text-info"> 
<?php 
if (ENVIRONMENT == 'development')
{
	echo "khusus development akan menampilkan login untuk test";
}	
?>
</p>
<!-- keyboard -->
</div>