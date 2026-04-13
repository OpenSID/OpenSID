@if (!empty($komentar))
<div class="comment-article">
<div class="row">
<div class="col-sm-12">
	<div class="head-module"><h2>Komentar</h2></div>
	@foreach ($komentar as $data)
		<div class="comment-small mt-20 mb-20">
			<div class="comment-grid">
				<div class="comment-icon">
					<svg viewBox="0 0 24 24"><path d="M17,12V3A1,1 0 0,0 16,2H3A1,1 0 0,0 2,3V17L6,13H16A1,1 0 0,0 17,12M21,6H19V15H6V17A1,1 0 0,0 7,18H18L22,22V7A1,1 0 0,0 21,6Z" /></svg>
				</div>
				<div class="comment-title">
					<h2>{{ $data['pengguna']['nama'] }}</h2>
					<h3>{{ tgl_indo2($data['tgl_upload']) }}</h3>
				</div>
			</div>
			<p>{{ $data['komentar'] }}</p>
			@if (count($data['children']) > 0)
    	        @foreach ($data['children'] as $children)
    	        <div style="margin:0 0 0 15px;">
				 <div class="row mt-20">
				  <div class="col-lg-12">
    	            <svg viewBox="0 0 24 24" style="width:20px !important;opacity:0.6;float:left;"><path d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z" /></svg>
    	            <p><b>{{ $children['pengguna']['nama'] }}</b> ({{ $children['pengguna']['level'] }})</p>
    	            <p style="font-style:italic;">{{ tgl_indo2($children['tgl_upload']) }} | {{ $children['komentar'] }}</p>
    	         </div>      
				 </div>
				 </div>
    	        @endforeach
            @endif
		</div>
	@endforeach
</div>	
</div>	
</div>	
@endif

@if ($single_artikel['boleh_komentar'] == 1)
<div class="comment-article">
<div class="row">
<div class="col-sm-12">
	<div class="head-module"><h2>Kirim Komentar</h2></div>
	<div class="mt-20">
	@php
		$notif = session('notif');
		$label = ($notif['status'] == -1) ? 'label-danger' : 'label-info';
	@endphp
	@if ($notif)
		<div class="comment-notif difle-c" style="margin-bottom:10px;"><p>{{ $notif['pesan'] }}</p></div>
	@endif
	<div class="comment-form">
		<form class="contact_form" id="validasi" name="form" action="{{ci_route("add_comment.{$single_artikel['id']}") }}" method="POST" onSubmit="return validasi(this);"> 
			<input class="form-control" type="text" name="owner" maxlength="100" placeholder="Isikan Nama" value="{{ $notif['data']['owner'] }}" required>
			<input class="form-control" type="text" name="no_hp" maxlength="15" placeholder="Isikan Nomor Telp./HP" value="{{ $notif['data']['no_hp'] }}" required>
			<input class="form-control" type="text" name="email" maxlength="100" placeholder="Isikan Email" value="{{ $notif['data']['email'] }}" required>
			<textarea style="width:100%;font-size:95%;padding:8px;" class="textarea" name="komentar" placeholder="Buat Komentar" >{{ $notif['data']['komentar'] }}</textarea>
				
			<div class="row">
				<div class="col-lg-6" style="margin-top:10px;">
					<div class="flex-left">
						<div class="imagecaptha">
							<img id="captcha" src="{{ci_route('captcha') }}" alt="CAPTCHA Image" />
						</div>
						<div class="changecaptha flex-left" style="margin-left:10px;">
							<a class="flex-left" href="#" onclick="document.getElementById('captcha').src = '{{ci_route('captcha') }}?' + Math.random();" alt="CAPTCHA Image">
							<svg viewBox="0 0 24 24"><path d="M17.65,6.35C16.2,4.9 14.21,4 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20C15.73,20 18.84,17.45 19.73,14H17.65C16.83,16.33 14.61,18 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6C13.66,6 15.14,6.69 16.22,7.78L13,11H20V4L17.65,6.35Z" /></svg>Ganti</a>
						</div>
					</div>
				</div>	
				<div class="col-lg-6">
					<div class="send-comment flex-left">
					<input type="text" name="captcha_code" class="form-control difle-c" maxlength="6" placeholder="Masukkan Kode"/>
					<input class="btn btn-success" type="submit" value="Kirim" style="margin-left:5px;">
					</div>
				</div>			
			</div>			
		</form>
	</div>
	</div>
</div>	
</div>	
</div>	
@endif

@if ($single_artikel['boleh_komentar'] == 1)
<div class="comment-article">
<div class="row">
<div class="col-sm-12">
	<div class="head-module"><h2>Komentar Facebook</h2></div>
	<div class="fb-comments" data-href="{{ $single_artikel['url_slug'] }}" width="100%" data-numposts="5" ></div>
</div>	
</div>	
</div>	
@endif