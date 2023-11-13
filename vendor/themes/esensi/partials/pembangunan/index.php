<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li aria-current="page">Pembangunan</li>
  </ol>
</nav>
<h1 class="text-h2">Pembangunan</h1>
<?php if($pembangunan) : ?>
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-5">
    <?php foreach($pembangunan as $data) : ?>
      <div class="space-y-3">
        <?php if($data->foto && is_file(LOKASI_GALERI . $data->foto)) : ?>
        <img class="h-44 w-full object-cover object-center bg-gray-300 dark:bg-gray-600"
          src="<?= base_url(LOKASI_GALERI . $data->foto) ?>" alt="Foto Pembangunan" />

        <?php else: ?>
        <img class="h-44 w-full object-cover object-center bg-gray-300 dark:bg-gray-600"
          src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Tidak ditemukan" />
        <?php endif ?>

        <div class="space-y-2 text-sm flex flex-col detail">
          <h3 class="text-h5"><?= $data->judul ?></h3>
          <div class="inline-flex"><i class="fas fa-calendar-alt mr-2"></i> <?= $data->tahun_anggaran ?></div>
          <div class="font-thin">
            <i class="fas fa-map-marker-alt mr-1"></i>
            <?= ($data->alamat == "=== Lokasi Tidak Ditemukan ===") ? 'Lokasi tidak diketahui' : $data->alamat; ?>
          </div>
          <p class="text-sm pt-1">
            <?= $data->keterangan ?>
          </p>
        </div>
        <div class="group flex items-center space-x-1">
          <a href="<?= site_url('pembangunan/'.$data->slug) ?>"
            class="btn btn-primary text-xs text-center rounded-0">Selengkapnya <i class="fas fa-chevron-right ml-1"></i> </a>
          <?php if($data->lat && $data->lng) : ?>
          <button type="button" class="btn btn-secondary text-xs text-center rounded-0" data-bs-toggle="modal"
            data-bs-target="#modalLokasi" data-bs-remote="false" title="Lokasi Pembangunan" data-lat="<?= $data->lat?>"
            data-lng="<?= $data->lng?>" data-title="Lokasi Pembangunan"><i class="fas fa-map-marker-alt mr-2"></i> Lokasi</button>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <?php $p_data['paging_page'] = ($paging_page ?? 'pembangunan') ?>
  <?php $this->load->view($folder_themes .'/commons/paging', $p_data) ?>

  <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="modalLokasi" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog relative w-auto pointer-events-none">
      <div
        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
        <div
          class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
          <h5 class="text-h5">Lokasi Pembangunan</h5>
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
        pembangunan = L.map('map', options).setView(posisi, zoom);

        // Menampilkan BaseLayers Peta
        getBaseLayers(pembangunan, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");

        // Tampilkan Posisi pembangunan
        marker = new L.Marker(posisi, {
          draggable: false
        });

        pembangunan.addLayer(marker);
        L.icon({
          iconUrl: "<?= asset('images/gis/point/construction.png'); ?>",
        });
        L.marker(posisi).addTo(pembangunan).bindPopup(popup);
        L.control.scale().addTo(pembangunan);
        pembangunan.invalidateSize();
      });
    });
  </script>
  <?php else : ?>
    <div class="alert text-primary-100">Data pembangunan tidak tersedia...</div>
<?php endif ?>