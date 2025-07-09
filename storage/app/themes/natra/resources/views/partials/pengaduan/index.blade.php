@extends('theme::layouts.full-content')

@push('styles')
    <style type="text/css">
        .padding {
            padding: 10px;
        }

        /* GRID */
        .col {
            padding: 10px 20px;
            margin-bottom: 10px;
            background: #fff;
            color: #666666;
            text-align: center;
            font-weight: 400;
            box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
        }

        .row h3 {
            color: #666666;
        }

        .row.grid {
            margin-left: 0;
        }

        .grid {
            position: relative;
            width: 100%;
            background: #fff;
            color: #666666;
            border-radius: 2px;
            margin-bottom: 25px;
            box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
        }

        .grid .grid-header {
            position: relative;
            border-bottom: 1px solid #ddd;
            padding: 15px 10px 10px 20px;
        }

        .grid .grid-header:before,
        .grid .grid-header:after {
            display: table;
            content: " ";
        }

        .grid .grid-header:after {
            clear: both;
        }

        .grid .grid-header span,
        .grid .grid-header>.fa {
            display: inline-block;
            margin: 0;
            font-weight: 300;
            font-size: 1.5em;
            float: left;
        }

        .grid .grid-header span {
            padding: 0 5px;
        }

        .grid .grid-header>.fa {
            padding: 5px 10px 0 0;
        }

        .grid .grid-header>.grid-tools {
            padding: 4px 10px;
        }

        .grid .grid-header>.grid-tools a {
            color: #999999;
            padding-left: 10px;
            cursor: pointer;
        }

        .grid .grid-header>.grid-tools a:hover {
            color: #666666;
        }

        .grid .grid-body {
            margin: 20px 0;
            font-size: 0.9em;
            line-height: 1.9em;
        }

        .grid .full {
            padding: 0 !important;
        }

        .grid .transparent {
            box-shadow: none !important;
            margin: 0px !important;
            border-radius: 0px !important;
        }

        .grid.top.black>.grid-header {
            border-top-color: #000000 !important;
        }

        .grid.bottom.black>.grid-body {
            border-bottom-color: #000000 !important;
        }

        .grid.top.blue>.grid-header {
            border-top-color: #007be9 !important;
        }

        .grid.bottom.blue>.grid-body {
            border-bottom-color: #007be9 !important;
        }

        .grid.top.green>.grid-header {
            border-top-color: #00c273 !important;
        }

        .grid.bottom.green>.grid-body {
            border-bottom-color: #00c273 !important;
        }

        .grid.top.purple>.grid-header {
            border-top-color: #a700d3 !important;
        }

        .grid.bottom.purple>.grid-body {
            border-bottom-color: #a700d3 !important;
        }

        .grid.top.red>.grid-header {
            border-top-color: #dc1200 !important;
        }

        .grid.bottom.red>.grid-body {
            border-bottom-color: #dc1200 !important;
        }

        .grid.top.orange>.grid-header {
            border-top-color: #f46100 !important;
        }

        .grid.bottom.orange>.grid-body {
            border-bottom-color: #f46100 !important;
        }

        .grid.no-border>.grid-header {
            border-bottom: 0px !important;
        }

        .grid.top>.grid-header {
            border-top-width: 4px !important;
            border-top-style: solid !important;
        }

        .grid.bottom>.grid-body {
            border-bottom-width: 4px !important;
            border-bottom-style: solid !important;
        }

        /* SUPPORT TICKET */
        .support ul {
            list-style: none;
            padding: 0px;
        }

        .support ul li {
            padding: 8px 10px;
        }

        .support ul li a {
            color: #999;
            display: block;
        }

        .support ul li a:hover {
            color: #666;
        }

        .support ul li.active {
            background: #0073b7;
        }

        .support ul li.active a {
            color: #fff;
        }

        .support ul.support-label li {
            padding: 2px 0px;
        }

        .support h2,
        .support-content h2 {
            margin-top: 5px;
        }

        .list-group li {
            padding: 15px 20px 12px 20px;
            cursor: pointer;
        }

        .list-group li:hover {
            background: #eee;
        }

        .support-content .fa-padding .fa {
            padding-top: 5px;
            width: 1.5em;
        }

        .support-content .info {
            color: #777;
            margin: 0px;
        }

        .support-content a {
            color: #111;
        }

        .support-content .info a:hover {
            text-decoration: underline;
        }

        .support-content .info .fa {
            width: 1.5em;
            text-align: center;
        }

        .support-content .number {
            color: #777;
        }

        .support-content img {
            margin: 0 auto;
            display: block;
        }

        .support-content .modal-body {
            padding-bottom: 0px;
        }

        .support-content-comment {
            padding: 10px 10px 10px 30px;
            background: #eee;
            border-top: 1px solid #ccc;
        }

        /* BACKGROUND COLORS */
        .bg-red,
        .bg-yellow,
        .bg-aqua,
        .bg-blue,
        .bg-light-blue,
        .bg-green,
        .bg-navy,
        .bg-teal,
        .bg-olive,
        .bg-lime,
        .bg-orange,
        .bg-fuchsia,
        .bg-purple,
        .bg-maroon,
        bg-gray,
        bg-black,
        .bg-red a,
        .bg-yellow a,
        .bg-aqua a,
        .bg-blue a,
        .bg-light-blue a,
        .bg-green a,
        .bg-navy a,
        .bg-teal a,
        .bg-olive a,
        .bg-lime a,
        .bg-orange a,
        .bg-fuchsia a,
        .bg-purple a,
        .bg-maroon a,
        bg-gray a,
        .bg-black a {
            color: #f9f9f9 !important;
        }

        .bg-white,
        .bg-white a {
            color: #999999 !important;
        }

        .bg-red {
            background-color: #f56954 !important;
        }

        .bg-yellow {
            background-color: #f39c12 !important;
        }

        .bg-aqua {
            background-color: #00c0ef !important;
        }

        .bg-blue {
            background-color: #0073b7 !important;
        }

        .bg-light-blue {
            background-color: #3c8dbc !important;
        }

        .bg-green {
            background-color: #00a65a !important;
        }

        .bg-navy {
            background-color: #001f3f !important;
        }

        .bg-teal {
            background-color: #39cccc !important;
        }

        .bg-olive {
            background-color: #3d9970 !important;
        }

        .bg-lime {
            background-color: #01ff70 !important;
        }

        .bg-orange {
            background-color: #ff851b !important;
        }

        .bg-fuchsia {
            background-color: #f012be !important;
        }

        .bg-purple {
            background-color: #932ab6 !important;
        }

        .bg-maroon {
            background-color: #85144b !important;
        }

        .bg-gray {
            background-color: #eaeaec !important;
        }

        .bg-black {
            background-color: #222222 !important;
        }
    </style>
