<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
    
	<div class="row" style="margin-bottom:3px;">
      <div class="col-lg-12 col-md-12"><br>
        <div class="header_top">
          <div class="header_top_left"style="margin-bottom:10px;">
            <ul class="top_nav">
              <li>
              <table>
                  <tr>
                      <td><img class="tlClogo" src="<?php echo LogoDesa($desa['logo']);?>" width="30" valign="top" alt="<?php echo $desa['nama_desa']?>"/></td>
                      <td>
                          <a href="<?php echo site_url(); ?>first">
                            <font size="4"><?php
                              echo $this->setting->website_title. ' ' . ucwords($this->setting->sebutan_desa). (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?>
                              </font><br /><font size="2">
                              <?php echo ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?> <?php echo ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?>
                            </font>
                          </a>
                      </td>
                  </tr>
              </table>
			  </li>
            </ul>
          </div>
          <div class="header_top_right">
        	<form method=get action="<?php echo site_url('first');?>" class="form-inline">
        		<table align="center"><tr><td><input type="text" name="cari" maxlength="50" class="form-control" value="<?php echo $_GET['cari']; ?>" placeholder="Cari Artikel"></td>
        		<td>&nbsp;</td><td><button type="submit" class="btn btn-primary">Cari</button></td></tr></table>
        	</form>	
          </div>
        </div>
        
      </div>
    </div>
    <?php if (count($slide_galeri)>0 OR count($slide_artikel)>0): ?>
	<?php $this->load->view($folder_themes."/layouts/slider.php") ?>
	<?php endif; ?>
