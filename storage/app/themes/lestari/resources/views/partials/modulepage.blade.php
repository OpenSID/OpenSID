
@include('theme::partials.homemap')
@include('theme::widgets.aparatur_desa')
@include('theme::widgets.statistik')
@include('theme::widgets.galeri')
@if (!is_null($transparansi))
	@include('theme::partials.anggaran', $transparansi)
@endif
@include('theme::partials.mandiri')
@include('theme::partials.widgets')