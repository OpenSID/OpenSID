@extends('theme::layouts.full-content')

@section('content')
<div class="box-def">
    <div class="box-def-inner">
        <div class="heading-module l-flex">
            <div class="heading-module-inner l-flex">
                <i class="fa fa-cog"></i>
                <h1>SDGs {{ ucwords(setting('sebutan_desa')) }}</h1>
            </div>
        </div>

        <div id="errorMsg" style="display: none;">
            <div class="alert alert-danger">
                <p class="py-3" id="errorText"></p>
            </div>
        </div>


        <div class="c-flex" id="sdgs_desa" style="margin:15px 0;display: none">
            <div class="text-center">
                <h3 id="average"></h3>
                <h3 style="margin:10px 0 0;">Skor SDGs Desa</h3>
            </div>

        </div>

        <div id="sdgsData" class="row-custom mlr-min10 sdgs">
        </div>
    </div>
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
                var { data, total_desa, average } = data['data'][0]['attributes'];
                var path = BASE_URL + 'assets/images/sdgs/';
                $('#average').text(average);

                data.forEach(item => {
                    var image = path + item.image;
                    $('#sdgsData').append(`
                        <div class="column-4 box-def">
                            <div class="sdgs-box l-flex">
                                <img src="${image}" alt="${item.image}" />
                                <div style="padding-left:5px;text-align:center;">
                                    <p>NILAI</p>
                                    <h3>${item.score}</h3>
                                </div>
                            </div>
                        </div>                        
                    `);
                });
            });
        });
</script>
@endpush
