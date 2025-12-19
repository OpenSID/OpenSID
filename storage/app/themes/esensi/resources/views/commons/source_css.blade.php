@if (cek_koneksi_internet())
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.compat.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.0/css/ionicons.min.css">
@endif
<link rel="stylesheet" href="{{ theme_asset('css/style.min.css', ['themeVersion' => $themeVersion]) }}">
<link rel="stylesheet" href="{{ theme_asset('css/custom.css', ['themeVersion' => $themeVersion]) }}">
<link rel="stylesheet" href="{{ theme_asset('vendor/OwlCarousel2-2.3.4/assets/owl.carousel.min.css', ['themeVersion' => $themeVersion]) }}">