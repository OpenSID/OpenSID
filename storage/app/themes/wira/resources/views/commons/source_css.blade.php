@if (cek_koneksi_internet())
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700;900&display=swap" rel="stylesheet">
@endif
<link rel="stylesheet" href="{{ theme_asset('vendor/animate-css/animate.compat.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('vendor/owl-carousel/owl.carousel.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ theme_asset('vendor/fancybox/jquery.fancybox.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('vendor/datatables/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('vendor/leaflet/leaflet.css') }}">
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css">
<link rel="stylesheet" href="{{ theme_asset('vendor/ionicons/ionicons.min.css') }}">
{{--
<link rel="stylesheet" href="{{ theme_asset('css/app.css') }}"> --}}
<link rel="stylesheet" href="{{ theme_asset('css/style.min.css?' . $themeVersion) }}">
<link rel="stylesheet" href="{{ theme_asset('css/custom.css?' . $themeVersion) }}">
<link rel="stylesheet" href="{{ theme_asset('css/ai_chat.css') }}">