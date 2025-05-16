<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="article-single">
    <div class="box-header">
        <div class="box-body text-sm py-2 space-y-4">
            <div class="container-page mb-20">
                <div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
                    <div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/statistik.svg") ?>" alt=""/></div>
                    <h2>Data Convergensi Stunting</h2>
                </div>
                <div class="box-article bg-white">
                    <div class="box-statis border-grey-soft">
                        <div class="modalfixed">
                        <form method="get" action="" class="form-inline text-center">
                            <select class="form-control input-sm select2 ml-1 mr-1 mb-1 mt-1" id="kuartal" name="kuartal">
                                <option selected value="">Pilih salah satu</option>
                                <?php foreach (kuartal2() as $item): ?>
                                <option value="<?= $item['ke'] ?>" <?= $item['ke'] == $kuartal ? 'selected' : '' ?>>
                                Kuartal ke <?= $item['ke'] ?>
                                (<?= $item['bulan'] ?>)
                                </option>
                                <?php endforeach ?>
                            </select>
                            <select class="form-control input-sm select2 ml-1 mr-1 mb-1 mt-1" id="tahun" name="tahun">
                                <option selected value="">Tahun</option>
                                <?php foreach ($dataTahun as $item): ?>
                                <option value="<?= $item->tahun ?>"><?= $item->tahun ?></option>
                                <?php endforeach ?>
                            </select>
                            <select class="form-control input-sm select2 ml-1 mr-1 mb-1 mt-1" name="id_posyandu">
                                <option selected value="">Posyandu</option>
                                <?php foreach ($posyandu as $item): ?>
                                <option value="<?= $item->id ?>" <?= $item->id == $idPosyandu ? 'selected' : '' ?>>
                                <?= $item->nama ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="input-group">
                                <button type="submit" class="btn btn-info" id="cari">
                                        <i class="fa fa-search"></i> Cari
                                    </button>
                            </div>
                        </form>
                    </div>
                    <?php 
                        $listIcon = ['fa-female','fa-child', 'fa-female','fa-child','fa-child','fa-child','fa-child'];
                    ?>
                    <?php foreach($widgets as $index => $item): ?>
                    <div class="col-lg-4 col-sm-6 col-xs-12" style="padding:15px">
                        <div class="small-box bordered <?= $item['bg-color'] == 'bg-gray' ? 'bg-danger' : $item['bg-color'] ?>"  style="border-radius:5px;padding:5px">
                            <div class="row" style="padding:5px">
                                <div class="col-md-9 col-sm-8">                    
                                    <p><?= $item['title'] ?></p>
                                    <p style="font-size:40px;margin-top:0px"><?= $item['total'] ?></p>
                                </div>
                                <div class="big-screen">
                                  	<div class="col-md-3 col-sm-4">
                                        <div class="icon" style="font-size:20px;margin-top:20px">
                                            <i class="fa fa-4x <?= $listIcon[$index] ?? 'fa-female' ?>"></i>
                                        </div>
                                	</div>
                              	</div>
                            </div>                    
                            
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="box-statis border-grey-soft bg-white">
                <div class="col-md-4 col-sm-12" id="chart_0_5"></div>
                <div class="col-md-4 col-sm-12" id="chart_6_11"></div>
                <div class="col-md-4 col-sm-12" id="chart_12_23"></div>
            </div>
            <div class="box-statis border-grey-soft bg-white" style="border-radius:0 0 5px 5px;">
                <div class="grid grid-cols-1 container px-3 lg:px-5">
                    <div id="chart_posyandu"></div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){        
                const posy=    Highcharts.chart('chart_posyandu', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Grafik Kasus Stunting per-Posyandu'
                    },
                    xAxis: {
                        categories: <?= json_encode($chartStuntingPosyanduData['categories']) ?>
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Angka Kasus Stunting'
                        }
                    },            
                    colors: ['#028EFA', '#5EE497', '#FDB13B'],
                    series: <?= json_encode($chartStuntingPosyanduData['data']) ?>
                    
                })                
            })
        </script>
        <script>
            $(document).ready(function(){
                <?php foreach($chartStuntingUmurData as $item): ?>
                    Highcharts.chart('<?= $item['id'] ?>', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: '<?= $item['title'] ?>'
                    },
                    tooltip: {
                        valueSuffix: '%'
                    },    
                    plotOptions: {
                        series: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            colors: ['blue', 'red'],
                            showInLegend: true,                    
                        },
                        pie: {
                            dataLabels: {
                            enabled: true,
                            distance: -50,
                            format: '{point.y:,.1f} %'
                            }
                        }
                    },
                    series: [
                        {
                            type: 'pie',
                            name: 'percentage',
                            colorByPoint: true,
                            data: <?= json_encode($item['data']) ?>
                        }
                    ]
                    
                })
                
                <?php endforeach; ?>
            })
        </script>
        <?php $this->load->view($folder_themes . '/partials/kesehatan/scorecard', $scorecard); ?>
    </div>
</div>
