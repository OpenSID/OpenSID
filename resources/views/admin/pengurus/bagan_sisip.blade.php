<div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <div id="container"></div>
                    <p class="highcharts-description"></p>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/bagan.css') }}">
    @include('admin.layouts.components.highchartjs')
    @include('admin.pengurus.chart_bagan_sotk', ['parentWidth' => true])
</div>
