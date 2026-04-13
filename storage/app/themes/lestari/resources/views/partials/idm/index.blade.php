@extends('theme::layouts.full-content')
@include('theme::commons.asset_highcharts')

@section('content')
    @include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center mb-20">
				<h1>Status IDM</h1>
			</div>
		</div>
		<div class="margin-page">
			<div class="head-module align-center mb-20">
				<h2>Status Indeks Desa Membangun {{ $tahun }}</h2>
			</div>
			<div class="box-body">
				<div id="status-error" style="display: none;">
					<div class="box-body">
						<div class="alert alert-danger">
							<p id="error-message"></p>
						</div>
					</div>
				</div>
			</div>
			<div class="box-body">
				<div class="idm-grid">
					<div class="idm-left">
						<div class="idm-grid">
							<div class="column2 box-shadow brd-10 bgblue-sky mt-20">
								<div class="idm-box">
									<svg viewBox="0 0 24 24"><path d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"/></svg>
									<h3 id="skor-saat-ini"></h3>
                                    <p>SKOR IDM SAAT INI</p>
								</div>
							</div>
							<div class="column2 box-shadow brd-10 bgorange mt-20">
								<div class="idm-box">
									<svg viewBox="0 0 24 24"><path d="M12 2C11.5 2 11 2.19 10.59 2.59L2.59 10.59C1.8 11.37 1.8 12.63 2.59 13.41L10.59 21.41C11.37 22.2 12.63 22.2 13.41 21.41L21.41 13.41C22.2 12.63 22.2 11.37 21.41 10.59L13.41 2.59C13 2.19 12.5 2 12 2M11 7H13V13H11V7M11 15H13V17H11V15Z"/></svg>
									<h3 id="status-saat-ini"></h3>
									<p>STATUS IDM</p>
								</div>
							</div>
							<div class="column2 box-shadow brd-10 bggreen mt-20">
								<div class="idm-box">
									<svg viewBox="0 0 24 24"><path d="M5,4V6H19V4H5M5,14H9V20H15V14H19L12,7L5,14Z"/></svg>
									<h3 id="skor-minimal"></h3>
									<p>SKOR IDM MINIMAL</p>
								</div>
							</div>
							<div class="column2 box-shadow brd-10 bgmagenta mt-20">
								<div class="idm-box">
									<svg viewBox="0 0 24 24"><path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,10.84 21.79,9.69 21.39,8.61L19.79,10.21C19.93,10.8 20,11.4 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4C12.6,4 13.2,4.07 13.79,4.21L15.4,2.6C14.31,2.21 13.16,2 12,2M19,2L15,6V7.5L12.45,10.05C12.3,10 12.15,10 12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12C14,11.85 14,11.7 13.95,11.55L16.5,9H18L22,5H19V2M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12H16A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8V6Z"/></svg>
									<h3 id="target-status"></h3>
									<p>TARGET STATUS</p>
								</div>
							</div>
						</div>
						<div class="table-responsive mt-20">
							<table class="">
							<tbody>
								<tr>
									<td>PROVINSI</td>
									<td style="text-align:center;width:15px;">:</td>
									<td id="nama-provinsi"></td>
								</tr>
								<tr>
									<td>KABUPATEN</td>
                                    <td style="text-align:center;width:15px;">:</td>
									<td id="nama-kabupaten"></td>
								</tr>
								<tr>
									<td>{{ strtoupper(setting('sebutan_kecamatan')) }}</td>
                                    <td style="text-align:center;width:15px;">:</td>
                                    <td id="nama-kecamatan"></td>
								</tr>
								<tr>
                                    <td>{{ strtoupper(setting('sebutan_desa')) }}</td>
                                    <td style="text-align:center;width:15px;">:</td>
									<td id="nama-desa"></td>
								</tr>
							</tbody>
							</table>
						</div>
					</div>
					<div class="idm-right">
						<figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>
					</div>
				</div>

			</div>
			<div class="row mt-20">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable table-striped table-hover" id="tabel-daftar">
                                <thead class="bg-gray color-palette">
                                    <tr>
                                        <th rowspan="2" class="padat">NO</th>
                                        <th rowspan="2">INDIKATOR IDM</th>
                                        <th rowspan="2">SKOR</th>
                                        <th rowspan="2">KETERANGAN</th>
                                        <th rowspan="2" nowrap>KEGIATAN YANG DAPAT DILAKUKAN</th>
                                        <th rowspan="2">+NILAI</th>
                                        <th colspan="6" class="text-center">YANG DAPAT MELAKSANAKAN KEGIATAN</th>
                                    </tr>
                                    <tr>
                                        <th>PUSAT</th>
                                        <th>PROVINSI</th>
                                        <th>KABUPATEN</th>
                                        <th>DESA</th>
                                        <th>CSR</th>
                                        <th>LAINNYA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var tahun = '{{ $tahun }}';
            var route = '{{ route('api.idm', $tahun) }}';

            $.get(route, function(data) {
                if (data['error_msg']) {
                    $('#status-error').show();
                    $('#status-idm').hide();
                    $('#error-message').text(data['error_msg']);
                    return;
                }

                $('#status-idm').show();
                $('#status-error').hide();

                var summaries = data['data'][0]['attributes']['SUMMARIES'];
                var row = data['data'][0]['attributes']['ROW'];
                var identitas = data['data'][0]['attributes']['IDENTITAS'][0];
                var iks = parseFloat(row[35].SKOR ?? 0);
                var ike = parseFloat(row[48].SKOR ?? 0);
                var ikl = parseFloat(row[52].SKOR ?? 0);
                console.log(row);


                // Skor
                $('#skor-saat-ini').text(parseFloat(summaries.SKOR_SAAT_INI).toFixed(4));
                $('#status-saat-ini').text(summaries.STATUS);
                $('#skor-minimal').text(parseFloat(summaries.SKOR_MINIMAL).toFixed(4));
                $('#target-status').text(summaries.TARGET_STATUS);

                // Highcharts
                loadHighcharts(tahun, iks, ike, ikl);

                // Identitas
                $('#nama-provinsi').text(identitas.nama_provinsi);
                $('#nama-kabupaten').text(identitas.nama_kab_kota);
                $('#nama-kecamatan').text(identitas.nama_kecamatan);
                $('#nama-desa').text(identitas.nama_desa);

                // Tabel
                row.forEach(item => {
                    var tr = `
					<tr class="${item.NO ?? ''}">
						<td class="text-center">${item.NO ?? ''}</td>
						<td style="min-width: 150px;">${item.INDIKATOR?? ''}</td>
						<td class="padat">${item.SKOR ?? ''}</td>
						<td style="min-width: 250px;">${item.KETERANGAN ?? ''}</td>
						<td>${item.KEGIATAN ?? ''}</td>
						<td class="padat">${item.NILAI?? ''}</td>
						<td>${item.PUSAT ?? ''}</td>
						<td>${item.PROV ?? ''}</td>
						<td>${item.KAB ?? ''}</td>
						<td>${item.DESA ?? ''}</td>
						<td>${item.CSR  ?? ''}</td>
						<td>${item.LAINNYA}</td>
					</tr>
					`;

                    $('#tabel-daftar tbody').append(tr);
                });
            }).fail(function(xhr, status, error) {
                $('#status-error').show();
                $('#status-idm').hide();
                $('#error-message').text('Data IDM tahun ' + tahun + ' tidak ditemukan.');
            });

            // Highcharts
            function loadHighcharts(tahun, iks, ike, ikl) {
                Highcharts.chart('container', {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45
                        }
                    },
                    title: {
                        text: 'Indeks Desa Membangun (IDM) ' + tahun
                    },
                    subtitle: {
                        text: 'SKOR : IKS, IKE, IKL'
                    },
                    plotOptions: {
                        series: {
                            colorByPoint: true
                        },
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            showInLegend: true,
                            depth: 45,
                            innerSize: 70,
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y:,.2f} / {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'SKOR',
                        shadow: 1,
                        border: 1,
                        data: [
                            ['IKS', parseFloat(iks)],
                            ['IKE', parseFloat(ike)],
                            ['IKL', parseFloat(ikl)]
                        ]
                    }]
                });
            }
        });
    </script>
@endpush
