@extends('theme::layouts.full-content')

@section('content')
	@include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center mb-20">
				<h1>Pengaduan</h1>
			</div>
		</div>
		<div class="margin-page">
			<div class="box-body">
				<div class="row">
				<div class="col-sm-12 pengaduan">
					<div class="pengaduan-filter">
						<div class="row">
							<div class="col-lg-4 col-sm-12">
								<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#newpengaduan">Formulir Pengaduan</button>
							</div>
							<div class="col-lg-4 col-sm-12">
								<select class="form-control select2" id="caristatus" name="caristatus">
									<option value="">Semua Status</option>
									<option value="1">Menunggu Diproses</option>
									<option value="2">Sedang Diproses</option>
									<option value="3">Selesai Diproses</option>
								</select>
							</div>
							<div class="col-lg-4 col-sm-12 flex-left">
								<input type="text" name="cari-pengaduan" value="{{ $cari }}" placeholder="Cari pengaduan disini..." class="form-control">
								<button type="button" id="btn-search" class="btn btn-info"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
					<div id="pengaduan-list" class="pengaduan-style"></div>
					<div class="flex-center mt-20">@include('theme::commons.pagination')</div>
				</div>
				</div>
			</div>
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>
	<div class="modal fade" id="pengaduan-detail" tabindex="-1" role="dialog" aria-labelledby="pengaduan-detail" aria-hidden="true">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content brd-10">
                    <div class="modal-header bg-blue" style="border-radius:10px 10px 0 0;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title"><i class="fa fa-file"></i> <span id="pengaduan-judul"></span></h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                            Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="newpengaduan" tabindex="-1" role="dialog" aria-labelledby="newpengaduan" aria-hidden="true">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content brd-10">
                    <div class="modal-header bg-blue" style="border-radius:10px 10px 0 0;">
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
                                    <input
                                        type="text"
                                        accept="image/*"
                                        onchange="readURL(this);"
                                        class="form-control"
                                        id="file_path"
                                        placeholder="Unggah Foto"
                                        name="foto"
                                        value="{{ $data['foto'] }}"
                                    >
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onchange="readURL(this);"
                                        class="hidden"
                                        id="file"
                                        name="foto"
                                        value="{{ $data['foto'] }}"
                                    >
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i></button>
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
                                            <a href="#" id="b-captcha" style="color: #000000;">
                                                <img id="captcha" src="{{ ci_route('captcha') }}" onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();" alt="CAPTCHA Image" />
                                            </a>
                                        </td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>
                                            <input type="text" name="captcha_code" class="form-control" maxlength="6" placeholder="Masukkan kode diatas" required />
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

            var data = {{ count(session('data') ?? []) }};
            if (data) {
                $('#newpengaduan').modal('show');
            }

            $('#btn-search').click(function() {
                pageNumber = 1
                cari = $('input[name=cari-pengaduan]').val()
                status = $('#caristatus').val()
                loadPengaduan(pageNumber)
            })

            const loadPengaduan = function(pageNumber) {
                let _filter = []
                if (status) {
                    _filter.push('filter[status]=' + status)
                }
                if (cari) {
                    _filter.push('filter[search]=' + cari)
                }
                let _filterString = _filter.length ? _filter.join('&') : ''
                $.ajax({
                    url: `{{ ci_route('internal_api.pengaduan') }}?sort=-created_at&page[number]=${pageNumber}&page[size]=${pageSize}&${_filterString}`,
                    type: "GET",
                    beforeSend: function() {
                        const pengaduanList = document.getElementById('pengaduan-list');
                        pengaduanList.innerHTML = `<div class="fa fa-circle-o-notch fa-spin fa-4x" role="status">
										<span class="sr-only">Loading...</span>
										</div>`;
                    },
                    dataType: 'json',
                    data: {

                    },
                    success: function(data) {
                        displayPengaduan(data);
                        initPagination(data);
                    }
                });
            }

            const displayPengaduan = function(dataPengaduan) {
                const pengaduanList = document.getElementById('pengaduan-list');
                pengaduanList.innerHTML = '';
                if (!dataPengaduan.data.length) {
                    pengaduanList.innerHTML = `<div class="alert alert-info" role="alert">Data tidak tersedia</div>`
                    return
                }
                const ulBlock = document.createElement('ul');
                ulBlock.className = 'list-group fa-padding';
                dataPengaduan.data.forEach(item => {
                    const card = document.createElement('li');
                    const labelComment = `<span class="label label-` + (item.attributes.child_count ? 'success' : 'danger') + ` "><i class="fa fa-comments"></i> ${item.attributes.child_count} Tanggapan</span>`
                    const isi = `<span>${item.attributes.isi.substring(0,50)}` + (item.attributes.isi.length > 50 ? `<label class="text-info">selengkapnya...</label>` : '') + `</span>`
                    const image = item.attributes.foto ? `<img class="img-thumbnail" src="${item.attributes.foto}" alt="Foto Pengaduan">` : ``
                    let labelStatus;
                    switch (item.attributes.status) {
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
                    card.className = `list-group-item status${item.attributes.status} allstatus`;
                    card.innerHTML = `
					<div class="pengaduan-row box-shadow brd-10 mt-20">
						<div class="pengaduan-inner">
						<div class="article-grid">
							<div class="pengaduan-left">
								<div class="pengaduan-user flex-left">
									<div class="pengaduan-icon flex-center">
										<svg viewBox="0 0 24 24"><path d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z" /></svg>
									</div>
									<div>
									<h3>${item.attributes.nama}</h3>
									<p>${item.attributes.created_at}</p>
									</div>
								</div>
								<h2>${item.attributes.judul}</h2>
							</div>
							<div class="pengaduan-right">
								<div class="article-grid">
									<div class="pengaduan-status flex-center">
									${labelStatus}
									</div>
									<div class="pengaduan-status flex-center">
									${labelComment}
									</div>
								</div>
							</div>
						</div>
						</div>
					</div>										
				`;
                    card.onclick = function() {
                        let _comments = []
                        const image = item.attributes.foto ? `<img class="img-thumbnail" src="${item.attributes.foto}" alt="Foto Pengaduan">` : ``
                        if (item.attributes.child_count) {
                            item.attributes.child.forEach(comment => {
                                _comments.push(`<div class="row support-content-comment">
								<div class="col-md-12">
									<div class="tanggapan"><b>Tanggapan</b></div>
									<div class="default-width mt-20">
									<div class="pengaduan-user flex-left">
										<div class="pengaduan-icon flex-center">
											<svg viewBox="0 0 24 24"><path d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z" /></svg>
										</div>
										<div>
										<h3><b>${comment.nama}</b></h3>
										<p>${comment.created_at}</p>
										</div>
									</div>
									<p style="margin:5px 0 0;">${comment.isi}</p>
									</div>
								</div>
							</div>`)
                            });
                        }
                        const htmlBody = `
					<div class="row">
						<div class="col-md-12">
							<table class="table-pengaduan">
								<tr>
									<td>Pengaduan</td><td style="width:15px;text-align:center;">:</td><td><b>${item.attributes.judul}</b></td>
								</tr>
								<tr>
									<td>Oleh</td><td style="width:15px;text-align:center;">:</td><td><b>${item.attributes.nama}</b></td>
								</tr>
								<tr>
									<td>Tanggal</td><td style="width:15px;text-align:center;">:</td><td>${item.attributes.created_at}</td>
								</tr>
								<tr>
									<td>Detail</td><td style="width:15px;text-align:center;">:</td><td>${item.attributes.isi}</td>
								</tr>
							</table>
							<div class="image-default mt-20 mb-20">
							${image}
							</div>
								
						</div>
					</div> ${_comments.join('')}`;

                        $('#pengaduan-detail').modal('show')
                        $('#pengaduan-judul').text(item.attributes.judul)

                        $('#pengaduan-detail .modal-body').html(htmlBody)
                    }
                    pengaduanList.appendChild(card);
                });
            }

            $('.pagination').on('click', '.btn-page', function() {
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
