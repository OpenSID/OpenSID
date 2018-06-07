<div class="content-wrapper">
	<section class="content-header">
		<h1>Staf Pemerintahan <?php echo ucwords($this->setting->sebutan_desa)?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Staf Pemerintahan <?php echo ucwords($this->setting->sebutan_desa)?></li>
		</ol>
	</section> 
	<section class="content">
		<div class="row" >
			<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-body box-profile">
							<?php if($pamong['foto']):?>
								<img class="profile-user-img img-responsive img-circle" src="<?AmbilFoto($pamong['foto'])?>" alt="Photo">								
							<?php else:?>
								<img class="profile-user-img img-responsive img-circle" src="<?php echo base_url()?>assets/files/user_pict/kuser.png" alt="Photo">						 
							<?php endif?>			
							<br/>
							<p class="text-muted text-center"><code>(Kosongkan jika tidak ingin mengubah photo)</code></p>
							<br/>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path2" name="foto">
								<input type="file" class="hidden" id="file2" name="foto">
								<input type="hidden" name="old_foto" value="<?=$pamong['foto']?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>							
						</div>						
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">							
							<a href="<?=site_url()?>pengurus" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left "></i> Kembali Ke Daftar Staf</a>	
						</div>									
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="pamong_nama">Nama Pegawai <?php echo ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-6">
									<input type="hidden" name="pamong_status" value="1">
									<input id="pamong_nama" name="pamong_nama" class="form-control input-sm required" type="text" placeholder="Nama" value="<?php echo unpenetration($pamong['pamong_nama'])?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="pamong_nip">NIP</label>
								<div class="col-sm-6">
									<input id="pamong_nip" name="pamong_nip" class="form-control input-sm" type="text" placeholder="NIP" value="<?=$pamong['pamong_nip']?>" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="pamong_nik">NIK</label>
								<div class="col-sm-6">
									<input id="pamong_nik" name="pamong_nik" class="form-control input-sm" type="text" placeholder="NIK" value="<?=$pamong['pamong_nik']?>" ></input>
								</div>
							</div>		
							<div class="form-group">
								<label class="col-sm-4 control-label" for="jabatan">Jabtan</label>
								<div class="col-sm-6">
									<input id="jabatan" name="jabatan" class="form-control input-sm" type="text" placeholder="Jabatan" value="<?php echo unpenetration($pamong['jabatan'])?>" ></input>
								</div>
							</div>		
							<div class="form-group">
								<label class="col-sm-4 control-label" for="pamong_status">Status</label>
								<div class="radio col-sm-6">
<label><input id="group1" type="radio" value="1" name="pamong_status" <?php if($pamong['pamong_status'] == '1' OR $pamong['pamong_status'] == ''):?>checked<?php endif?>></input>Aktif</label>&nbsp;&nbsp;
									<label><input id="group2" type="radio" value="2" name="pamong_status" <?php if($pamong['pamong_status'] == '2' ):?>checked<?php endif?>></input>Tidak Aktif</label>
								</div>
							</div>
						</div>
						<div class='box-footer'>					
							<div class='col-xs-12'>	
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>						
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
