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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 py-1" id="pemerintah-list">
    </div>

    @include('theme::commons.pagination')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function loadPemerintah(params = {}) {
                var apiPemerintah = '{{ route('api.pemerintah') }}';

                $('#pagination-container').hide();
                $('#pemerintah-list').html('<div class="col-span-full text-center py-10"><i class="fas fa-spinner fa-spin text-3xl text-green-600"></i><p class="mt-3 text-gray-500">Memuat data pemerintah...</p></div>');

                $.get(apiPemerintah, params, function(data) {
                    var pemerintah = data.data;
                    var pemerintahList = $('#pemerintah-list');
                    pemerintahList.empty();

                    if (!pemerintah.length) {
                        pemerintahList.html(`<div class="col-span-full text-center py-10 text-gray-500"><i class="fas fa-info-circle text-2xl mb-2 block"></i> ${setting.sebutan_pemerintah_desa} tidak tersedia.</div>`);
                        return;
                    }

                    var mediaSosialPlatforms = JSON.parse(setting.media_sosial_pemerintah_desa);

                    pemerintah.forEach(function(item) {
                        var mediaSosial = '';
                        var mediaSosialPengurus = item.attributes.media_sosial || {};

                        mediaSosialPlatforms.forEach((platform) => {
                            var link = mediaSosialPengurus[platform];
                            if (link) {
                                mediaSosial += `
                                <a href="${link}" target="_blank" class="inline-flex items-center justify-center text-gray-400 hover:text-green-600 transition-colors duration-200 h-8 w-8">
                                    <i class="fab fa-lg fa-${platform}"></i>
                                </a>
                                `;
                            }
                        });

                        var pemerintahHTML = `
                        <div class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                            <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                                <img
                                    class="absolute top-0 left-0 w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105"
                                    src="${item.attributes.foto || '{{ theme_asset('assets/images/pengguna/kuser.png') }}'}"
                                    alt="Foto ${item.attributes.nama}"
                                    loading="lazy"
                                    onerror="this.src='{{ theme_asset('assets/images/pengguna/kuser.png') }}'"
                                />
                            </div>
                            <div class="p-5 flex flex-col flex-grow items-center text-center">
                                <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-2 leading-tight">${item.attributes.nama}</h3>
                                <p class="text-sm text-green-600 font-medium mb-3 uppercase tracking-wide line-clamp-2">${item.attributes.nama_jabatan}</p>
                                
                                ${item.attributes.pamong_niap ? 
                                    `<div class="text-xs text-gray-500 bg-gray-50 px-3 py-1 rounded-full mb-3 border border-gray-100 truncate max-w-full">
                                        ${item.attributes.sebutan_nip_desa}: ${item.attributes.pamong_niap}
                                    </div>` : ''
                                }

                                ${item.attributes.kehadiran == 1 ? 
                                    `<div class="mb-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                            item.attributes.status_kehadiran === 'hadir' 
                                            ? 'bg-green-100 text-green-800 border border-green-200' 
                                            : 'bg-red-100 text-red-800 border border-red-200'
                                        }">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${
                                                item.attributes.status_kehadiran === 'hadir' ? 'bg-green-600' : 'bg-red-600'
                                            }"></span>
                                            ${item.attributes.status_kehadiran === 'hadir' ? 'Hadir' : item.attributes.status_kehadiran}
                                        </span>
                                    </div>` : ''
                                }

                                <div class="mt-auto pt-4 border-t border-gray-100 w-full flex justify-center gap-1">
                                    ${mediaSosial}
                                </div>
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
