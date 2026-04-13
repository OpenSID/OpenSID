@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="widget-column">
	<div class="box-shadow brd-10">
	<div class="widget-padding">
		<div class="head-module flex-center">
			<h1>{{ $judul_widget }}</h1>
		</div>
		<div class="colscroll">
		<div class="sinergi_program">	
				<table style="width:100%;">
					@php
					$sinergi_program = sinergi_program();
					$perbaris = (int) (setting('gambar_sinergi_program_perbaris') ?: 3);

					// Calculate the total number of iterations needed
					$totalIterations = count($sinergi_program) + ($perbaris - count($sinergi_program) % $perbaris) % $perbaris;
				@endphp

				@for ($key = 0; $key < $totalIterations; $key++)
					@if ($key % $perbaris === 0)
						<tr>
					@endif

					@if ($key < count($sinergi_program))
							<td>
								<center>
								<a href="{{ $sinergi_program[$key]['tautan'] }}" target="_blank">
									<img src="{{ $sinergi_program[$key]['gambar_url'] }}" alt="Gambar {{ $sinergi_program[$key]['judul'] }}">
									<p>{{ $sinergi_program[$key]['judul'] }}</p>
								</a>
								</center>
							</td>
							@endif

					@if ($key % $perbaris === $perbaris - 1 || $key === $totalIterations - 1)
						</tr>
					@endif
				@endfor
				</table>
		</div>
		</div>
	</div>
	</div>
</div>