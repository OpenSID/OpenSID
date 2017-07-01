<script type="text/javascript">
  $(document).ready(function () {
    $('.lmenu ul').hide();

    $('legend').click(function() {
        $(this).parent().find('ul').slideToggle();
    });

    var kategori = document.getElementById('kategori').value;
    $('#' + kategori).show();
  });
</script>

<input id="kategori" name="kategori" type="hidden" value="<?php echo $kategori ?>" />

<div class="lmenu">
  <legend>Statistik Penduduk <span class="fa fa-chevron-down"></span></legend>
  <ul id="penduduk">
    <a href="<?php echo site_url()?>statistik/index/13"><li <?php if($lap==13){?>class="selected"<?php }?>>
      Umur</li></a>
    <a href="<?php echo site_url()?>statistik/index/0"><li <?php if($lap==0){?>class="selected"<?php }?>>
      Pendidikan dalam KK</li></a>
    <a href="<?php echo site_url()?>statistik/index/14"><li <?php if($lap==14){?>class="selected"<?php }?>>
      Pendidikan sedang Ditempuh</a></li>
    <a href="<?php echo site_url()?>statistik/index/1"><li <?php if($lap==1){?>class="selected"<?php }?>>
      Pekerjaan</li></a>
    <a href="<?php echo site_url()?>statistik/index/2"><li <?php if($lap==2){?>class="selected"<?php }?>>
      Status Perkawinan</li></a>
    <a href="<?php echo site_url()?>statistik/index/3"><li <?php if($lap==3){?>class="selected"<?php }?>>
      Agama</li></a>
    <a href="<?php echo site_url()?>statistik/index/4"><li <?php if($lap==4){?>class="selected"<?php }?>>
      Jenis Kelamin</li></a>
    <a href="<?php echo site_url()?>statistik/index/5"><li <?php if($lap==5){?>class="selected"<?php }?>>
      Warga Negara</li></a>
    <a href="<?php echo site_url()?>statistik/index/6"><li <?php if($lap==6){?>class="selected"<?php }?>>
      Status Penduduk</li></a>
    <a href="<?php echo site_url()?>statistik/index/7"><li <?php if($lap==7){?>class="selected"<?php }?>>
      Golongan Darah</li></a>
    <a href="<?php echo site_url()?>statistik/index/9"><li <?php if($lap==9){?>class="selected"<?php }?>>
      Penyandang Cacat</li></a>
    <a href="<?php echo site_url()?>statistik/index/16"><li <?php if($lap==16){?>class="selected"<?php }?>>
      Akseptor KB</li></a>
    <a href="<?php echo site_url()?>statistik/index/17"><li <?php if($lap==17){?>class="selected"<?php }?>>
      Akte Kelahiran</li></a>
  </ul>
</div>

<div class="lmenu">
  <legend>Statistik Program Bantuan <span class="fa fa-chevron-down"></span></legend>
  <ul id="bantuan">
    <?php foreach ($list_bantuan as $bantuan): ?>
      <li <?php if($lap==$bantuan['lap']){?>class="selected"<?php }?>>
        <a href="<?php echo site_url()?>statistik/index/<?php echo $bantuan['lap']?>"><?php echo $bantuan['nama']." (".$bantuan['lap'].")"?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
