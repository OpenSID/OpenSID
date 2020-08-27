<script type="text/javascript">

Highcharts.chart('container', {
	chart: {
		height: 600,
		width: <?= $this->setting->ukuran_lebar_bagan ?>,
		inverted: true
	},

	title: {
		text: 'Struktur Organisasi Pemerintahan <?= ucwords($this->setting->sebutan_desa.' '.$desa['nama_desa'])?>'
	},

	accessibility: {
		point: {
			descriptionFormatter: function (point) {
				var nodeName = point.toNode.name,
					nodeId = point.toNode.id,
					nodeDesc = nodeName === nodeId ? nodeName : nodeName + ', ' + nodeId,
					parentDesc = point.fromNode.id;
				return point.index + '. ' + nodeDesc + ', reports to ' + parentDesc + '.';
			}
		}
	},

	series: [{
		type: 'organization',
		name: "<?= ucwords($this->setting->sebutan_desa.' '.$desa['nama_desa'])?>",
		keys: ['from', 'to'],
		data: [
			<?php if ($ada_bpd): ?>
				['BPD','LPM'],
			<?php endif; ?>
			<?php foreach ($bagan['struktur'] as $struktur): ?>
				[<?= key($struktur) ?>,<?= current($struktur)?>],
			<?php endforeach;?>
		],
		levels: [{
			level: 0,
			color: 'gold',
			dataLabels: {
				color: 'black'
			},
			height: 25
		}, {
			level: 1,
			color: 'MediumTurquoise',
			dataLabels: {
				color: 'white'
			},
			height: 25
		}, {
			level: 2,
			color: '#980104',
			dataLabels: {
				color: 'white'
			},
			height: 25
		}, {
			level: 4,
			color: '#359154',
			dataLabels: {
				color: 'white'
			},
			height: 25
		}],

		linkColor: "#ccc",
		linkLineWidth: 2,
		linkRadius: 0,

		nodes: [
			<?php if ($ada_bpd): ?>
				{
				  id: 'BPD',
				  color: 'gold',
				  column: 0,
				  offset: '-150'
				},
				{
				  id: 'LPM',
				  color: 'gold',
				  column: 0,
				  dataLabels: {
					  color: 'black'
				  },
				  offset: '150'
				},
			<?php endif; ?>
			<?php foreach ($bagan['nodes'] as $pamong) : ?>
				{
					id: <?= $pamong['pamong_id']?>,
					title: '<?= $pamong['jabatan']?>',
					name: "<?= $pamong['nama']?>",
					<?php if (! empty($pamong['foto'])): ?>
						image: '<?= base_url().LOKASI_USER_PICT.'kecil_'.$pamong['foto']?>',
					<?php endif; ?>
					<?php if (! empty($pamong['bagan_tingkat'])): ?>
						column: <?= $pamong['bagan_tingkat'] ?: ''?>,
					<?php endif; ?>
					<?php if (! empty($pamong['bagan_offset'])): ?>
						offset: '<?= $pamong['bagan_offset'] ?: ''?>%',
					<?php endif; ?>
					<?php if (! empty($pamong['bagan_layout'])): ?>
						layout: '<?= $pamong['bagan_layout'] ?: ''?>',
					<?php endif; ?>
					<?php if (! empty($pamong['bagan_warna'])): ?>
						color: '<?= $pamong['bagan_warna'] ?: ''?>',
					<?php endif; ?>
				},
			<?php endforeach; ?>
		],
		colorByPoint: false,
		color: '#007ad0',
		dataLabels: {
			color: 'white'
		},
		shadow: {
		  color: '#ccc',
		  width: 10,
		  offsetX: 0,
		  offsetY: 0
		},
		borderColor: 'white',
		nodeWidth: 75
	}],
	tooltip: {
		outside: true
	},
	exporting: {
		allowHTML: true,
		sourceWidth: 800,
		sourceHeight: 600
	}

});

</script>
