 <!-- widget Statistik Penduduk -->

  <div class="single_bottom_rightbar">
    <h2><i class="fa fa-bar-chart-o"></i> Statistik Penduduk</h2>
  </div>
  <div style="margin-bottom:20px;">
      <?php
      $cowok1 = $this->db->query('SELECT sex FROM tweb_penduduk WHERE sex = 1');
      $cewek1 = $this->db->query('SELECT sex FROM tweb_penduduk WHERE sex = 2');
      $kk1 = $this->db->query('SELECT * FROM tweb_keluarga');
      
      $cowok = $cowok1->num_rows();
      $cewek = $cewek1->num_rows();
      $dua = $cowok+$cewek;
      $kk = $kk1->num_rows(); ?>
        <div class="progress-group">
            LAKI-LAKI
            <div class="progress progress-sm active" align="right"><small><b><?php echo number_format($cowok);?> Jiwa</small></b>&nbsp;
                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"  style="width: <?php echo $cowok/$dua*100; ?>%"></div>
            </div>
        </div>
        <div class="progress-group">
            PEREMPUAN
            <div class="progress progress-sm active" align="right"><small><b><?php echo number_format($cewek);?> Jiwa</small></b>&nbsp;
                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width: <?php echo $cewek/$dua*100; ?>%"></div>
            </div>
        </div><hr>
        <div class="progress-group">
            <button type="button" class="btn btn-success btn-block">Jumlah <?php echo number_format($kk);?> KK | <?php echo number_format($dua);?> Jiwa</button>
        </div>
  </div>