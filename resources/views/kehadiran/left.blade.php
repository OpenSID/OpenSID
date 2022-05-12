@include('admin.layouts.components.datetime_picker')

<div class="form-left vertical-align">
    <div class="row text-center">
        <div class="col-xm-12 col-sm-12">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="Lambang Desa"
                class="img-responsive center-block" />
        </div>
        <div class="col-xm-12 col-sm-12">
            <div class="col-sm-1"></div>
            <div class="text-ceter col-sm-10">
                <h1>Aplikasi Rekam Kehadiran Perangkat {{ ucwords($setting->sebutan_desa) }}</h1>
                <h5>IP Address : {{ $ip_address }}</h5>
                @if($mac_address)
                    <h5> MAC Address : {{ $mac_address }}</h5>
                @endif
                <h5> ID Pengunjung : {{ $id_pengunjung }}</h5>
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

    <div class="jam" >
        <div class="row text-center" style="margin-top: 10%; margin-right: 10px;margin-left: 10px;">
            <div class="col-sm-12" style="background-color:#289DA5">
                <h4 style="margin: 10px 0px; color:white">{{ date('F Y') }}</h4>
            </div>
            <div class="col-sm-12" style="background-color:#E7E7E7">
                <h1 style="margin: 10px 0px; color:#505050">{{ date('d') }}</h1>
            </div>
        </div>

        <div class="row text-center" style="margin-top: 10%; margin-right: 10px;margin-left: 10px;">
            <div class="col-sm-12"  style="background-color:#E7E7E7">
                <h3 style="margin: 10px 0px; color:#505050" id="jam"> </h3>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {

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
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }
        startTime();
    });

    $(document).ready(function () { 
        // Initialize the agent at application startup.
        const fpPromise = import('https://openfpcdn.io/fingerprintjs/v3')
            .then(FingerprintJS => FingerprintJS.load())

        // Get the visitor identifier when you need it.
        fpPromise
            .then(fp => fp.get())
            .then(result => {
            // This is the visitor identifier:
            const browserId = result.visitorId
            createCookie("pengunjung", browserId, "1");
            })
        });

        // Function to create the cookie 
        function createCookie(name, value, days) { 
        var expires; 

        if (days) { 
            var date = new Date(); 
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); 
            expires = "; expires=" + date.toGMTString(); 
        } 

        else { 
            expires = ""; 
        } 

        document.cookie = escape(name) + "=" +  
        escape(value) + expires + "; path=/"; 
        } 
</script>
@endpush