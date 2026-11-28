
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-comments"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox">
		<marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="3" direction="up" width="100%" height="180px" align="center" behavior="alternate">
			@foreach($komen As $data)
			<div class="comment-row">
				<div class="comment-icon c-flex">
					<i class="fa fa-comments"></i>
				</div>
				<div class="comment-title">
					<h3>{{ $data['owner'] }}</h3>
					<p>{{ tgl_indo2($data['tgl_upload'])}}</p>
					<p style="margin-top:5px;">{!! potong_teks($data['komentar'], 50) !!}... <a href="{{ ci_route('artikel/' . buat_slug($data)) }}">selengkapnya</a></p>
				</div>
			</div>	
			@endforeach
		</marquee>
	</div>
</div>
