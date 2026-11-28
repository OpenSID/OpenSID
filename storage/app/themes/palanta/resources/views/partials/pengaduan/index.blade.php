@extends('theme::layouts.full-content')
@section('content')
<div class="heading-module l-flex">
	<div class="heading-module-inner l-flex">
		<i class="fa fa-bullhorn"></i><h1>Pengaduan</h1>
	</div>
</div>
<div class="lapak box-def">
	<div class="box-def-inner pengaduan">		
			<div class="row">
				<div class="col-md-3">
					<select class="form-control select2" id="caristatus" name="caristatus">
						<option value="">Semua Status</option>
						<option value="1" >Menunggu Diproses</option>
						<option value="2" >Sedang Diproses</option>
						<option value="3" >Selesai Diproses</option>
					</select>
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<input type="text" name="cari-pengaduan" value="" placeholder="Cari pengaduan disini..." class="form-control">						
						<span class="input-group-btn">
							<button id="btn-search" type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</div>
				<div class="col-md-3">
					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#newpengaduan">Formulir Pengaduan</button>
				</div>
			</div>
		
		<!-- Notifikasi -->
		@include('theme::commons.notifikasi')
		<div id="pengaduan-list"></div>
        @include('theme::commons.pagination')			
	</div>
</div>
<!-- BEGIN DETAIL TICKET -->
<div class="modal fade" tabindex="-1" role="dialog" id="pengaduan-detail"
	aria-labelledby="pengaduan-detail" aria-hidden="true">
	<div class="modal-wrapper">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fa fa-bullhorn"></i> Detail Pengaduan</h4>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END DETAIL TICKET -->					
