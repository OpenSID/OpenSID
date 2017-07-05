<!-- widget Sinergi Program-->

<style>
  #sinergi_program { text-align: center; }
  #sinergi_program table { margin: auto; }
  #sinergi_program img {
    max-width: 100%;
    max-height: 100%;
    transition: all 0.5s;
    -o-transition: all 0.5s;
    -moz-transition: all 0.5s;
    -webkit-transition: all 0.5s;
  }
  #sinergi_program img:hover {
   transition: all 0.3s;
    -o-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    transform: scale(1.5);
    -moz-transform: scale(1.5);
    -o-transform: scale(1.5);
    -webkit-transform: scale(1.5);
    box-shadow: 2px 2px 6px rgba(0,0,0,0.5);
  }
</style>
<div class="box box-warning box-solid">
  <div class="box-header">
    <h3 class="box-title"><i class="fa fa-external-link"></i> Sinergi Program</h3>
  </div>
  <div id="sinergi_program" class="box-body">
    <table>
      <?php foreach($sinergi_program as $key => $program) {
          $baris[$program['baris']][$program['kolom']] = $program;
        }
      ?>
      <?php foreach($baris as $baris_program) : ?>
        <tr>
          <td>
            <?php $width = 100/count($baris_program)-count($baris_program)?>
            <?php foreach($baris_program as $key => $program) : ?>
              <span style="display: inline-block; width: <?php echo $width.'%'?>">
                <a href="<?php echo $program['tautan']?>" target="_blank"><img src="<?php echo base_url()?>desa/upload/widget/<?php echo $program['gambar']?>" alt="<?php echo $program['judul']?>" /></a>
              </span>
            <?php endforeach; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>