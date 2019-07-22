<!-- Widget Statistik -->
<?php
  $widget_data = json_decode($widget_keuangan, true);
?>
<style type="text/css">
  .box-body > select{
    margin-left: 0;
  }

  .progress{
    margin-bottom: 10px;
    height: 30px;
  }

  .progress #progress-pendapatan{
    background-color: rgb(32, 124, 229);
  }

  .progress #progress-belanja{
    background-color: #28a745;
  }

  .keuangan-sub{
    padding-top: 20px;
  }

  .keuangan-angka-sub{
    padding-top: 8px;
    padding-bottom: 12px;
    text-align: center;
  }

  .keuangan-angka-sub span{
    padding-bottom: 10px;
    display: block;
    font-size: 12px;
  }

  .keuangan-angka-sub h3{
    font-size: 2rem;
  }

</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/keuangan/1")?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
    Tahun <select id="keuangan-selector">
      <option value="2016">2016</option>
      <option value="2017">2017</option>
    </select>
    <div class="keuangan-sub">
      <h3>Pendapatan Desa</h3>
      <div class="progress">
        <div id="progress-pendapatan" class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 0%"><span style="vertical-align: middle;">100 %</span></div>
      </div>

      <div class="row keuangan-angka">
        <div class="col-md-6 keuangan-angka-sub">
          <span>Realisasi</span>
          <h3 id="realisasi-pendapatan"></h3>
        </div>
        <div class="col-md-6 keuangan-angka-sub">
          <span>Anggaran</span>
          <h3 id="pagu-pendapatan"></h3>
        </div>
      </div>
    </div>
    <div class="keuangan-sub">
      <h3>Belanja Desa</h3>
      <div class="progress">
        <div id="progress-belanja" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%"></div>
      </div>

      <div class="row keuangan-angka">
        <div class="col-md-6 keuangan-angka-sub">
          <span>Realisasi</span>
          <h3 id="realisasi-belanja"></h3>
        </div>
        <div class="col-md-6 keuangan-angka-sub">
          <span>Anggaran</span>
          <h3 id="pagu-belanja"></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var data = <?= $widget_keuangan ?>;

  $(document).ready(function()
  {
    var tahun = $("#keuangan-selector").val();
    var realisasiPendapatan = data[tahun]['pendapatan']['realisasi'];
    var paguPendapatan = data[tahun]['pendapatan']['pagu'];
    var realisasiBelanja = data[tahun]['belanja']['realisasi'];
    var paguBelanja = data[tahun]['belanja']['pagu'];
    $("#progress-pendapatan").css("width", realisasiPendapatan / paguPendapatan * 100 + "%");
    $("#progress-belanja").css("width", realisasiBelanja / paguBelanja * 100 + "%");
    $("#pagu-pendapatan").text(paguPendapatan);
    $("#realisasi-pendapatan").text(realisasiPendapatan);
    $("#pagu-belanja").text(paguBelanja);
    $("#realisasi-belanja").text(realisasiBelanja);
  });
  
  $("#keuangan-selector").change(function(){
    var tahun = $("#keuangan-selector").val();
    var realisasiPendapatan = data[tahun]['pendapatan']['realisasi'];
    var paguPendapatan = data[tahun]['pendapatan']['pagu'];
    var realisasiBelanja = data[tahun]['belanja']['realisasi'];
    var paguBelanja = data[tahun]['belanja']['pagu'];
    $("#progress-pendapatan").css("width", realisasiPendapatan / paguPendapatan * 100 + "%");
    $("#progress-belanja").css("width", realisasiBelanja / paguBelanja * 100 + "%");
    $("#pagu-pendapatan").text(paguPendapatan);
    $("#realisasi-pendapatan").text(realisasiPendapatan);
    $("#pagu-belanja").text(paguBelanja);
    $("#realisasi-belanja").text(realisasiBelanja);
  });
</script>
