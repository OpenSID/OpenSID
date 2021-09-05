<div class="content-wrapper">
	<section class="content-header">
		<h1>Setting Tanggal Merah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Setting Tanggal Merah</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="year">Tahun dan Bulan</label>
						<div class="col-sm-4">
							<select id="year" name="tahun" class="form-control input-sm inp-tahun"  placeholder="Tahun"></select>
						</div>
						<div class="col-sm-4">
							<select id="month" name="bulan" class="form-control input-sm inp-bulan" ></select>
						</div>
					</div>
				</form>
			</div>
			
		</div>
	</section>
</div>
<script>
$(function() {
	$( ".inp-tahun" ).each(function( index ) {
		for(y=2021;y<=<?=date("Y",strtotime("+1 year"));?>;y++)
		{
			txt="<option>"+y+"</option>";
			$(this).append(txt);
		} 
	});
	$( ".inp-bulan" ).each(function( index ) {
		const bln = ["Silakan memilih bulan","Januari", "Februari","Maret","April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober","November", "Desember"];
		for (month=1; month < bln.length; month++) {
			txt='<option value="'+month+'">' + bln[month] + '</option>';
		  $(this).append(txt);
		}

	});
});

function myFunction(item, index, arr) {
  arr[index] = item * 10;
}
</script>