@endpush

@section('content')
    <div class="single_category wow fadeInDown" style="margin-bottom: 20px;">
        <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Pengaduan</span></h2>
    </div>

    <div class="row">
        <div class="col-md-12">

            <table style="width: -webkit-fill-available">
                <tr>
                    <td style="padding-right: 5px"><button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#newpengaduan">Formulir Pengaduan</button></td>
                    <td style="width: 20%; padding-right: 5px">
                        <select class="form-control select2" id="caristatus" name="caristatus">
                            <option value="">Semua Status</option>
                            <option value="1">Menunggu Diproses</option>
                            <option value="2">Sedang Diproses</option>
                            <option value="3">Selesai Diproses</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="cari-pengaduan" value="{{ $cari }}" placeholder="Cari pengaduan disini..." class="form-control">
                            <span class="input-group-btn">
                                <button type="button" id="btn-search" class="btn btn-info"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>

            <br />

            <!-- Notifikasi -->
            @include('theme::commons.notifikasi')
            <div id="pengaduan-list"></div>
            @include('theme::commons.pagination')
        </div>
    </div>

    <!-- BEGIN DETAIL TICKET -->
    <div class="modal fade" id="pengaduan-detail" tabindex="-1" role="dialog" aria-labelledby="pengaduan-detail" aria-hidden="true">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
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
    <!-- END DETAIL TICKET -->
    <!-- Formulir Pengaduan -->
    <div class="modal fade" id="newpengaduan" tabindex="-1" role="dialog" aria-labelledby="newpengaduan" aria-hidden="true">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
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
                    const labelComment = `<span class="label label-` + (item.attributes.child_count ? 'success' : 'danger') + ` pull-right"><i class="fa fa-comments"></i> ${item.attributes.child_count} Tanggapan</span>`
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
					<div class="media">
						<div class="media-body" style="display: block;">
							<table>
								<tr>
									<td rowspan="2"><i class="fa fa-user pull-left" style="font-size: -webkit-xxx-large"></i></td>
									<td>
										<h4 style="margin-bottom: 0px">${item.attributes.nama}</h4>
									</td>
								</tr>
								<tr>
									<td class="text-muted">${item.attributes.created_at} | ${item.attributes.judul} | ${labelStatus}</td>
								</tr>
							</table><br>
							<p class="info">
								${isi}
								${labelComment}
							</p>
						</div>
						${image}
					</div>										
				`;
                    card.onclick = function() {
                        let _comments = []
                        const image = item.attributes.foto ? `<img class="img-thumbnail" src="${item.attributes.foto}" alt="Foto Pengaduan">` : ``
                        if (item.attributes.child_count) {
                            item.attributes.child.forEach(comment => {
                                _comments.push(`<div class="row support-content-comment">
								<div class="col-md-12">
									<p>Ditanggapi oleh ${comment.nama} | ${comment.created_at}</p>
									<p>${comment.isi}</p>
								</div>
							</div>`)
                            });
                        }
                        const htmlBody = `
					<div class="row">
						<div class="col-md-12">
							<p class="text-muted">Pengaduan oleh ${item.attributes.nama} | ${item.attributes.created_at}
							</p>
							<p>${item.attributes.isi}</p>							
							${image}
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
