@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="widget-column">
	<div class="box-shadow brd-10">
	<div class="widget-padding">
		<div class="head-module">
			<h1>{{ $judul_widget }}</h1>
		</div>
		<div class="colscroll">
				@foreach ($komen as $data)
				<div class="comment-small mb-20">
					<a href="{{ site_url('artikel/' . buat_slug($data)) }}">
					<div class="comment-grid">
						<div class="comment-icon">
							<svg viewBox="0 0 24 24"><path d="M17,12V3A1,1 0 0,0 16,2H3A1,1 0 0,0 2,3V17L6,13H16A1,1 0 0,0 17,12M21,6H19V15H6V17A1,1 0 0,0 7,18H18L22,22V7A1,1 0 0,0 21,6Z" /></svg>
						</div>
						<div class="comment-title">
							<h2>{{ $data['pengguna']['nama'] }}</h2>
							<h3>{{ tgl_indo2($data['tgl_upload']) }}</h3>
						</div>
					</div>
					<p>{{ potong_teks($data['komentar'], 100) }}...</p>
					</a>
				</div>
			@endforeach
		</div>
	</div>
	</div>
</div>