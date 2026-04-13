<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>@font-face{font-family:'Titanium Web';src:url("{{ theme_asset('fonts/TitaniumWeb-Regular.woff2') }}") format('woff2');font-weight:400;font-style:normal;font-display:swap;}@font-face{font-family:'Titanium Web';src:url("{{ theme_asset('fonts/TitaniumWeb-Bold.woff2') }}") format('woff2');font-weight:700;font-style:normal;font-display:swap;}</style>
<link rel="stylesheet" href="{{ theme_asset('css/style.css') }}">
@if (cek_koneksi_internet())
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" integrity="sha384-gOaRlqAhqPUMlR/5HfjaLm+COAJ+Ka0Am9GCueJAWwFluNWKDUZJ8GUGhBJ1r+J/" crossorigin="anonymous">
@endif
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap.min.css" integrity="sha384-YHPGPaIQUmoGiga1LScY4hoOESDpx8HssQQYh3ZLrYkJ7EOXOygNDnDTaJOfcjXf" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha384-KZO2FRYNmIHerhfYMjCIUaJeGBRXP7CN24SiNSG+wdDzgwvxWbl16wMVtWiJTcMt" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl/2.15.0/mapbox-gl.css" integrity="sha384-SDYx9Nwa5fE1fRuBplOPejrcbPOK/ql0Uym6hsGsTvnlC784P5LZhBJIbo8O/O+0" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/leaflet-measure-path.css') }}">
<link rel="stylesheet" href="{{ asset('css/MarkerCluster.css') }}">
<link rel="stylesheet" href="{{ asset('css/MarkerCluster.Default.css') }}">
<link rel="stylesheet" href="{{ asset('css/leaflet.groupedlayercontrol.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/leaflet.fullscreen.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" integrity="sha384-qlUhevqmCF5AxtnfkF0zXJClBzA6GJuX/UrLejCfE61bBGt+zo/My0AJ+ojVmUSb" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" integrity="sha384-gAPqlBuTCdtVcYt9ocMOYWrnBZ4XSL6q+4eXqwNycOr4iFczhNKtnYhF3NEXJM51" crossorigin="anonymous">