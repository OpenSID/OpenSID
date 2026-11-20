@extends('theme::layouts.full-content')

@section('content')
<div class="heading-module l-flex">
	<div class="heading-module-inner l-flex">
		<i class="fa fa-group"></i><h1>{{ ucwords(setting('sebutan_pemerintah_desa')) }}</h1>
	</div>
</div>

<div class="row-custom mlr-min10 pemerintah" id="pemerintah-list">
</div>

@include('theme::commons.pagination')
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        function loadPemerintah(params = {}) {
            var apiPemerintah = '{{ route("api.pemerintah") }}';

            $('#pagination-container').hide();
            $('#pemerintah-list').html('<p class="text-center">Memuat...</p>');

            $.get(apiPemerintah, params, function (data) {
                var pemerintah = data.data;
                var pemerintahList = $('#pemerintah-list');
                pemerintahList.empty();

                if (!pemerintah.length) {
                    pemerintahList.html(`<div class="box-def hoverstyle">
                            <div class="emptydata c-flex">
                            <div>
                                <svg viewBox="0 0 24 24"><path d="M13 13H11V7H13M11 15H13V17H11M15.73 3H8.27L3 8.27V15.73L8.27 21H15.73L21 15.73V8.27L15.73 3Z" /></svg>
                                <p>Mohon maaf, untuk saat ini data belum tersedia...!</p>
                            </div>
                            </div>
                        </div>`);
                    return;
                }

                var mediaSosialPlatforms = JSON.parse(setting.media_sosial_pemerintah_desa);

                pemerintah.forEach(function (item) {
                    var mediaSosial = '<div class="c-flex" style="margin:10px 0 0;width:100%;text-align:center;">';
                    var mediaSosialPengurus = item.attributes.media_sosial || {};

                    mediaSosialPlatforms.forEach((platform) => {
                        var link = mediaSosialPengurus[platform];
                        mediaSosial += `
                        <div style="padding: 0 3px;">
                            <a href="${link}" rel="noopener noreferrer" target="_blank">
                                <i class="fab fa-lg fa-${platform}" style="color: #fff;"></i>
                            </a>
                        </div>
                        `;
                    });
                    mediaSosial += '</div>';

                    var pemerintahHTML = `
                        <div class="column-4 box-def">
                            <div class="box-def-inner">
                                <img style="width:100%;height:auto;" src="${item.attributes.foto || ''}" alt="Foto ${item.attributes.nama}"/>
                                <div class="c-flex" style="margin:10px 0 0;width:100%;text-align:center;">
                                    <div>
                                        <h2>${item.attributes.nama}</h2>
                                        <p>${item.attributes.nama_jabatan}</p>
                                    </div>
                                </div>
                                <div class="c-flex" style="margin:10px 0 0;width:100%;text-align:center;">                                    
                                    <div class="btn btn-${item.attributes.status_kehadiran === 'hadir' ? 'primary' : 'danger'} btn-sm">${item.attributes.status_kehadiran === 'hadir' ? 'Hadir' : item.attributes.status_kehadiran}</div>                                    
                                </div>  
                                ${mediaSosial}                              
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