<div class="modal fade" id="newpengaduan" tabindex="-1" role="dialog" aria-labelledby="newpengaduan" aria-hidden="true">
	<div class="modal-wrapper">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fa fa-pencil"></i> Buat Pengaduan Baru</h4>
				</div>
				<form action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<!-- Notifikasi -->			
						@include('theme::commons.notifikasi')
						@php $data = 	session('data', []) @endphp
						<div class="form-group">
							<input name="nik" type="text" maxlength="16" class="form-control" placeholder="NIK" value="{{ $data['nik'] }}">
						</div>
						<div class="form-group">
							<input name="nama" type="text" class="form-control" placeholder="Nama*" value="{{ $data['nama'] }}" required>
						</div>
						<div class="form-group">
							<input name="email" type="email" class="form-control" placeholder="Email" value="{{ $data['email'] }}">
						</div>
						<div class="form-group">
							<input name="telepon" type="text" class="form-control" placeholder="Telepon" value="{{ $data['telepon'] }}">
						</div>
						<div class="form-group">
							<input name="judul" type="text" class="form-control" placeholder="Judul*" value="{{ $data['judul'] }}" required>
						</div>
						<div class="form-group">
							<textarea name="isi" class="form-control" placeholder="Isi Pengaduan*" rows="5" required>{{ $data['isi'] }}</textarea>
						</div>
						<div class="form-group">
							<div class="input-group">
								<input type="text" accept="image/*" onchange="readURL(this);" class="form-control" id="file_path" placeholder="Unggah Foto" name="foto" value="{{ $data['foto'] }}">
								<input type="file" accept="image/*" onchange="readURL(this);" class="hidden" id="file" name="foto" value="{{ $data['foto'] }}">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info" id="file_browser" style="padding:5px 10px;"><i class="fa fa-search"></i></button>
								</span>
							</div>
							<small>Gambar: png,jpg,jpeg</small><br>
							<br><img id="blah" src="#" alt="gambar" class="img-responsive hidden" />
						</div>
						<div class="form-group">
							<table>
								<tr class="captcha">
									<td>&nbsp;</td>
									<td>
										<img id="captcha" src="{{ ci_route('captcha') }}" alt="CAPTCHA Image" class="max-w-full h-auto">
										<button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();">[Ganti Gambar]</button>
									</td>
									<td>&nbsp;&nbsp;&nbsp;</td>
									<td>
										<input type="text" name="captcha_code" class="form-control" maxlength="6" placeholder="Isikan jawaban" required />
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<a href="{{ ci_route('pengaduan') }} " class="btn btn-danger pull-left"><i class="fa fa-times"></i> Tutup</a>
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Kirim</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
	$('#file_browser').click(function(e)
	{
		e.preventDefault();
		$('#file').click();
	});
	$('#file').change(function()
	{
		$('#file_path').val($(this).val());
		if ($(this).val() == '')
		{
		$('#'+$(this).data('submit')).attr('disabled','disabled');
		}
		else
		{
		$('#'+$(this).data('submit')).removeAttr('disabled');
		}
	});
	$('#file_path').click(function()
	{
		$('#file_browser').click();
	});
	$(document).ready(function() {
		const pageSize = 10
		let pageNumber = 1
		let status = ''
		let cari = $('input[name=cari-pengaduan]').val()
		window.setTimeout(function() {
			$("#notifikasi").fadeTo(500, 0).slideUp(500, function() {
				$(this).remove();
			});
		}, 2000);
		
		var data = {{ count(session('data') ?? [])  }};
		if (data) {
			$('#newpengaduan').modal('show');
		}

		$('#btn-search').click(function(){
			pageNumber = 1
			cari = $('input[name=cari-pengaduan]').val()
			status = $('#caristatus').val()
			loadPengaduan(pageNumber)
		})

		const loadPengaduan = function (pageNumber) {
			let _filter = []
			if(status){
				_filter.push('filter[status]='+status)  
			}
			if(cari){
				_filter.push('filter[search]='+cari)				
			}
			let _filterString = _filter.length ? _filter.join('&') : '' 			
			$.ajax({
				url: `{{ ci_route('internal_api.pengaduan') }}?sort=-created_at&page[number]=${pageNumber}&page[size]=${pageSize}&${_filterString}`,
				type: "GET",
				beforeSend: function(){
					const pengaduanList = document.getElementById('pengaduan-list');
					pengaduanList.innerHTML = `@include('theme::commons.loading')`;
				},
				dataType: 'json',
				data: {
					
				},
				success: function (data) {
					displayPengaduan(data);
					initPagination(data);
				}
			});
		}

		const displayPengaduan = function (dataPengaduan) {
			const pengaduanList = document.getElementById('pengaduan-list');
			pengaduanList.innerHTML = '';
			if(!dataPengaduan.data.length) {
				pengaduanList.innerHTML = `<div class="box-def hoverstyle">
					<div class="emptydata c-flex">
						<div>
						<svg viewBox="0 0 24 24"><path d="M13 13H11V7H13M11 15H13V17H11M15.73 3H8.27L3 8.27V15.73L8.27 21H15.73L21 15.73V8.27L15.73 3Z" /></svg>
						<p>Mohon maaf, untuk saat ini data belum tersedia...!</p>
						</div>
					</div>
				</div>`
				return
			}
			const ulBlock = document.createElement('div');
			ulBlock.className = ``;
			dataPengaduan.data.forEach(item => {
				const card = document.createElement('div');				
				const labelComment = `<span class="label label-`+ (item.attributes.child_count ? 'success' : 'danger')+ ` pull-right"><i class="fa fa-comments"></i> ${item.attributes.child_count} Tanggapan</span>`
				const isi = `<span class="italic">${item.attributes.isi.substring(0,50)}`+ (item.attributes.isi.length > 50 ? `... <label class="underline">selengkapnya ></label>`: '') +`</span>`
				let labelStatus;
				switch(item.attributes.status){
					case 1:
						labelStatus = `<span class="label label-danger">Menunggu Diproses</span>`
						break;
					case 2:
						labelStatus = `<span class="label label-info">Sedang Diproses</span>`
						break;
					case 3:
						labelStatus = `<span class="label label-success">Selesai Diproses</span>`
						break;
				}				
				card.innerHTML = `															
						<div class="comment-row" style="cursor:pointer">
							<div class="comment-icon c-flex">
								<i class="fa fa-bullhorn"></i>
							</div>
							<div class="comment-title">
								<h3>${item.attributes.nama}</h3>
								<p>${item.attributes.created_at}</p>
								<p style="margin-top:10px;font-size:100%;"><b>${item.attributes.judul}</b> | ${labelStatus}
								<p style="margin-top:10px;font-size:100%;">
								${isi}
								${labelComment}	
								</p>
							</div>
						</div>					
				`;
				card.className = `status${item.attributes.status} allstatus`
				card.onclick = function(){
					let _comments = []
					const image  = item.attributes.foto ? `<img style="width:100%;height:auto;display:block;margin:0 0 10px;" src="${item.attributes.foto}">` : ``
					if(item.attributes.child_count){
						item.attributes.child.forEach(comment => {
							_comments.push(`
									<div class="comment-row">
										<div class="comment-icon c-flex">
											<i class="fa fa-comments"></i>
										</div>
										<div class="comment-title">
											<h3 style="font-weight:500;">Ditanggapi oleh : ${comment.nama}</h3>
											<p>${comment.created_at}</p>
											<p style="margin-top:10px;font-size:100%;">${comment.isi}</p>
										</div>
									</div>
							`)		
						});
					}
					const htmlBody = `
						<div class="row">
											<div class="col-md-12">
												<h2>${item.attributes.judul}</h2>
												<table width="100%" style="margin-top:10px;">
													<tr>
														<td style="width:35%;">Dilaporkan Oleh</td><td style="width:30px;text-align:center;">:</td><td>${item.attributes.nama}</td>
													</tr>
													<tr>
														<td style="width:35%;">Tanggal</td><td style="width:30px;text-align:center;">:</td><td>${item.attributes.created_at}</td>
													</tr>
												</table>
												<p style="margin:10px 0 5px;"><b>Isi Pengaduan :</b></p>
												<p style="margin:0 0 10px;">${item.attributes.isi}</p>
												${image}																			
												${_comments.join('')}`;
										
					$('#pengaduan-detail').modal('show')
					$('#pengaduan-judul').text(item.attributes.judul)
					$('#pengaduan-detail .modal-body').html(htmlBody)					
				}				
				pengaduanList.appendChild(card);
			});
		}		
		$('.pagination').on('click', '.btn-page', function() {
            var params = {};
            var page = $(this).data('page');            

            loadPengaduan(page);
        });
		loadPengaduan(pageNumber);
	});

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#blah').removeClass('hidden');
				$('#blah').attr('src', e.target.result).width(150).height(auto);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>
@endpush