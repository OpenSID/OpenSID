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
    <h3 class="box-title"><a href="<?= site_url('first/keuangan/1')?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
    <div id="widget-keuangan-container">
      <div class="dropdown" style="float: right;">
        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          &#x25bc;
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a class="dropdown-item" onclick="displayPelaksanaan()">Realisasi Pelaksanaan APBDesa</a></li>
          <li><a class="dropdown-item" onclick="displayPendapatan()">Realisasi Pendapatan Desa</a></li>
          <li><a class="dropdown-item" onclick="displayBelanja()">Realisasi Belanja Desa</a></li>
        </ul>
      </div>
      <h3></h3>
      <p>Tahun 2016 Semester 1</p>
      <div class="col-md-12 keuangan-selector" style="text-align: center; padding-bottom: 20px">
        Data tahun <select id="keuangan-selector">
          <option value="2016-1">Tahun 2016 - Semester 1</option>
          <option value="2016-2">Tahun 2016 - Semester 2</option>
        </select>
      </div>
      <div id="graph-container">
      </div>
    </div>
  </div>
</div>

<?php
  //Realisasi Pelaksanaan APBD
  $raw_data = $this->keuangan_model->rp_apbd('1', '2016');

    $res_pelaksanaan = [];
    $nama            = [
        'PENDAPATAN' => '(PA) Pendapatan Desa',
        'BELANJA'    => '(PA) Belanja Desa',
        'PEMBIAYAAN' => '(PA) Pembiayaan Desa',
    ];

    for ($i = 0; $i < count($raw_data['jenis_belanja']) / 2; $i++) {
        $row = [
            'jenis_belanja' => $raw_data['jenis_belanja'][$i]['Nama_Akun'],
            'anggaran'      => $raw_data['anggaran'][$i]['AnggaranStlhPAK'],
            'realisasi'     => $raw_data['realisasi'][$i]['Nilai'],
        ];
        $res_pelaksanaan[] = $row;
    }

    //Pendapatan APBD
    $raw_data       = $this->keuangan_model->r_pd('2', '2016');
    $res_pendapatan = [];

    foreach ($raw_data['jenis_pendapatan'] as $r) {
        $res_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
    }

    foreach ($raw_data['anggaran'] as $r) {
        $res_pendapatan[$r['jenis_pendapatan']]['anggaran'] = $r['Pagu'];
    }

    foreach ($raw_data['realisasi'] as $r) {
        $res_pendapatan[$r['jenis_pendapatan']]['realisasi'] = $r['Pagu'];
    }

    //Belanja APBD
    $raw_data    = $this->keuangan_model->r_bd('1', '2016');
    $res_belanja = [];

    foreach ($raw_data['bidang'] as $r) {
        $res_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
    }

    foreach ($raw_data['anggaran'] as $r) {
        $res_belanja[$r['Kd_Bid']]['anggaran'] = $r['Pagu'];
    }

    foreach ($raw_data['realisasi'] as $r) {
        $res_belanja[$r['Kd_Bid']]['realisasi'] = $r['Nilai'];
    }
    ?>

<script type="text/javascript">
  function displayPelaksanaan(){
    resetContainer();
    $("#widget-keuangan-container h3").text("Realisasi Pelaksanaan APBDesa");
    <?php $i = 0;

    foreach ($res_pelaksanaan as $data):?>
      $("#graph-container").append("<div class='graph-sub'><?= $nama[$data['jenis_belanja']] ?></div><div id='graph-<?= $i ?>' class='graph'></div>");
      Highcharts.chart('graph-<?= $i ?>', {
          chart: {
              type: 'bar',
              margin: 0,
              height: 140,
              styledMode: true,
              backgroundColor: "rgba(0,0,0,0)",
              spacingTop: 0,
              spacingBottom: 0,
          },
          title: {
              text: ''
          },
          subtitle: {
            text: '<?= $nama[$data['jenis_belanja']] ?>',
            y: 4,
            style: {"color" : "#000"},
            text: '',
          },

          xAxis: {
              visible: false,
              categories: ['<?= $nama[$data['jenis_belanja']] ?>'],
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
              data: [<?= $data['anggaran'] ?: 0 ?>]
              // data: 100,
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [<?= $data['realisasi'] ?: 0 ?>],
              // data: 200,
          }]
      });
    <?php $i++; endforeach; ?>
  }

  function displayPendapatan() {
    resetContainer();
    $("#widget-keuangan-container h3").text("Realisasi Pendapatan Desa");
    <?php $i = 0;

    foreach ($res_pendapatan as $data):?>
      $("#graph-container").append("<div class='graph-sub'><?= $nama[$data['jenis_belanja']] ?></div><div id='graph-<?= $i ?>'></div>");
      Highcharts.chart('graph-<?= $i ?>', {
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
            text: '<?= $data['nama'] ?>',
            y: -2,
            style: {"color" : "#000"},
            text: '',
          },

          xAxis: {
              visible: false,
              categories: ['<?= $data['nama'] ?>'],
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
              data: [<?= $data['anggaran'] ?: 0 ?>]
              // data: 100,
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [<?= $data['realisasi'] ?: 0 ?>],
              // data: 200,
          }]
      });
    <?php $i++; endforeach; ?>
  }

  function displayBelanja(){
    resetContainer();
    $("#widget-keuangan-container h3").text("Realisasi Belanja Desa");
    <?php $i = 0;

    foreach ($res_belanja as $data):?>
      $("#graph-container").append("<div class='graph-sub'><?= $data['nama'] ?></div><div id='graph-<?= $i ?>'></div>");
      Highcharts.chart('graph-<?= $i ?>', {
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
            text: '<?= $data['nama'] ?>',
            y: -2,
            style: {"color" : "#000"},
            text: '',
          },

          xAxis: {
              visible: false,
              categories: ['<?= $data['nama'] ?>'],
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
              data: [<?= $data['anggaran'] ?: 0 ?>],
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [<?= $data['realisasi'] ?: 0 ?>],
          }]
      });
    <?php $i++; endforeach; ?>
  }

  function resetContainer(){
    $("#graph-container").html("");
  }

	$(document).ready(function (){
    //Realisasi Pelaksanaan APBD
    displayPelaksanaan();
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
