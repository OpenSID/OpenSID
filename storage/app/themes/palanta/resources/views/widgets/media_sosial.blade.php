
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-share-alt"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox widget-social c-flex">
		@foreach ($sosmed as $data)
			@if (!empty($data["link"]))
				<a href="{{ $data['link'] }}" rel="noopener noreferrer" target="_blank">
					@php $icon = strtolower($data['nama']) . '.png' @endphp
					<img src="{{ theme_asset("img/sosial_media/{$icon}") }}" alt="{{ $data['nama'] }}"/>
					<img src="{{ $data['icon'] }}" alt="{{ $data['nama'] }}" style="width:50px;height:50px;" />
				</a>
			@endif
		@endforeach
	</div>
</div>