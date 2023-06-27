<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li aria-current="page">Data Kelompok</li>
  </ol>
</nav>

<h1 class="text-h2"><?= $title ?></h1>
<div class="space-y-3 content py-3">
  
  <p class="py-4"><?= $detail['keterangan'] ?></p>

  <h2 class="text-h4">Daftar Pengurus</h2>
  <div class="table-responsive content">
    <table class="w-full text-sm">
      <thead>
        <tr>
          <th>No</th>
          <th>Jabatan</th>
          <th>Nama</th>
          <th>Alamat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pengurus as $key => $data): ?>
          <tr>
            <td><?= $key + 1?></td>
            <td><?= $data['jabatan'] ?></td>
            <td nowrap><?= $data['nama']?></td>
            <td><?= $data['alamat']?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <h2 class="text-h4">Daftar Anggota</h2>
  <div class="table-responsive content">
    <table class="w-full text-sm" id="tabel-data">
      <thead>
        <tr>
          <th>No</th>
          <th>No. Anggota</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>Jenis Kelamin</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($anggota as $key => $data): ?>
        <tr>
          <td></td>
          <td><?= $data['no_anggota'] ?:'-' ?></td>
          <td nowrap><?= $data['nama'] ?></td>
          <td><?= $data['alamat'] ?></td>
          <td><?= $data['sex'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
  $(document).ready(function(){
    var tabelData = $('#tabel-data').DataTable({
      'processing': false,
      'order': [[1, 'desc']],
      'pageLength': 10,
      'lengthMenu': [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "Semua"]
      ],
      'columnDefs': [
        {
            'searchable': false,
            'targets': [0]
        },
        {
            'orderable': false,
            'targets': [0]
        }
      ],
      'language': {
        'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
      },
    });

    tabelData.on( 'order.dt search.dt', function () {
      tabelData.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });
</script>