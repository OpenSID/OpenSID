
	<div class="login-box">

		<div class="login-box-body">
			<div class="login-title">
				<!--a href="<?=site_url(); ?>"><img src="<?= gambar_desa($header['logo']); ?>" alt="<?=$header['nama_desa']?>" class="logo-login"/></a-->
				<h1>
					LAYANAN KEHADIRAN	<br/><?=ucwords($this->setting->sebutan_desa) . ' ' . $header['nama_desa']?>
				</h1>
				<!--h3>
					<br/><?=$header['alamat_kantor']?><br/>Kodepos <?=$header['kode_pos']?>
					<br/><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$header['nama_kecamatan']?><br/><?=ucwords($this->setting->sebutan_kabupaten)?> <?=$header['nama_kabupaten']?>
				</h3-->
			</div>
			<hr/>
			<?php if ($error_msg = $this->session->error_msg): 
					$this->session->unset_userdata('error_msg');
				?>
				<div class="callout callout-danger" id="notif_msg">
					<?=$error_msg;?> 
				</div>	
			<?php endif; ?>
			<?php if ($success_msg = $this->session->success_msg): 
					$this->session->unset_userdata('success_msg');
				?>
				<div class="callout callout-success" id="notif_msg">
					<?=$success_msg;?> 
				</div>	
			<?php endif; ?>
				
			<?php if ($this->session->mandiri_wait == 1): ?>
				<div class="notif-mandiri">
					<p id="countdown"></p>
				</div>
			<?php else: ?> 
				
				<?php
					if ($this->session->mandiri_try < 4): ?>
					<div class="callout callout-danger" id="notif">
						<p>NIK atau PIN salah.<br/>Kesempatan mencoba <?= ($this->session->mandiri_try - 1); ?> kali lagi.</p>
					</div>
				<?php 
					endif; 
				if(isset($locked))
				{
					$this->load->view('kehadiran/form/login_locked');
					if(ENVIRONMENT == 'development')
					{
						$this->load->view('kehadiran/form/login_'.$login_type);
					}
						 
				}
				else
				{
					$this->load->view('kehadiran/form/login_'.$login_type);
				}
				
				?>
				
				
			<?php endif; ?>
			<!--div class="form-login-footer">
				<hr/><a href="https://github.com/OpenSID/OpenSID" target="_blank" rel="noreferrer">OpenSID <?= AmbilVersi() ?></a>
				<br/>
				IP Address :
				<?php if ( ! $cek_anjungan): ?>
					<?= $this->input->ip_address(); ?>
				<?php else: ?>
					<?= $cek_anjungan['ip_address'] . "<br/>Anjungan Mandiri" ?>
					<?= jecho($cek_anjungan['keyboard'] == 1, TRUE, ' | Virtual Keyboard : Aktif'); ?>
				<?php endif; ?>
			</div-->
		</div>
<?php if(false) {?>
<!-- $login_type==3||$login_type==4 -->
		<div class='numpad'>
		<?php $this->load->view('kehadiran/form/login_keypad'); ?>
		</div>

<?php } ?>
		
	</div>
