<?php
/*
 * Berkas default dari halaman web utk publik
 * 
 * Copyright 2013 
 * Rizka Himawan <himawan.rizka@gmail.com>
 * Muhammad Khollilurrohman <adsakle1@gmail.com>
 * Asep Nur Ajiyati <asepnurajiyati@gmail.com>
 *
 * SID adalah software tak berbayar (Opensource) yang boleh digunakan oleh siapa saja selama bukan untuk kepentingan profit atau komersial.
 * Lisensi ini mengizinkan setiap orang untuk menggubah, memperbaiki, dan membuat ciptaan turunan bukan untuk kepentingan komersial
 * selama mereka mencantumkan asal pembuat kepada Anda dan melisensikan ciptaan turunan dengan syarat yang serupa dengan ciptaan asli.
 * Untuk mendapatkan SID RESMI, Anda diharuskan mengirimkan surat permohonan ataupun izin SID terlebih dahulu, 
 * aplikasi ini akan tetap bersifat opensource dan anda tidak dikenai biaya.
 * Bagaimana mendapatkan izin SID, ikuti link dibawah ini:
 * http://lumbungkomunitas.net/bergabung/pendaftaran/daftar-online/
 * Creative Commons Attribution-NonCommercial 3.0 Unported License
 * SID Opensource TIDAK BOLEH digunakan dengan tujuan profit atau segala usaha  yang bertujuan untuk mencari keuntungan. 
 * Pelanggaran HaKI (Hak Kekayaan Intelektual) merupakan tindakan  yang menghancurkan dan menghambat karya bangsa.
 */
?>
<ul>
  <?php foreach ($this->Menu_model->get_menu('bottom') as $key => $item):?>
  	<?php if ($this->session->userdata('group_id') && $item->group_id == $this->session->userdata('group_id') || $item->group_id == '0'):?>
        <li<?php if($key==0) echo " class='first'";?>>
  		  <?php 
           	  if ($item->url != '') {
           		echo "<a href='" .$item->url. "' target='_blank'>" .$item->title. "</a>";
              }
              else {
               	echo anchor($item->controller .'/'. $item->view,$item->title);
              }
	       ?>    <?php endif;?>
  <?php endforeach;?>
        </li>
        <li class="separator">|</li>

 
  	  <li>
           <?php echo anchor('penduduk','Admin');?>
      </li>
        <li class="separator">|</li>
  
  	  <li>
           <?php echo anchor('auth/logout','Logout');?>
      </li>



  
</ul>
