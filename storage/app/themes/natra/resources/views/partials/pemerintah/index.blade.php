@extends('theme::layouts.full-content')

@push('styles')
    <style>
        .image-pemerintah {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            overflow: hidden;
            transition: all 500ms ease;
            padding: 5px;
        }

        .row-pemerintah {
            padding: 20px;
        }

        .card-pemerintah {
            background-color: darkgrey;
            padding: 5px;
            border-radius: 10px;
        }

        .line-pemerintah {
            margin: 5px 0;
            height: 1px;
        }

        .media-sosial {
            margin-top: 5px;
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="single_category wow fadeInDown">
        <h2>
            <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">
                {{ ucwords(setting('sebutan_pemerintah_desa')) }}
            </span>
        </h2>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <div class="row" id="pemerintah-list">
            </div>
        </div>
    </div>

    @include('theme::commons.pagination')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function loadPemerintah(params = {}) {
                var apiPemerintah = '{{ route('api.pemerintah') }}';

                $('#pagination-container').hide();
                $('#pemerintah-list').html('<p class="text-center">Memuat...</p>');

                $.get(apiPemerintah, params, function(data) {
                    var pemerintah = data.data;
                    var pemerintahList = $('#pemerintah-list');
                    pemerintahList.empty();

                    if (!pemerintah.length) {
                        pemerintahList.html(`<p class="py-2"> ${setting.sebutan_pemerintah_desa} tidak tersedia.</p>`);
                        return;
                    }

                    var mediaSosialPlatforms = JSON.parse(setting.media_sosial_pemerintah_desa);

                    pemerintah.forEach(function(item) {
                        var mediaSosial = '';
                        var mediaSosialPengurus = item.attributes.media_sosial || {};

                        mediaSosialPlatforms.forEach((platform) => {
                            var link = mediaSosialPengurus[platform];
                            mediaSosial += `
                            <a href="${link}" target="_blank" style="padding: 5px;">
                                <span style="color:#fff;"><i class="fa fa-${platform} fa-2x"></i></span>
                            </a>
                        `;
                        });

                        var pemerintahHTML = `
                            <div class="col-sm-3 row-pemerintah">
                                <div class="card-pemerintah text-center">
                                    <img
                                        width="auto"
                                        class="rounded-circle image-pemerintah"
                                        src="${item.attributes.foto || ''}"
                                        alt="Foto ${item.attributes.nama}"
                                    />
                                    <hr class="line-pemerintah">
                                    <b>
                                        ${item.attributes.nama}<br>
                                        ${item.attributes.nama_jabatan}<br>
                                        ${
                                            item.attributes.kehadiran == 1
                                                ? `<span class="label label-${item.attributes.status_kehadiran === 'hadir' ? 'primary' : 'danger'}">${item.attributes.status_kehadiran === 'hadir' ? 'Hadir' : item.attributes.status_kehadiran}</span>`
                                                : ''
                                        }
                                        <div class="text-center media-sosial">
                                            ${mediaSosial}
                                        </div>
                                    </b>
                                </div>
                            </div>
                        `;

                        pemerintahList.append(pemerintahHTML);
                    });

                    initPagination(data);
                });
            }

            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');

                params['page[number]'] = page;

                loadPemerintah(params);
            });

            loadPemerintah();
        });
    </script>
@endpush
