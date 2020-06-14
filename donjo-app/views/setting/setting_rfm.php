<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan File Manager Key</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan File Manager Key</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form action="<?=site_url("setting/update_key_admin/$main[id]")?>" method="POST" id="validasi" enctype="multipart/form-data">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="pass_baru">File Manager Key Lama</label>
								<div class="input-group">
									<input class="form-control input-sm" type="text" style="width: 220px;" readonly value="<?=$main['value']?>" name="pass_lama" placeholder="File Manager Key Lama" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="pass_baru">File Manager Key Baru</label>
								<div class="input-group">
									<input class="form-control input-sm required pwdLength" type="password" id="pass_baru" name="pass_baru" placeholder="File Manager Key Baru"></input>
									<span class="input-group-btn">
										<button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 col-md-3" for="pass_baru1">File Manager Key Baru (Ulangi)</label>
								<div class="input-group">
									<input class="form-control input-sm required pwdLength" type="password" id="pass_baru1" name="pass_baru1" placeholder="FM Key Baru (Ulangi)"></input>
									<span class="input-group-btn">
										<button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' id="btnSubmit" class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script>
$('document').ready(function()
{

	$(".reveal").on('click',function() {
		var $pwd = $(".input-sm");
		if ($pwd.attr('type') === 'password') {
			$pwd.attr('type', 'text');
		} else {
			$pwd.attr('type', 'password');
		}
	});

	setTimeout(function() {
		$('#pass_baru1').rules('add', {
			equalTo: '#pass_baru'
		})
	}, 500);

});
</script>
<style>
span.input-group-btn {
  position: absolute;
  display: inline-block;
  cursor: pointer;
  z-index: 2;
}
</style>
