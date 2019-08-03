<!-- widget Statistik -->
<style type="text/css">
  .highcharts-xaxis-labels tspan {font-size: 8px;}

  #keuangan-title{
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    padding-bottom: 16px;
  }

  .graph-sub {
    font-family: 'Courier New', monospace;
    /*font-style: italic;*/
    font-size: 10px;
    /*padding-bottom: 40px;*/
    /*fill: #000;*/
  }

  #widget-keuangan-container{
    text-align: center;
  }

  #widget-keuangan-container h3{
    font-size: 20px;
    /*font-weight: bold;*/
  }

  #widget-keuangan-container p{
    font-size: 12px;
    margin-bottom: 20px;
  }

  #graph-container{
    /*background-color: #999*/
  }

  .graph{
    height: 100px;
  }
</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/keuangan/1")?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
    <div id="widget-keuangan-container">
      <div class="dropdown" style="float: right;">
        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          &#x25bc;
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a class="dropdown-item" onclick="gantiTipe('pelaksanaan')">Realisasi Pelaksanaan APBDesa</a></li>
          <li><a class="dropdown-item" onclick="gantiTipe('pendapatan')">Realisasi Pendapatan Desa</a></li>
          <li><a class="dropdown-item" onclick="gantiTipe('belanja')">Realisasi Belanja Desa</a></li>
        </ul>
      </div>
      <h3></h3>
      <p id="grafik-tahun"></p>
      <div class="col-md-12 keuangan-selector" style="text-align: center; padding-bottom: 20px">
        Data tahun <select id="keuangan-selector">
          <?php 
            foreach (json_decode($widget_keuangan) as $key => $value):
          ?>
          <option value="<?= $key ?>"><?= $key ?></option>
          <?php 
            endforeach;
          ?>
        </select>
<!--         <input type="hidden" value="" id="type"/>
        <input type="hidden" value="2016" id="tahun"/> -->
      </div>
      <div id="grafik-container">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var rawData = <?= $widget_keuangan; ?>;

  function displayChart(tahun, tipe){
    resetContainer();
    switch(tipe){
      case "pelaksanaan":
        var judulGrafik = 'Realisasi Pelaksanaan APBDesa';
        var tipeGrafik = 'res_pelaksanaan';
        break;

      case "belanja":
        var judulGrafik = 'Realisasi Belanja Desa';
        var tipeGrafik = 'res_belanja';
        break;

      case "pendapatan":
        var judulGrafik = 'Realisasi Pendapatan Desa';
        var tipeGrafik = 'res_pendapatan';
        break;
    }
    var chartData = rawData[tahun][tipeGrafik];
    $("#widget-keuangan-container h3").text(judulGrafik);
    //Eksekusi chart dengan for loop
    chartData.forEach(function(subData, idx){
      // var chartOption = option(subData['anggaran'], subData['realisasi']);
      $("#grafik-container").append(
          "<div class='graph-sub'>"+ subData['nama'] + "</div><div id='graph-"+ idx +"'></div>");
      Highcharts.chart("graph-"+ idx, {
          chart: {
              type: 'bar',
              margin: 0,
              height: 90,
              backgroundColor: "rgba(0,0,0,0)",
          },
          title: {
              text: ''
          },
          subtitle: {
            y: -2,
            style: {"color" : "#000"},
            text: '',
          },

          xAxis: {
              visible: false,
              categories: [''],
          },
          tooltip: {
              valueSuffix: ''
          },
          plotOptions: {
              bar: {
                  dataLabels: {
                      enabled: true
                  }
              }
          },
          credits: {
            enabled: false
          },
          yAxis: {
            visible: false
          },
          exporting: {
            enabled: false
          },
          legend: {
            enabled: false
          },
          series: [{
              name: 'Anggaran',
              color: '#2E8B57',
              data: [parseInt(subData['anggaran'])],
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [parseInt(subData['realisasi'])],
          }]
      });
    })
  }

  function resetContainer(){
    $("#grafik-container").html("");
  }

  var year = "2016";
  var type = "pelaksanaan"

  function gantiTahun(newThn){
    year = newThn;
    displayChart(year, type);
  }

  function gantiTipe(newType){
    type = newType;
    displayChart(year, type);
  }

  $("#keuangan-selector").change(function(){
    gantiTahun($("#keuangan-selector").val());
  })

	$(document).ready(function (){
    //Realisasi Pelaksanaan APBD
    displayChart(year, type);
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
