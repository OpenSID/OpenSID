<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(!empty($widget_keuangan['tahun']) && !is_null($widget_keuangan['tahun'])): ?>
<!-- widget Statistik -->
<style type="text/css">
  .graph,
  .graph-sub {
    padding: 0 12px;
    padding-top: 4px;
  }

  .graph-sub {
    font-family: 'Courier New', monospace;
    font-size: 10px;
    color: #333;
    font-weight: bold;
    text-align: left;
    white-space: nowrap;
  }

  .graph {
    padding-top: 4px;
  }

  .graph-not-available {
    text-align: center;
    font-family: 'Courier New', monospace;
    font-size: 12px;
  }

  #graph-legend {
    padding: 0;
    padding-bottom: 12px;
  }

  .highcharts-container,
  svg:not(:root) {
    overflow: visible !important;
    position: absolute;
  }

  .highcharts-tooltip>span {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid silver;
    border-radius: 3px;
    box-shadow: 1px 1px 2px #888;
    padding: 8px;
  }
</style>
<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title">
      <a href="<?= site_url('artikel/kategori/1001') ?>"><i class="fa fa-chart-bar mr-1"></i><?= $judul_widget ?></a>
    </h3>
  </div>
  <div class="box-body">
    <div id="widget-keuangan-container">
      <div class="text-center">
        <div x-data="{
            open: false,
            toggle() {
                if (this.open) {
                    return this.close()
                }
    
                this.open = true
            },
            close(focusAfter) {
                this.open = false
    
                focusAfter && focusAfter.focus()
            }
        }" x-on:keydown.escape.prevent.stop="close($refs.button)"
          x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" class="relative text-right">
          <!-- Button -->
          <button x-ref="button" x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')"
            type="button" class="btn btn-primary text-sm">
            <span><i class="fas fa-bars"></i></span>
          </button>

          <!-- Panel -->
          <div x-ref="panel" x-show="open" x-transition.origin.top.left x-on:click.outside="close($refs.button)"
            :id="$id('dropdown-button')" style="display: none;"
            class="absolute right-0 mt-2 bg-white shadow-lg z-[9999]">
            <ul class="divide-y text-base font-normal">
              <?php foreach ($widget_keuangan['tahun'] as $key):?>
              <li><a href="#!" class="py-2 px-3 block"><?= $key ?></a></li>
              <li><a href="#!" class="py-2 px-3 block" @click="open = false" onclick="gantiTipe('pelaksanaan'); gantiTahun('<?= $key ?>')">Pelaksanaan
                  APBDes</a></li>
              <li><a href="#!"  class="py-2 px-3 block" @click="open = false" onclick="gantiTipe('pendapatan'); gantiTahun('<?= $key ?>')">Pendapatan
                  APBDes</a></li>
              <li><a href="#!" class="py-2 px-3 block" @click="open = false" onclick="gantiTipe('belanja'); gantiTahun('<?= $key ?>')">Belanja APBDes</a>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
        </div>
        <h3 class="text-h5 pt-2"></h3>
        <p id="grafik-tahun"></p>
      </div>
      <div id="grafik-container">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var rawData = <?= $widget_keuangan['data']; ?> ;
  var year = "<?= $widget_keuangan['tahun_terbaru'] ?>";
  var type = "pelaksanaan"

  Highcharts.setOptions({
    lang: {
      thousandsSep: '.'
    }
  })

  function displayChart(tahun, tipe) {
    resetContainer();
    switch (tipe) {
      case "pelaksanaan":
        var judulGrafik = 'Pelaksanaan APBDes';
        var tipeGrafik = 'res_pelaksanaan';
        break;

      case "belanja":
        var judulGrafik = 'Belanja APBDes';
        var tipeGrafik = 'res_belanja';
        break;

      case "pendapatan":
        var judulGrafik = 'Pendapatan APBDes';
        var tipeGrafik = 'res_pendapatan';
        break;
    }
    var chartData = rawData[tahun][tipeGrafik];
    $("#widget-keuangan-container h3").text(judulGrafik);
    $("#grafik-container").append("<div id='graph-legend' class='graph'></div>");
    Highcharts.chart("graph-legend", {
      chart: {
        type: 'bar',
        margin: 0,
        backgroundColor: "rgba(0,0,0,0)",
        spacing: [0, 0, 0, 0],
        height: 20
      },

      title: {
        text: ''
      },

      subtitle: {
        y: -2,
        style: {
          "color": "#000"
        },
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
          },
        },

        series: {
          pointPadding: 0,
          groupPadding: 0,
          dataLabels: {
            align: 'right',
            inside: true,
            shadow: false,
            color: '#000',
          },
          grouping: false,
        },
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
        padding: 0,
        margin: 0,
        verticalAlign: 'middle',
        maxHeight: 50
      },

      series: [{
          name: 'Anggaran',
          color: '#34b4eb',
          data: [],
        },
        {
          name: 'Realisasi',
          color: '#b4eb34',
          data: [],
        }
      ]
    });
    //Eksekusi chart dengan for loop
    chartData.forEach(function (subData, idx) {
      if (subData['nama']) {
        if ((!subData['realisasi'] && !subData['anggaran'])) {
          $("#grafik-container").append(
            "<div class='graph-sub' id='graph-sub-" + idx + "'>" + subData['nama'] + "</div><div id='graph-" +
            idx + "' class='graph-not-available'>Data tidak tersedia.</div>");
        } else {
          var persentase = parseInt(subData['realisasi']) / (parseInt(subData['realisasi']) + parseInt(subData[
            'anggaran'])) * 100;
          if (isNaN(persentase)) {
            persentase = 0;
          }
          persentase = Math.round(persentase);
          $("#grafik-container").append(
            "<div class='graph-sub' id='graph-sub-" + idx + "'>" + subData['nama'] + "</div><div id='graph-" +
            idx + "' class='graph'></div>");
          Highcharts.chart("graph-" + idx, {
            chart: {
              type: 'bar',
              margin: 0,
              height: 20,
              backgroundColor: "rgba(0,0,0,0)",
              spacingBottom: 0,
            },

            title: {
              text: ''
            },

            subtitle: {
              y: -2,
              style: {
                "color": "#000"
              },
              text: '',
            },

            xAxis: {
              visible: false,
              categories: [''],
            },

            tooltip: {
              valueSuffix: '',
              backgroundColor: "#fff",
              hideDelay: 0,
              shape: "square",
              outside: true,
            },

            plotOptions: {
              bar: {
                dataLabels: {
                  enabled: true
                },
              },

              series: {
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                  align: 'right',
                  inside: true,
                  shadow: false,
                  color: '#000',
                },
                grouping: false,
              },
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
              color: '#34b4eb',
              data: [parseInt(subData['anggaran'])],
              dataLabels: {
                formatter: function () {
                  if (parseInt(subData['realisasi']) <= parseInt(subData['anggaran'])) {
                    return "Rp. " + Highcharts.numberFormat(subData['anggaran'], '.', ',');
                  } else {
                    return "";
                  }
                },
                style: {
                  "textOutline": "1px contrast"
                },
              },
              tooltip: {
                pointFormatter: function () {
                  return 'Anggaran: <b>Rp. ' + Highcharts.numberFormat(this.y, '.', ',') + '</b>';
                }
              }
            }, {
              name: 'Realisasi',
              color: '#b4eb34',
              data: [parseInt(subData['realisasi'])],
              dataLabels: {
                formatter: function () {
                  if (parseInt(subData['realisasi']) > parseInt(subData['anggaran'])) {
                    return "Rp. " + Highcharts.numberFormat(subData['realisasi'], '.', ',');
                  } else {
                    return "(" + persentase + "%)";
                  }
                },
                style: {
                  "textOutline": "1px contrast"
                },
              },
              tooltip: {
                pointFormatter: function () {
                  return 'Realisasi: <b>Rp. ' + Highcharts.numberFormat(this.y, '.', ',') + '</b>';
                }
              }
            }]
          });
        }
      }
    });
    $("p#grafik-tahun").text("Tahun " + year);
  }

  function resetContainer() {
    $("#grafik-container").html("");
  }

  function gantiTahun(newThn) {
    year = newThn;
    displayChart(year, type);
  }

  function gantiTipe(newType) {
    type = newType;
    displayChart(year, type);
  }

  $("#keuangan-selector").change(function () {
    gantiTahun($("#keuangan-selector").val());
  })

  $(document).ready(function () {
    //Realisasi Pelaksanaan APBD
    $("#keuangan-selector").val("<?= $widget_keuangan['tahun_terbaru']?>")
    displayChart(year, type);
  });
</script>
<?php endif; ?>