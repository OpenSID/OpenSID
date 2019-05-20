<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php foreach ($teks_berjalan AS $isi): ?>
<?php $pecah = explode(" ", $isi);
for ($i=0; $i<=sizeof($pecah)-1; $i++)
{
    if ((substr($pecah[$i], 0, 7) == 'http://') && ($pecah[$i] != 'http://'));
    else if ((substr($pecah[$i], 0, 8) == 'https://') && ($pecah[$i] != 'https://'));
    else if ((substr($pecah[$i], 0, 3) == 'www') && ($pecah[$i] != 'www'))
    $isi = str_replace($pecah[$i], "<a href='http://".$pecah[$i]."'>Selengkapnya...</a>", $isi);
} ?>
<span style="font-family: Oswald; padding-right: 50px;"><?= $isi?></span>
<?php endforeach; ?>
