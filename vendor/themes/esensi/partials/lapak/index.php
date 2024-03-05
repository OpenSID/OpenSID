<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li aria-current="page">Lapak</li>
  </ol>
</nav>
<h1 class="text-h2"><i class="fas fa-store mr-1"></i> Lapak</h1>
<form method="get" class="w-full block py-4">
  <div class="flex gap-3 lg:w-7/12 flex-col lg:flex-row">
    <select class="form-input inline-block select2" id="id_kategori" name="id_kategori" style="min-width: 25%">
      <option selected value="">Semua Kategori</option>
      <?php foreach ($kategori as $kategori_item) : ?>
        <option value="<?= $kategori_item->id ?>" <?= selected($id_kategori, $kategori_item->id) ?>><?= $kategori_item->kategori ?></option>
      <?php endforeach; ?>
    </select>
    <input type="text" name="keyword" maxlength="50" class="form-input" value="<?= $keyword; ?>" placeholder="Cari Produk" style="min-width: 25%">
    <button type="submit" class="btn btn-primary flex-shrink-0 text-center">Cari</button>
    <?php if ($keyword): ?>
      <a href="<?=site_url('lapak')?>" class="btn btn-secondary flex-shrink-0 text-center">Tampilkan Semua</a>
    <?php endif ?>
  </div>
</form>

<?php if($produk) : ?>
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-1">
    <?php foreach($produk as $in => $pro) : ?>
      <?php $foto = json_decode($pro->foto); ?>
      <div class="flex flex-col justify-between space-y-4 this-product">
        <div class="space-y-3">
          <?php if($pro->foto) : ?>
            <div class="owl-carousel">
              <?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
                <?php if ($foto[$i]): ?>
                  <?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
                    <img src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i+1); ?>" class="h-44 w-full object-cover object-center bg-gray-300">
                  <?php endif; ?>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
            <?php else: ?>
              <img class="h-44 w-full object-cover object-center bg-gray-300" src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Foto Produk"/>
          <?php endif ?>
          <div class="space-y-1/2 text-sm flex flex-col detail">
            <span class="font-heading font-medium"><?= $pro->nama ?></span>
            <?php $harga_potongan = ($pro->tipe_potongan == 1) ? ($pro->harga * ($pro->potongan / 100)) : $pro->potongan; ?>
            <?php if($pro->potongan != 0) : ?>
              <s class="text-xs text-red-500"><?= rupiah($pro->harga); ?></s>
            <?php endif ?>
            <span class="text-lg font-bold"><?= rupiah($pro->harga - $harga_potongan); ?> <span class="text-xs font-thin">/ <?= $pro->satuan ?></span></span>
            <p class="text-xs pt-1">
              <?= $pro->deskripsi ?>
            </p>
            <span class="pt-2 text-xs font-bold text-gray-500 dark:text-gray-50">
            <i class="fas fa-award mr-1"></i> <?= $pro->pelapak ?? 'Admin'; ?> <i
                  class="fas fa-check text-xs bg-green-500 h-4 w-4 inline-flex items-center justify-center rounded-full text-white"></i></span>
          </div>
        </div>
        <div class="group flex items-center space-x-1">
          <?php if ($pro->telepon): ?>
            <?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => "%0A"], nl2br($this->setting->pesan_singkat_wa)); ?>
            <a href="https://api.whatsapp.com/send?phone=<?=format_telpon($pro->telepon);?>&amp;text=<?= $pesan; ?>" rel="noopener noreferrer" target="_blank" class="btn btn-primary text-xs text-center"><i class="fa fa-shopping-cart mr-1"></i> Beli Sekarang</a>
          <?php endif; ?>
          <button type="button" class="btn btn-secondary text-xs text-center rounded-0" data-bs-toggle="modal" data-bs-target="#modalLokasi" data-bs-remote="false" title="Lokasi" data-lat="<?= $pro->lat?>" data-lng="<?= $pro->lng?>" data-zoom="<?= $pro->zoom?>" data-title="Lokasi <?= $pro->pelapak?>"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi</button>
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <?php $p_data['paging_page'] = ($paging_page === 'lapak' ? $paging_page : 'lapak') ?>
  <?php $this->load->view($folder_themes .'/commons/paging', $p_data) ?>

  <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="modalLokasi" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog relative w-auto pointer-events-none">
      <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
        <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
          <h5 class="text-h6">Lokasi Penjual</h5>
        </div>
        <div class="modal-body p-4">
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function () {
      document.querySelector('#modalLokasi').addEventListener('shown.bs.modal', function (event) {
        const link = $(event.relatedTarget);
        const title = link.data('title');
        const modal = $(this);
        modal.find('.modal-title').text(title);
        modal.find('.modal-body').html("<div id='map' style='width: 100%; height:350px'></div>");

        const popup = `
          <div class="card">
            <div class="text-xs">
              <div class="py-1 space-y-1/2 text-sm flex flex-col">
                ${link.closest('.this-product').find('.detail').html()}
              </div>
            </div>
          </div>`;

        const posisi = [link.data('lat'), link.data('lng')];
        const zoom = link.data('zoom') || 10;
        $("#lat").val(link.data('lat'));
        $("#lng").val(link.data('lng'));

        var options = {
            maxZoom: <?= setting('max_zoom_peta') ?>,
            minZoom: <?= setting('min_zoom_peta') ?>,
        };

        // Inisialisasi tampilan peta
        pelapak = L.map('map', options).setView(posisi, zoom);

        // Menampilkan BaseLayers Peta
        getBaseLayers(pelapak, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");

        // Tampilkan Posisi Pelapak
        marker = new L.Marker(posisi, {
          draggable: false
        });

        pelapak.addLayer(marker);
        L.marker(posisi).addTo(pelapak).bindPopup(popup);
        L.control.scale().addTo(pelapak);
        pelapak.invalidateSize();
      });
    });
  </script>
  <?php else : ?>
    <p class="py-2">Tidak ada produk yang tersedia</p>
  <?php endif ?>