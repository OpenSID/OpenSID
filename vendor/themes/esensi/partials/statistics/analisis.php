<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="text-h2"><?= IS_PREMIUM ? $indikator['pertanyaan'] : $indikator; ?></h1>

<div class="content space-y-5">
  <div class="ui-layout-center" id="chart" style="padding: 5px;"></div>
  <table class="table table-responsive table-striped">
    <thead>
      <tr>
        <th width="30%">No</th>
        <th>Jawaban</th>
        <th>Jumlah Responden</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($list_jawab as $data): ?>
      <tr>
        <td><?= $data['no']; ?></td>
        <td><?= $data['jawaban']; ?></td>
        <td><?= $data['nilai']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    printChart();
  });

  let chart;

  function printChart() {
    chart = new Highcharts.Chart({
      chart: {
        renderTo: 'chart',
        border: 0,
        defaultSeriesType: 'column'
      },
      title: {
        text: '<?= IS_PREMIUM ? $indikator['pertanyaan'] : $indikator; ?>'
      },
      xAxis: {
        title: {
          text: ''
        },
        categories: [
          <?php $i=0;foreach ($list_jawab as $data){$i++;?>
            <?php if ($data['nilai'] != "-"){echo "'$data[jawaban]',";}?>
          <?php }?>
        ]
      },
      yAxis: {
        title: {
          text: 'Jumlah Populasi'
        }
      },
      legend: {
        layout: 'vertical',
        enabled: false
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
        shadow: 1,
        border: 0,
        data: [
          <?php foreach ($list_jawab as $data){?>
            <?php if ($data['jawaban'] != "TOTAL"){?>
              <?php if ($data['nilai'] != "-"){?>
                <?= $data['nilai']?>,
              <?php }?>
            <?php }?>
          <?php }?>
        ]
      }]
    });
  };
</script>