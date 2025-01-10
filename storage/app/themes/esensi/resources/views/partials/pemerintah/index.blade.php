@extends('theme::layouts.full-content')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ site_url() }}">Beranda</a></li>
            <li aria-current="page">
                {{ ucwords(setting('sebutan_pemerintah_desa')) }}
            </li>
        </ol>
    </nav>
    <h1 class="text-h2">
        {{ ucwords(setting('sebutan_pemerintah_desa')) }}
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-1" id="pemerintah-list">
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
                            <a href="${link}" target="_blank" class="inline-flex items-center justify-center bg-blue-600 h-8 w-8 rounded-full">
                                <i class="fab fa-lg fa-${platform}" style="color: #fff;"></i>
                            </a>
                        `;
                        });

                        var pemerintahHTML = `
                            <div class="space-y-3">
                                <img
                                    class="h-44 w-full object-cover object-center bg-gray-300"
                                    src="${item.attributes.foto || ''}"
                                    alt="Foto ${item.attributes.nama}"
                                />
                                <div class="space-y-1 text-sm text-center">
                                    <span class="text-h6">${item.attributes.nama}</span>
                                    <span class="block">${item.attributes.nama_jabatan}</span>
                                    ${
                                        item.attributes.pamong_niap
                                            ? `<span class="block">${item.attributes.sebutan_nip_desa}: ${item.attributes.pamong_niap}</span>`
                                            : ''
                                    }
                                    ${
                                        item.attributes.kehadiran == 1
                                            ? `<span class="btn btn-${item.attributes.status_kehadiran === 'hadir' ? 'primary' : 'danger'} w-auto mx-auto inline-block">${item.attributes.status_kehadiran === 'hadir' ? 'Hadir' : item.attributes.status_kehadiran}</span>`
                                            : ''
                                    }
                                    <div>${mediaSosial}</div>
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
