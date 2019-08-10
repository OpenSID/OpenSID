<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Menghasilkan grafik & tabel dari database
 * konversi isi artikel yang berisi kode_tampilan
 */

if (!function_exists('shortcode'))
{
	// Ambil jenis shortcode
	function shortcode($str = '')
	{
		$regex = "/\[\[(.*?)\]\]/";
		return preg_replace_callback($regex, function ($matches) {
			$result = array();

			$params_explode = explode(",", $matches[1]);
			$fnName = 'extract_shortcode';
			return extract_shortcode($params_explode[0],$params_explode[1],$params_explode[2]);
		}, $str);
	}

	function extract_shortcode($type, $smt, $thn)
	{
		$CI =& get_instance();
		$CI->load->model('keuangan_model');
		
		if ($type == 'grafik-RP-APBD') 
		{
			$data = $CI->keuangan_model->rp_apbd($smt, $thn);
			$jenisbelanja = array();
			foreach ($data['jenis_belanja'] as $j) 
			{
				$jenisbelanja[] = "'". $j['Nama_Akun']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $p) 
			{
				$anggaran[] = $p['AnggaranStlhPAK'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $s) 
			{ 
				if(!empty($s['Nilai']) || !is_null($s['Nilai']))
				{ 
					$realisasi[] =  $s['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.setOptions({lang: {thousandsSep: '.'}});".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Realisasi Pelaksanaan APBDesa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: ['(PA) Pendapatan Desa', '(PA) Belanja Desa', '(PA) Pembiayaan Desa'],
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Realisasi'
					        },
					        labels: {
					            overflow: 'justify',
					            enabled: false
					        }
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
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',
					        dataLabels: {
					        	formatter: function () {
					        		return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',');
					        	}
					        },
							color: '#16a085',
					        data: [". join($anggaran, ",")."]
						}, {
						    name: 'Realisasi',
						    dataLabels: {
							    formatter: function () {
							    	var index = this.series.index;
							    	var pointB = this.series.chart.series[0].data[index].y;
							    	var percent = Highcharts.numberFormat(this.y / pointB * 100, '.', ',');
							    	return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',') + ' (' +	percent + ' %'+')';
							    }
						    },
							color: '#f1c40f',
					        data: [". join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif ($type == 'grafik-R-PD') 
		{
			$data = $CI->keuangan_model->r_pd($smt, $thn);
			$jp = array();
			foreach ($data['jenis_pendapatan'] as $b) 
			{
				$jp[] = "'". $b['Nama_Jenis']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $a) 
			{
				$anggaran[] = $a['Pagu'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $r) 
			{ 
				if(!empty($r['Nilai']) || !is_null($r['Nilai']))
				{ 
					$realisasi[] =  $r['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.setOptions({lang: {thousandsSep: '.'}});".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Realisasi Pendapatan Desa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: [".join($jp, ",")."],
					    },
					    yAxis: {
					        min: 0,					        
					        title: {
					            text: 'Realisasi'
					        },
					        labels: {
					            overflow: 'justify',
					            enabled: false
					        }
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
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',					        
					        dataLabels: {
					        	formatter: function () {
					        		return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',');
					        	}
					        },
							color: '#3498db',
					        data: [".join($anggaran, ",")."]
				        },{
					        name: 'Realisasi',
					        dataLabels: {
							    formatter: function () {
							    	var index = this.series.index;
							    	var pointB = this.series.chart.series[0].data[index].y;
							    	var percent = Highcharts.numberFormat(this.y / pointB * 100, '.', ',');
							    	return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',') + ' (' +	percent + ' %'+')';
							    }
						    },
							color: '#e67e22',
					        data: [".join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif ($type == 'grafik-R-BD') 
		{
			$data = $CI->keuangan_model->r_bd($smt, $thn);
			$bidang = array();
			foreach ($data['bidang'] as $b) 
			{
				$bidang[] = "'". $b['Nama_Bidang']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $a) 
			{
				$anggaran[] = $a['Pagu'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $r) 
			{ 
				if(!empty($r['Nilai']) || !is_null($r['Nilai']))
				{ 
					$realisasi[] =  $r['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.setOptions({lang: {thousandsSep: '.'}});".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Realisasi Belanja Desa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: [". join($bidang, ",")."],
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Realisasi'
					        },
					        labels: {
					            overflow: 'justify',
					            enabled: false
					        }
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
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',
					        dataLabels: {
					        	formatter: function () {
					        		return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',');
					        	}
					        },
							color: '#2E8B57',
					        data: [". join($anggaran, ",")."]
						},{
					        name: 'Realisasi',
					        dataLabels: {
							    formatter: function () {
							    	var index = this.series.index;
							    	var pointB = this.series.chart.series[0].data[index].y;
							    	var percent = Highcharts.numberFormat(this.y / pointB * 100, '.', ',');
							    	return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',') + ' (' +	percent + ' %'+')';
							    }
						    },
							color: '#3461eb',
					        data: [". join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif($type == 'grafik-R-PEMDES') 
		{
			$data = $CI->keuangan_model->r_pembiayaan($smt, $thn);
			$pembiayaan = array();
			foreach ($data['pembiayaan'] as $d) 
			{
				$pembiayaan[] = "'". $d['Nama_Kelompok']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $a) 
			{
				$anggaran[] = $a['Pagu'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $r) 
			{ 
				if(!empty($r['Nilai']) || !is_null($r['Nilai']))
				{ 
					$realisasi[] =  $r['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.setOptions({lang: {thousandsSep: '.'}});".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Pembiayaan Desa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: [". join($pembiayaan, ",")."],
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Realisasi'
					        },
					        labels: {
					            overflow: 'justify',
					            enabled: false
					        }
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
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',
					        dataLabels: {
					        	formatter: function () {
					        		return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',');
					        	}
					        },
							color: '#2E8B57',
					        data: [". join($anggaran, ",")."]
						},{
					        name: 'Realisasi',
					        dataLabels: {
							    formatter: function () {
							    	var index = this.series.index;
							    	var pointB = this.series.chart.series[0].data[index].y;
							    	var percent = Highcharts.numberFormat(this.y / pointB * 100, '.', ',');
							    	return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',') + ' (' +	percent + ' %'+')';
							    }
						    },
							color: '#3461eb',
					        data: [". join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif($type == 'lap-RP-APBD') 
		{
			$data = $CI->keuangan_model->lap_rp_apbd($smt, $thn);
			ob_start();
			echo "<style>
					table.blueTable {
					  border: 1px solid #1C6EA4;
					  background-color: #EEEEEE;
					  width: 100%;
					  text-align: left;
					  border-collapse: collapse;
					}
					table.blueTable td, table.blueTable th {
					  border: 1px solid #AAAAAA;
					  padding: 3px 2px;
					}
					table.blueTable tbody td {
					  font-size: 13px;
					}
					table.blueTable tr:nth-child(even) {
					  background: #D0E4F5;
					}
					table.blueTable thead {
					  background: #1C6EA4;
					  background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
					  background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
					  background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
					  border-bottom: 2px solid #444444;
					}
					table.blueTable thead th {
					  font-size: 15px;
					  font-weight: bold;
					  color: #FFFFFF;
					  text-align: center;
					  border-left: 2px solid #D0E4F5;
					}
					table.blueTable thead th:first-child {
					  border-left: none;
					}
					.bold{
						font-weight: bold;
					}
					.highlighted{
						background-color: #FFFF00
					}
					</style>".
			"<table class='blueTable' width='100%'>".
			"<thead><tr><th colspan='5'>Uraian</th><th>Anggaran (Rp)</th><th>Realisasi (Rp)</th><th>Sisa Anggaran (Rp)</th><th>Persentase (%)</th></tr></thead>";
			foreach ($data['pendapatan'] as $l) 
			{
				$i=0;
				echo "<tr class='bold highlighted'>".
				"<td colspan='5'>".$l['Akun']." ".$l['Nama_Akun']."</td>".
				"<td align='right'>".number_format($l['anggaran'][0]['pagu'])."</td>".
				"<td align='right'>".number_format($l['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($l['anggaran'][0]['pagu'] - $l['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($l['realisasi'][0]['realisasi']/$l['anggaran'][0]['pagu']*100, 2)."</td>";
				foreach ($l['sub_pendapatan'] as $s) 
				{
					$j=0;
					echo "<tr class='bold'>".
					"<td>".$s['Kelompok']."</td><td colspan='4'>".$s['Nama_Kelompok']."</td>".
					"<td align='right'>".number_format($s['anggaran'][0]['pagu'])."</td>".
					"<td align='right'>".number_format($s['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($s['anggaran'][0]['pagu']-$s['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($s['realisasi'][0]['realisasi']/$s['anggaran'][0]['pagu']*100, 2)."</td>".
					"</tr>";
					foreach ($s['sub_pendapatan2'] as $q) 
					{
						echo "<tr>".
						"<td></td><td colspan='3'>".$q['Jenis']."</td>".
						"<td>".$q['Nama_Jenis']."</td>".
						"<td align='right'>".number_format($q['anggaran'][0]['pagu'])."</td>".
						"<td align='right'>".number_format($q['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($q['anggaran'][0]['pagu']-$q['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($q['realisasi'][0]['realisasi']/$q['anggaran'][0]['pagu']*100, 2)."</td>".
						"</tr>";
					};
					$j++;
				};			
				echo "</tr>";
				$i++;
			}
			foreach ($data['belanja'] as $b) 
			{
				$k=0;
				echo "<tr class='bold highlighted'>".
				"<td colspan='5' class='bold highlighted'>".$b['Akun']." ".$b['Nama_Akun']."</td>".
				"<td align='right' class='bold highlighted'>".number_format($b['anggaran'][0]['pagu'])."</td>".
				"<td align='right' class='bold highlighted'>".number_format($b['realisasi'][0]['realisasi'])."</td>".
				"<td align='right' class='bold highlighted'>".number_format($b['anggaran'][0]['pagu'] - $b['realisasi'][0]['realisasi'])."</td>".
				"<td align='right' class='bold highlighted'>".number_format($b['realisasi'][0]['realisasi']/$b['anggaran'][0]['pagu']*100, 2)."</td>";
				foreach ($b['sub_belanja'] as $sb) 
				{
					$l=0;
					echo "<tr class='bold'>".
					"<td align='right'>".substr($sb['Kd_Bid'], 6)."."."</td><td colspan='4'>".$sb['Nama_Bidang']."</td>".
					"<td align='right'>".number_format($sb['anggaran'][0]['pagu'])."</td>".
					"<td align='right'>".number_format($sb['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($sb['anggaran'][0]['pagu']-$sb['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($sb['realisasi'][0]['realisasi']/$sb['anggaran'][0]['pagu']*100, 2)."</td>".
					"</tr>";
					$l++;
					foreach ($sb['sub_belanja2'] as $sb2) 
					{
						$l=0;
						echo "<tr class='bold'>".
						"<td></td><td align='right'>".substr($sb2['Kd_Keg'], 6)."</td><td colspan='3'>".$sb2['Nama_Kegiatan']."</td>".
						"<td align='right'>".number_format($sb2['anggaran'][0]['pagu'])."</td>".
						"<td align='right'>".number_format($sb2['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($sb2['anggaran'][0]['pagu']-$sb2['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($sb2['realisasi'][0]['realisasi']/$sb2['anggaran'][0]['pagu']*100, 2)."</td>".
						"</tr>";
						$l++;
						foreach ($sb2['sub_belanja3'] as $sb3) 
						{
							$m=0;
							echo "<tr class=''>".
							"<td></td><td></td><td align='right'>".$sb3['Jenis']."</td><td colspan='2'>".$sb3['Nama_Jenis']."</td>".
							"<td align='right'>".number_format($sb3['anggaran'][0]['pagu'])."</td>".
							"<td align='right'>".number_format($sb3['realisasi'][0]['realisasi'])."</td>".
							"<td align='right'>".number_format($sb3['anggaran'][0]['pagu']-$sb3['realisasi'][0]['realisasi'])."</td>".
							"<td align='right'>".number_format($sb3['realisasi'][0]['realisasi']/$sb3['anggaran'][0]['pagu']*100, 2)."</td>".
							"</tr>";
							$m++;
						};
					};
				};
				echo "</tr>";
				$k++;
			}
			foreach ($data['pembiayaan'] as $a) 
			{
				$o=0;
				echo "<tr class='bold highlighted'>".
				"<td colspan='5'>".$a['Akun']." ".$a['Nama_Akun']."</td>".
				"<td align='right'>".number_format($a['anggaran'][0]['pagu'])."</td>".
				"<td align='right'>".number_format($a['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($a['anggaran'][0]['pagu'] - $l['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($a['realisasi'][0]['realisasi']/$a['anggaran'][0]['pagu']*100, 2)."</td>";
				foreach ($a['sub_pembiayaan'] as $b) 
				{
					$p=0;
					echo "<tr class='bold'>".
					"<td>".$b['Kelompok']."</td><td colspan='4'>".$b['Nama_Kelompok']."</td>".
					"<td align='right'>".number_format($b['anggaran'][0]['pagu'])."</td>".
					"<td align='right'>".number_format($b['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($b['anggaran'][0]['pagu']-$b['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($b['realisasi'][0]['realisasi']/$b['anggaran'][0]['pagu']*100, 2)."</td>".
					"</tr>";
					foreach ($b['sub_pendapatan2'] as $c) 
					{
						echo "<tr>".
						"<td></td><td colspan='3'>".$c['Jenis']."</td>".
						"<td>".$c['Nama_Jenis']."</td>".
						"<td align='right'>".number_format($c['anggaran'][0]['pagu'])."</td>".
						"<td align='right'>".number_format($c['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($c['anggaran'][0]['pagu']-$c['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($c['realisasi'][0]['realisasi']/$c['anggaran'][0]['pagu']*100, 2)."</td>".
						"</tr>";
					};
					$p++;
				};			
				echo "</tr>";
				$o++;
			}
			echo "<tr class='bold highlighted'><td colspan='5' align='center'>TOTAL</td><td align='right'>".number_format($data['pendapatan'][0]['total_anggaran'][0]['pagu'])."</td><td align='right'>".number_format($data['pendapatan'][0]['total_realisasi'][0]['realisasi'])."</td><td align='right'>".number_format($data['pendapatan'][0]['total_anggaran'][0]['pagu']-$data['pendapatan'][0]['total_realisasi'][0]['realisasi'])."</td><td align='right'>".number_format($data['pendapatan'][0]['total_realisasi'][0]['realisasi']/$data['pendapatan'][0]['total_anggaran'][0]['pagu']*100, 2)."</td></tr>".
			"</table>";
			$output = ob_get_clean();
			return $output;			
		}
	}

}