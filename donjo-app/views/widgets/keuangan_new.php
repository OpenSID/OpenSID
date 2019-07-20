<!-- Widget Statistik -->
<?php
  $widget_data = json_decode($widget_keuangan, true);
?>
<style type="text/css">
  .box-body > select{
    margin-left: 0;
  }
</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/keuangan/1")?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
    <select id="keuangan-selector" class="form-control">
      <option value="2016">2016</option>
      <option value="2017">2017</option>
    </select>
    <div class="progress">
      <div id="progress-pendapatan" class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%"></div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <p>Realisasi</p>
        <h3 id="realisasi-angka"><?= $widget_data[2016]['realisasi'] ?></h3>
      </div>
      <div class="col-md-6">
        <p>Anggaran</p>
        <h3 id="pagu-angka"><?= $widget_data[2016]['pagu'] ?></h3>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var data = <?= $widget_keuangan ?>;

  $(document).ready(function()
  {
    var tahun = $("#keuangan-selector").val();
    var realisasi = data[tahun]['realisasi'];
    var pagu = data[tahun]['pagu'];
    $("#progress-pendapatan").css("width", realisasi / pagu * 100 + "%");
    $("#pagu-angka").text(data[tahun]['pagu']);
    $("#realisasi-angka").text(data[tahun]['realisasi']);
  });
  
  $("#keuangan-selector").change(function(){
    var tahun = $("#keuangan-selector").val();
    realisasi = data[tahun]['realisasi'];
    pagu = data[tahun]['pagu'];
    $("#progress-pendapatan").css("width", realisasi / pagu * 100 + "%");
    $("#pagu-angka").text(data[tahun]['pagu']);
    $("#realisasi-angka").text(data[tahun]['realisasi']);
  });
</script>