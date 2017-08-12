<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
