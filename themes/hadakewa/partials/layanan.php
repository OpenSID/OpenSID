<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="artikel">

			<div class="teks">
			<?php $this->load->view('surat/signature.php');?>
			</div>
			<div><ol>
			<?php /*foreach($menu_surat2 AS $data){
			if($data['id']==1 OR $data['id']==2 OR $data['id']==7 OR $data['id']==8){?>
			<li><a  href="<?php echo site_url()?>surat/form/<?php echo $data['url_surat']?>"><span><?php echo $data['nama']?></span>
			</a></li>
			<?php  } }*/?>
			</ol></div>
</div>
