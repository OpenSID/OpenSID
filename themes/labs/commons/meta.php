<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<meta content="utf-8" http-equiv="encoding">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name='google' content='notranslate' />
<meta name='theme' content='LABS' />
<meta name='designer' content='Nazrul Rahim' />
<meta name='theme:designer' content='Nazrul Rahim' />
<meta name='theme:version' content='0.01' />
<meta name='dev:themes' content='codebase' />
<meta name="keywords"
    content="<?= $this->setting->website_title.' '.ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'];?>" />
<meta property="og:site_name" content="<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'];?>" />
<meta property="og:type" content="article" />
<meta property="fb:app_id" content="147912828718">

<title><?php
    if ($single_artikel["judul"] == "")
        echo $this->setting->website_title
            . ' ' . ucwords($this->setting->sebutan_desa)
            . (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : '');
    else echo $single_artikel["judul"]. ' - ' . ucwords($this->setting->sebutan_desa)
                    . (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?>
</title>
<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
<link rel="shortcut icon" href="<?php echo base_url()?><?php echo LOKASI_LOGO_DESA?>favicon.ico" />
<?php else: ?>
<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
<?php endif; ?>
<?php if(isset($single_artikel)): ?>
<meta property="og:title" content="<?= $single_artikel["judul"];?>" />
<meta property="og:url" content="<?= site_url()?>first/artikel/<?= $single_artikel['id'];?>" />
<meta property="og:image" content="<?= base_url()?><?= LOKASI_FOTO_ARTIKEL?>sedang_<?= $single_artikel['gambar'];?>" />
<meta property="og:description" content="<?= potong_teks($single_artikel['isi'], 300)?> ..." />
<?php else: ?>
<meta property="og:title" content="<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'];?>" />
<meta property="og:url" content="<?= site_url()?>" />
<meta property="og:image" content="<?= LogoDesa($desa['logo']);?>" />
<meta property="og:description" content="<?= $this->setting->website_title.' '.ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'];?>" />  
<?php endif; ?>