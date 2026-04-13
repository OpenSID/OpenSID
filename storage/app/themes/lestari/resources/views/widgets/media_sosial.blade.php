@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="widget-column">
	<div class="box-shadow brd-10">
	<div class="widget-padding">
		<div class="head-module flex-center">
			<h1>{{ $judul_widget }}</h1>
		</div>
		<div class="colscroll">
			<div class="sosmed flex-center mt-20">
			@foreach ($sosmed as $data)
				@if (!empty($data["link"]))
					<a href="{{ $data['link'] }}" rel="noopener noreferrer" target="_blank">
					<div class="sosmed mt-20">
						<img src="{{ $data['icon'] }}" alt="{{ $data['nama'] }}"/>
					</div>	
					</a>
				@endif
			@endforeach
			</div>	
		</div>
	</div>
	</div>
</div>