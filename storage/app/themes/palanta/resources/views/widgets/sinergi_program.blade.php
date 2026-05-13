<style>
#sinergi_program img {
    max-width: 90%;
}
</style>
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-link"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div id="sinergi_program" class="widgetbox">
		<table style="margin:0 auto;">
		@php
			$sinergi_program = sinergi_program();
			$perbaris        = (int) (setting('gambar_sinergi_program_perbaris') ?: 3);

			// Calculate the total number of iterations needed
			$totalIterations = count($sinergi_program) + ($perbaris - count($sinergi_program) % $perbaris) % $perbaris;
		@endphp
			@for($key = 0; $key < $totalIterations; $key++)				
				@if ($key % $perbaris === 0)
					<tr>
				@endif
				
				@if ($key < count($sinergi_program))
					<td>
						<center>
							<span style="display: inline-block;">
								<a href="{{ $sinergi_program[$key]['tautan'] }}" rel="noopener noreferrer" target="_blank"><img src="{{ $sinergi_program[$key]['gambar_url'] }}" style="float:left; margin:5px;" alt="{{ $sinergi_program[$key]['judul'] }}" /></a>
							</span>
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
