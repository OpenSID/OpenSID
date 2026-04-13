@extends('theme::layouts.full-content')

@section('content')
    @include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center">
				<h1>SDGs {{ ucwords(setting('sebutan_desa')) }}</h1>
			</div>
		</div>
		<div class="margin-page">
			<div class="row">
			<div class="col-sm-12">
			<div class="head-module align-center mb-20">
				<h2>{{ $heading }}</h2>
			</div>
			<div class="box-body mb-20">
				<div id="errorMsg" style="display: none;">
					<div class="alert alert-danger">
						<p class="py-3" id="errorText"></p>
					</div>
				</div>
				<div class="box-body">
					<div class="flex-center">
					<div class="numbertotal"><span class="total-bumds" style="text-align: center;" id="average"></span></div>
					<div class="score"><span class="desc-bumds" style="text-align: center;">Skor SDGs {{ ucwords(setting('sebutan_desa')) }}</span></div>
					</div>
					
					<div class="row" id="sdgsData">

					</div>
				</div>
			</div>
			</div>
			</div>
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
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
                $('#average').prepend(`${average} `);

                data.forEach(item => {
                    const image = path + item.image;
                    $('#sdgsData').append(`
                    <div class="col-lg-4 col-sm-12">
                        <div class="box-shadow brd-10 mt-20">
							<div class="sdgs-box">
								<div class="sdgs-grid">
									<div class="sdgs-icon">
									<img src="${image}" alt="${item.image}">
									</div>
									<div class="sdgs-info flex-center align-center">
										<div>
										<div class="nilai"><span class="desc-bumds">Nilai</span></div>
										<div class="nilaiscore"><span class="total-bumds">${item.score}</span></div>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                `);
                });
            });
        });
    </script>
@endpush
