<script>
	var stat=0;
<?php
if ($hadir['waktu_masuk'] == NULL)
{?>
	function cekHadir()
	{
		inp=$("#stat_hadir").is(':checked');
		if (inp == true)
		{
			$("#form_hadir").submit();
		}
		else
		{
			alert("Mohon menekan lingkaran di atas <b>ke kanan </b>untuk kehadiran");
		}
		
	}	
	
<?php
}
elseif ($hadir['waktu_masuk'] != NULL && $hadir['waktu_keluar'] == NULL)
{
?>
	function cekHadir()
	{
		inp = $("#stat_hadir").is(':checked');
		if (inp != true)
		{
			$("#form_hadir").submit();
		}
		else
		{
			alert("Mohon menekan lingkaran di atas <b>ke kiri</b> untuk keluar");
		}
		
	}
	
<?php
} 
else
{
?>
	alert("Maaf, Anda sudah Mengisi Kehadiran Keluar. Pada <?=date('H:i:s',strtotime($hadir['waktu_keluar']));?>");
	window.location.href = '<?=base_url("kehadiran/masuk");?>';
<?php
}
?>
var loginPage = "<?=site_url('kehadiran/masuk').'?form='. $this->session->userdata('login_type'); ?>";
//setInterval(function(){  window.location.href = loginPage; }, 20000);
</script>