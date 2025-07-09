@extends('theme::layouts.full-content')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ site_url('/') }}">Beranda</a></li>
            <li aria-current="page">SDGs {{ ucwords(setting('sebutan_desa')) }}</li>
        </ol>
    </nav>

    <h1 class="text-h2">SDGs {{ ucwords(setting('sebutan_desa')) }}</h1>

    <div id="errorMsg" style="display: none;">
        <div class="alert alert-danger">
            <p class="py-3" id="errorText"></p>
        </div>
    </div>

    <div class="space-y-12 text-center" id="sdgs_desa" style="display: none;">
        <span class="text-h2" id="average"></span>
        </br>
        <span class="text-h6">Skor SDGs {{ ucwords(setting('sebutan_desa')) }}</span>
    </div>

    <div id="sdgsData" class="grid grid-cols-2 lg:grid-cols-4 gap-5 py-5">
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $.get("{{ route('api.sdgs') }}", function(data) {
                if (data['error_msg']) {
                    $('#errorMsg').show().next('#sdgs_desa').hide();
                    $('#errorText').html(data['error_msg']);
                    return;
                }

                $('#sdgs_desa').show();
                var {
                    data,
                    total_desa,
                    average
                } = data['data'][0]['attributes'];
                var path = BASE_URL + 'assets/images/sdgs/';
                $('#average').text(average);

                data.forEach(item => {
                    var image = path + item.image;
                    $('#sdgsData').append(`
                        <div class="space-y-3">
                            <img class="w-full object-cover object-center" src="${image}" alt="${item.image}" />
                            <div class="space-y-1 text-sm text-center z-10">
                                <span class="text-h6">NILAI</span>
                                <span class="block">${item.score}</span>
                            </div>
                        </div>
                    `);
                });
            });
        });
    </script>
@endpush
