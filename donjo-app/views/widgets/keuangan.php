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
    padding-top: 5px;
  }

  #widget-keuangan-container p{
    font-size: 12px;
    margin-bottom: 20px;
  }

  #grafik-container{
    /*background-color: #999*/ 
  }

  .graph{
    /*height: 100px;*/
  }

  .keuangan-selector{
    font-size: 12px;
  }

  span.icon-bar{
    display: block;
    height: 3px;
    margin-bottom: 4px;
    width: 22px;
    background: #333;
    border-radius: 1px;
  }

  .dropdown-toggle{
    border: none;
  }
</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/keuangan/1")?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
    <div id="widget-keuangan-container">
      <div class="dropdown" style="float: right;">
        <button class="dropdown-toggle btn btn-default" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toogle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a class="dropdown-item" onclick="gantiTipe('pelaksanaan')">Realisasi Pelaksanaan APBDesa</a></li>
          <li><a class="dropdown-item" onclick="gantiTipe('pendapatan')">Realisasi Pendapatan Desa</a></li>
          <li><a class="dropdown-item" onclick="gantiTipe('belanja')">Realisasi Belanja Desa</a></li>
        </ul>
      </div>
      <h3></h3>
      <p id="grafik-tahun">
        <div class="col-md-12 keuangan-selector" style="text-align: center; padding-bottom: 20px">
          Data tahun <select class="form-control col-md-3" id="keuangan-selector">
            <?php 
              foreach ($widget_keuangan['tahun'] as $key):
            ?>
            <option value="<?= $key ?>"><?= $key ?></option>
            <?php 
              endforeach;
            ?>
          </select>
  <!--         <input type="hidden" value="" id="type"/>
          <input type="hidden" value="2016" id="tahun"/> -->
        </div>
      </p>
      <div id="grafik-container">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var rawData = <?= $widget_keuangan['data']; ?>;

  var year = "<?= $widget_keuangan['tahun_terbaru'] ?>";
  var type = "pelaksanaan"

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
      $("#grafik-container").append(
          "<div class='graph-sub'>"+ subData['nama'] + "</div><div id='graph-"+ idx +"' class='graph'></div>");
      Highcharts.chart("graph-"+ idx, {
          chart: {
            type: 'bar',
            margin: 0,
            height: 50,
            backgroundColor: "rgba(0,0,0,0)",
            spacingBottom: 0,
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
    });
    $("p#grafik-tahun").text("Tahun " + year);
  }

  function resetContainer(){
    $("#grafik-container").html("");
  }

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
