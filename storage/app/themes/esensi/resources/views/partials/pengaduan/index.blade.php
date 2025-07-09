@extends('theme::layouts.full-content')

@push('styles')
    <style type="text/css">
        .label {
            border-radius: 4px;
            padding: 2px 8px;
            color: white;
        }

        .label-danger {
            background-color: #dc2626;
        }

        .label-info {
            background-color: #0891b2;
        }

        .label-success {
            background-color: #059669;
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
        }

        .italic {
            font-style: italic;
        }

        .items-end {
            align-items: flex-end;
        }
    </style>
@endpush

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ ci_route() }}">Beranda</a></li>
            <li aria-current="page">Pengaduan</li>
        </ol>
    </nav>
    <h1 class="text-h2">Pengaduan</h1>
    <div>
        <div class="flex gap-3 lg:w-7/12 flex-col lg:flex-row py-5">
            <button type="button" class="btn btn-primary flex-shrink-0" data-bs-toggle="modal" data-bs-target="#newpengaduan"><i class="fas fa-pencil-alt mr-1"></i> Buat Pengaduan</button>
            <select class="form-input inline-block select2" id="caristatus" name="caristatus">
                <option value="">Semua Status</option>
                <option value="1">Menunggu Diproses</option>
                <option value="2">Sedang Diproses</option>
                <option value="3">Selesai Diproses</option>
            </select>
            <input type="text" name="cari-pengaduan" value="" placeholder="Cari pengaduan disini..." class="form-input inline-block">
            <button id="btn-search" type="button" class="btn btn-secondary"><i class="fa fa-search"></i></button>
        </div>

        <!-- Notifikasi -->
        @include('theme::commons.notifikasi')
        <div id="pengaduan-list"></div>
        @include('theme::commons.pagination')
    </div>
    </div>

    <!-- BEGIN DETAIL TICKET -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="pengaduan-detail" tabindex="-1" role="dialog" aria-labelledby="pengaduan-detail" aria-hidden="true">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h4 class="text-h6 text-primary-200"><i class="fa fa-folder-open mr-1"></i> <span id="pengaduan-judul"></span></h4>
                </div>
                <div class="modal-body relative py-2 px-3 lg:px-5 text-sm lg:text-base">

                </div>
                <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <button type="button" class="btn bg-red-500 hover:bg-red-500 text-white" data-bs-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END DETAIL TICKET -->
    <!-- Formulir Pengaduan -->
    <div
        class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        tabindex="-1"
        id="newpengaduan"
        tabindex="-1"
        role="dialog"
        aria-labelledby="newpengaduan"
        aria-hidden="true"
    >
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h4 class="text-h6 text-primary-200"><i class="fas fa-pencil-alt mr-1"></i> Buat Pengaduan Baru</h4>
                </div>
                <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body relative px-3 py-2 lg:px-5">
                        <!-- Notifikasi -->
                        @include('theme::commons.notifikasi')
                        @php $data = 	session('data', []) @endphp
                        <div class="py-2">
                            <input name="nik" type="text" maxlength="16" class="form-input" placeholder="NIK" value="{{ $data['nik'] }}">
                        </div>
                        <div class="py-2">
                            <input name="nama" type="text" required="" class="form-input" placeholder="Nama*" value="{{ $data['nama'] }}">
                        </div>
                        <div class="py-2">
                            <input name="email" type="email" class="form-input" placeholder="Email" value="{{ $data['email'] }}">
                        </div>
                        <div class="py-2">
                            <input name="telepon" type="text" class="form-input" placeholder="Telepon" value="{{ $data['telepon'] }}">
                        </div>
                        <div class="py-2">
                            <input name="judul" type="text" class="form-input" required="" placeholder="Judul*" value="{{ $data['judul'] }}">
                        </div>
                        <div class="py-2">
                            <textarea name="isi" required="" class="form-textarea" placeholder="Isi Pengaduan*" rows="4">{{ $data['isi'] }}</textarea>
                        </div>
                        <div class="py-2">
                            <div class="relative">
                                <input
                                    type="text"
                                    accept="image/*"
                                    onchange="readURL(this);"
                                    class="form-input"
                                    id="file_path"
                                    placeholder="Unggah Foto"
                                    name="foto"
                                >
                                <input type="file" accept="image/*" onchange="readURL(this);" class="hidden" id="file" name="foto">
                                <span class="absolute top-1/2 right-0 transform -translate-y-1/2">
                                    <button type="button" class="btn btn-info button-flat" id="file_browser"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                            <small>Gambar: png,jpg,jpeg</small><br>
                            <br><img id="blah" src="#" alt="gambar pendukung tampil di sini" class="max-w-full w-full hidden" />
                        </div>
                        <div class="flex gap-3">
                            <div class="w-full lg:w-1/3 overflow-hidden">
                                <img id="captcha" src="{{ ci_route('captcha') }}" alt="CAPTCHA Image" class="w-full lg:w-11/12">
                                <button type="button" class="btn bg-transparent text-xs" onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();">[Ganti Gambar]</button>
                            </div>
                            <div class="w-full lg:w-2/3">
                                <input
                                    type="text"
                                    class="form-input required"
                                    name="captcha_code"
                                    maxlength="6"
                                    value="{{ $notif['data']['captcha_code'] }}"
                                    placeholder="Isikan jawaban"
                                    required
                                >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-between p-4 border-t border-gray-200 rounded-b-md">
                        <a href="{{ ci_route('pengaduan') }}" class="btn bg-red-500 hover:bg-red-500 text-white pull-left"><i class="fa fa-times"></i> Tutup</a>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fas fa-paper-plane"></i> Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/pagination.js') }}"></script>
    <script type="text/javascript">
        $('#file_browser').click(function(e) {
            e.preventDefault();
            $('#file').click();
        });
        $('#file').change(function() {
            $('#file_path').val($(this).val());
            if ($(this).val() == '') {
                $('#' + $(this).data('submit')).attr('disabled', 'disabled');
            } else {
                $('#' + $(this).data('submit')).removeAttr('disabled');
            }
        });
        $('#file_path').click(function() {
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
                        pengaduanList.innerHTML = `@include('theme::commons.loading')`;
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
                    pengaduanList.innerHTML = `<div class="alert alert-info text-primary-100" role="alert">Data tidak tersedia</div>`
                    return
                }
                const ulBlock = document.createElement('div');
                ulBlock.className = 'grid grid-cols-1 lg:grid-cols-2 gap-5';
                dataPengaduan.data.forEach(item => {
                    const card = document.createElement('div');
                    const labelComment = `<span class="label label-` + (item.attributes.child_count ? 'success' : 'danger') + ` pull-right text-xs flex-shrink-0"><i class="fa fa-comments"></i> ${item.attributes.child_count} Tanggapan</span>`
                    const isi = `<span class="italic">${item.attributes.isi.substring(0,50)}` + (item.attributes.isi.length > 50 ? `... <label class="underline">selengkapnya ></label>` : '') + `</span>`
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
                    card.className = `card p-5 border cursor-pointer`;
                    card.innerHTML = `										
					<dl>
						<dt class="font-bold lg:text-xl">${item.attributes.judul}</dt>
						<ul class="inline-flex flex-wrap gap-2 w-full items-center text-xs">
							<li class="inline-flex items-center"><i class="fas fa-calendar-alt text-secondary-100 mr-2"></i>
								${item.attributes.created_at}</li>
							<li class="inline-flex items-center"><i class="fas fa-user text-secondary-100 mr-2"></i>
								${item.attributes.nama}</li>
							<li>${labelStatus}</li>
						</ul>
						<dd class="pt-2 flex flex-col lg:flex-row items-end justify-between gap-3">
							${isi}
							${labelComment}
						</dd>
					</dl>
				`;
                    card.onclick = function() {
                        let _comments = []
                        const image = item.attributes.foto ? `<img class="w-auto max-w-full" src="${item.attributes.foto}">` : ``
                        if (item.attributes.child_count) {
                            item.attributes.child.forEach(comment => {

                                _comments.push(`<div class="alert alert-info text-green-600">
									<p class="text-xs lg:text-sm">Ditanggapi oleh ${comment.nama} | ${comment.created_at}</p>
									<p class="italic">${comment.isi}</p>
								</div>`)
                            });
                        }
                        const htmlBody = `
						<div class="w-full py-2 space-y-2">
							<p class="text-muted text-xs lg:text-sm">Pengaduan oleh ${item.attributes.nama} | ${item.attributes.created_at}</p>
							<p class="italic">${item.attributes.isi}</p>
							${image}													
						</div>
						${_comments.join('')}`;

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
