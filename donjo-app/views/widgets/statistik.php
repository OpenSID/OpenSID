<!-- widget Statistik -->
<style type="text/css">
  .highcharts-xaxis-labels tspan {font-size: 8px;}
</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/statistik/1")?>"><i class="fa fa-bar-chart"></i> Statistik <?= ucwords($this->setting->sebutan_desa),' ', $desa["nama_desa"];?></a></h3>
  </div>
  <div class="box-body">
    <script type="text/javascript">
    $(function () {
        var chart_widget;
        $(document).ready(function () {
          // Build the chart
            chart_widget = new Highcharts.Chart({
                chart: {
                    renderTo: 'container_widget',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                  text: 'Jumlah Penduduk'
                },
          yAxis: {
                title: {
                  text: 'Jumlah'
                }
          },
          xAxis: {
            categories:
            [
            <?php foreach($stat_widget as $data): ?>
              <?php if ($data['jumlah'] != "-" AND $data['nama']!= "JUMLAH"): ?>
                ['<?= $data['jumlah']?> <br> <?= $data['nama']?>'],
              <?php endif; ?>
            <?php endforeach; ?>
            ]
          },
          legend: {
            enabled:false
          },
          plotOptions: {
            series: {
              colorByPoint: true
            },
            column: {
              pointPadding: 0,
              borderWidth: 0
            }
          },
            series: [{
                type: 'column',
                name: 'Populasi',
                data: [
            <?php foreach ($stat_widget as $data): ?>
              <?php if ($data['jumlah'] != "-" AND $data['nama']!= "JUMLAH"): ?>
                ['<?= $data['nama']?>',<?= $data['jumlah']?>],
              <?php endif; ?>
            <?php endforeach; ?>
                ]
            }]
          });
        });

    });
    </script>
    <script src="<?= base_url()?>/assets/js/highcharts/highcharts.js"></script>
    <div id="container_widget" style="width: 100%; height: 150px; margin: 0 auto"></div>
  </div>
</div>
