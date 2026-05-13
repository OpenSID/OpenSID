
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-list"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox widget-cat">
		<ul id="ul-menu">
		@foreach($menu_kiri as $data)
			<li><a href="{{ ci_route('artikel.kategori.'.$data['slug']) }}">{{ $data['kategori'] }}
				@if (count($data['submenu'] ?? [])>0)
				<span class="caret"></span>
				@endif
			</a>
			@if(count($data['submenu'] ?? [])>0)
			<ul>
				@foreach($data['submenu'] as $submenu)
					<li><a href="{{ ci_route('artikel.kategori.'.$submenu['slug']) }}">{{ $submenu['kategori'] }}</a></li>
				@endforeach
			</ul>
			@endif
			</li>
		@endforeach
		</ul>
	</div>
</div>
