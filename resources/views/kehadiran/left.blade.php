@include('admin.layouts.components.datetime_picker')

<div class="form-left vertical-align">
    <div class="row text-center">
        <div class="col-xm-12 col-sm-12">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="Lambang Desa" class="img-responsive center-block" />
        </div>
        <div class="col-xm-12 col-sm-12">
            <div class="col-sm-1"></div>
            <div class="text-ceter col-sm-10">
                <h1>Aplikasi Rekam Kehadiran Perangkat {{ ucwords($setting->sebutan_desa) }}</h1>
                <h5>IP Address : {{ $ip_address }}</h5>
                @if ($mac_address)
                    <h5> MAC Address : {{ $mac_address }}</h5>
                @endif
                <h5>ID Pengunjung : <span id="pengunjung"></span>&nbsp;<span><a href="#" class="copy" title="Copy" style="color: white"><i class="fa fa-copy"></i></a></span></h5>
            </div>
        </div>
        <div class="col-xm-12 col-sm-2"></div>
    </div>
    <div class="callout">
        <h4> {{ ucwords($setting->sebutan_desa . ' ' . $desa['nama_desa']) }}
        </h4>
        <p> {{ ucwords($setting->sebutan_kecamatan . ' ' . $desa['nama_kecamatan']) }}
        </p>
    </div>

    <div class="jam">
        <div class="row text-center" style="margin-top: 10%; margin-right: 10px;margin-left: 10px;">
            <div class="col-sm-12" style="background-color:#289DA5">
                <h4 style="margin: 10px 0px; color:white">{{ date('F Y') }}</h4>
            </div>
            <div class="col-sm-12" style="background-color:#E7E7E7">
                <h1 style="margin: 10px 0px; color:#505050">{{ date('d') }}</h1>
            </div>
        </div>

        <div class="row text-center" style="margin-top: 10%; margin-right: 10px;margin-left: 10px;">
            <div class="col-sm-12" style="background-color:#E7E7E7">
                <h3 style="margin: 10px 0px; color:#505050" id="jam"> </h3>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.components.konfirmasi_cookie')
@include('admin.layouts.components.aktifkan_cookie')

@push('scripts')
    <script src="{{ asset('js/id_browser.js') }}"></script>
    <script>
        $(function() {
            // Refrensi https://www.w3schools.com/js/tryit.asp?filename=tryjs_timing_clock
            function startTime() {
                const today = moment();
                let h = today.hours();
                let m = today.minutes();
                let s = today.seconds();
                m = checkTime(m);
                s = checkTime(s);
                $('#jam').html(h + ":" + m + ":" + s);
                setTimeout(startTime, 1000);
            }

            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i
                }; // add zero in front of numbers < 10
                return i;
            }
            startTime();
        });
    </script>
@endpush
