
<?php error_reporting(E_ALL); ?>
<!-- widget Statistik -->
<style type="text/css">
  .highcharts-xaxis-labels tspan {font-size: 8px;}
  .keuangan-chart-label{
    color: #333;
    text-align: center;
    width: 100%;
    min-height: 40px;
    padding: 0;
  }

  g{
    /*display: none;*/
  }

  g.highcharts-series-group{
    /*display: inline;*/
  }

  #keuangan-title{
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    padding-bottom: 16px;
  }

  .highcharts-subtitle {
    font-family: 'Courier New', monospace;
    font-style: italic;
    padding-bottom: 20px;
    /*fill: #7cb5ec;*/
  }
</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/keuangan/1")?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
    <div class="col-md-12 keuangan-selector" style="text-align: center; padding-bottom: 20px">
      Data tahun <select id="keuangan-selector">
        <option value="2016">2016</option>
        <option value="2017">2017</option>
      </select>
    </div>
    <div id="graph-0"></div>
    <div id="graph-1"></div>
    <div id="graph-2"></div>
  </div>
</div>

<?php
  $raw_data = $this->keuangan_model->rp_apbd('1', '2016');
  // var_dump($raw_data);
  $res = array();
  for ($i = 0; $i < count($raw_data['jenis_belanja']) / 2; $i++) { 
    $row = array(
      'jenis_belanja' => $raw_data['jenis_belanja'][$i]['Nama_Akun'],
      'anggaran' => $raw_data['anggaran'][$i]['AnggaranStlhPAK'],
      'realisasi' => $raw_data['realisasi'][$i]['Nilai'],
    );
    array_push($res, $row);
  }
?>

<script type="text/javascript">
	$(document).ready(function (){
    //Realisasi Pelaksanaan APBD
    <?php $i = 0; foreach ($res as $data):?>
      Highcharts.chart('graph-<?= $i ?>', {
          chart: {
              type: 'bar',
              margin: 0,
              height: 100
          },
          title: {
              text: ''
          },
          subtitle: {
              text: '<?= $data['jenis_belanja'] ?>',
          },

          xAxis: {
              visible: false,
              categories: ['Anggaran', 'Realisasi'],
          },
          // yAxis: {
          //     min: 0,
          //     title: {
          //         text: 'Juta',
          //         align: 'high'
          //     },
          //     labels: {
          //         overflow: 'justify'
          //     }
          // },
          tooltip: {
              valueSuffix: 'juta'
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
              data: [<?= $data['anggaran'] ? $data['anggaran'] : 0 ?>]
              // data: 100,
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [<?= $data['realisasi'] ? $data['realisasi'] : 0 ?>],
              // data: 200,
          }]
      });
    <?php $i++; endforeach; ?>
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
