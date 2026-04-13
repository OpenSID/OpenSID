
<div class="section-module homemap">
	<div class="margin-page">
		<div class="apbd-grid mt-20">
			<div class="apbd-left flex-left">
				<img src="{{ theme_asset('images/apbd.png') }}"/>
			</div>
			<div class="apbd-right">
				<h1>APB {{ ucwords(setting('sebutan_desa')) }}</h1>
				<h2>Data APB {{ ucwords(setting('sebutan_desa')) }} sebagai wujud transparansi Pemerintah {{ ucwords(setting('sebutan_desa')) }} dalam mengelola keuangan</h2>
				<div class="row">
					@foreach ($data_widget as $subdata_name => $subdatas)
					<div class="col-sm-12 apbd-data">
						@foreach ($subdatas as $key => $subdata)
						@continue(!is_array($subdata))
						@if ($subdata['judul'] != null and $key != 'laporan' and $subdata['realisasi'] != 0 or $subdata['anggaran'] != 0)
							<div class="box-shadow brd-10" style="margin-top:15px;">
								<div class="apbd-inner">
									<h3>
									{{ \Illuminate\Support\Str::of($subdata['judul'])->title()->whenEndsWith('Desa', function (\Illuminate\Support\Stringable $string) {
                                if (!in_array($string, ['Dana Desa'])) {
                                    return $string->replace('Desa', setting('sebutan_desa'));
                                }
                            })->title() }}
									</h3>
									<div class="flex-right">
									<div class="abpd-item">
										<table class="tablesmall" style="width:100%;">
											<tr>
												<td>Anggaran</td><td style="width:15px;text-align:center;">:</td><td class="anggaran">{{ rupiah24($subdata['anggaran']) }}</td>
											</tr>
											<tr>
												<td>Realisasi</td><td style="width:15px;text-align:center;">:</td><td class="realisasi">{{ rupiah24($subdata['realisasi'], 'RP ') }}</td>
											</tr>
										</table>
									</div>
									</div>
								</div>
							</div>
						@endif
						@endforeach
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>