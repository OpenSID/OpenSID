<!-- widget Statistik -->

<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?php echo site_url("first/statistik/1")?>"><i class="fa fa-bar-chart"></i> Statistik <?php echo $desa["nama_desa"];?></a></h3>
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
                    text: 'Statistik Penduduk'
                },
          yAxis: {
                title: {
                  text: 'Jumlah'
                }
          },
          tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            percentageDecimals: 1
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
                data: [
            <?php  foreach($stat_widget as $data){?>
              <?php if($data['jumlah'] != "-"){?>
                ['<?php echo $data['nama']?>',<?php echo $data['jumlah']?>],
              <?php }?>
            <?php }?>
                ]
            }]
          });
        });

    });
    </script>
    <script src="<?php echo base_url()?>/assets/js/highcharts/highcharts.js"></script>
    <div id="container_widget" style="width: 100%; height: 150px; margin: 0 auto"></div>
  </div>
</div>
