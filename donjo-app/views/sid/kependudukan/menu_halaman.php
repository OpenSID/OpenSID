    <div class="left">
      <div class="uibutton-group">
        <a href="<?php echo site_url('penduduk/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Penduduk Pendatang</a>

				<?php  if($grup==1){?><button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("penduduk/delete_all/$p/$o")?>')" class="uibutton chrome"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data</button><?php  }?>
        <a href="<?php echo site_url("penduduk/cetak/$o")?>" class="uibutton" title="Cetak Data" target="_blank"><span class="icon-print icon-large">&nbsp;</span>Cetak</a>
				<a href="<?php echo site_url("penduduk/excel/$o")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="icon-file-text icon-large">&nbsp;</span>Excel</a>
      </div>
    </div>
    <div class="right">
      <div class="uibutton-group">
        <a href="<?php echo site_url("penduduk_log/clear")?>" class="uibutton tipsy south" title="Log Data" ><span class="icon-book icon-large">&nbsp;</span>Log Penduduk</a>
      </div>
    </div>
    <div class="left">
      <select name="filter" onchange="formAction('mainform','<?php echo site_url('penduduk/filter')?>')">
        <option value="">Semua</option>
        <option value="1" <?php if($filter==1 ) :?>selected<?php endif?>>Tetap</option>
        <option value="2" <?php if($filter==2 ) :?>selected<?php endif?>>Tidak Aktif</option>
        <option value="3" <?php if($filter==3) :?>selected<?php endif?>>Pendatang</option>
      </select>

      <select name="sex" onchange="formAction('mainform','<?php echo site_url('penduduk/sex')?>')">
        <option value="">Jenis Kelamin</option>
        <option value="1" <?php if($sex==1 ) :?>selected<?php endif?>>Laki-Laki</option>
        <option value="2" <?php if($sex==2 ) :?>selected<?php endif?>>Perempuan</option>
      </select>

      <select name="dusun" onchange="formAction('mainform','<?php echo site_url('penduduk/dusun')?>')">
        <option value=""><?php echo ucwords(config_item('sebutan_dusun'))?></option>
				<?php foreach($list_dusun AS $data){?>
          <option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo ununderscore(unpenetration($data['dusun']))?></option>
				<?php }?>
      </select>

			<?php if($dusun){?>
        <select name="rw" onchange="formAction('mainform','<?php echo site_url('penduduk/rw')?>')">
          <option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
            <option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
        </select>
			<?php }?>

			<?php if($rw){?>
        <select name="rt" onchange="formAction('mainform','<?php echo site_url('penduduk/rt')?>')">
          <option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
            <option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
        </select>
			<?php }?>

			<button href="<?php echo site_url("penduduk/ajax_adv_search")?>"  target="ajax-modalx" rel="window" header="Pencarian Spesifik"  class="uibutton tipsy south"  title="Pencarian Spesifik"><span class="icon-search icon-large">&nbsp;</span>Pencarian Spesifik</button><a href="<?php echo site_url("penduduk/clear")?>"  class="uibutton tipsy south"  title="Bersihkan Pencarian"><span class="icon-refresh icon-large">&nbsp;</span>Bersihkan</a>
	  </div>
    <div class="right">
      <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('penduduk/search')?>');$('#'+'mainform').submit();}" />
      <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('penduduk/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span> Cari </button>
    </div>
