<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view($folder_themes . '/commons/meta') ?>
  <?php if (cek_koneksi_internet()): ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl/2.0.1/mapbox-gl.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700;900&display=swap" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.1.1/highcharts.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.1.1/highcharts-3d.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <?php endif ?>
  <link rel="stylesheet" href="<?= asset('css/peta.css'); ?>">

  <?php $this->load->view('global/validasi_form', ['web_ui' => true]); ?>
  <style>
    .font-primary {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="font-primary bg-gray-100">
  <style>
    #map {
      width: 100%;
      height: 88vh !important;
    }
  </style>

  <main class="container w-full space-y-1 text-gray-600">
    <div class="page-title text-center">
      <h2 class="text-3xl font-bold text-bold my-0 pt-6 pb-2">Peta <?= NAMA_DESA ?></h2>
      <a href="<?= site_url() ?>" class="inline-block" class="text-link hover:text-link">Kembali ke Beranda</a>
    </div>
    <br>
    <?php $this->load->view($halaman_peta); ?>
  </main>
  <?php $this->load->view('head_tags_front') ?>
</body>

</html>