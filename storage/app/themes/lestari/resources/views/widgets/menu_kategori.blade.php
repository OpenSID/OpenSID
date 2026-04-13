@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="widget-column">
	<div class="box-shadow brd-10">
	<div class="widget-padding">
		<div class="head-module">
			<h1>{{ $judul_widget }}</h1>
		</div>
		<div class="colscroll">
			<div class="menu-category">
				@foreach($menu_kiri as $data)
						<li>
							<a href="{{ci_route('artikel/kategori/' . $data['slug']) }}">
								{{ $data['kategori'] }}
							</a>
							@if(count($data['submenu'] ?? []) > 0)
								<ul class="sub-category">
									@foreach($data['submenu'] as $submenu)
										<li><a href="{{ci_route('artikel/kategori/' . $submenu['slug']) }}">{{ $submenu['kategori'] }}</a></li>
									@endforeach
								</ul>
							@endif
						</li>
				@endforeach
			</div>
		</div>
	</div>
	</div>
</div>