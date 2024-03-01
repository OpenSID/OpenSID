<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title">
      <i class="fas fa-map-marker-alt mr-1"></i><?= $judul_widget ?>
    </h3>
  </div>
  <div class="box-body">
    <div id="map_canvas" style="height:200px;"></div>
    <button class="btn btn-accent btn-block mt-5"><a
        href="https://www.openstreetmap.org/#map=15/<?=$data_config['lat']."/".$data_config['lng']?>"
        style="color:#fff;" target="_blank">Buka Peta</a></button>
    <button class="btn btn-accent btn-block mt-5" data-bs-toggle="modal" data-bs-target="#detail">
      Detail
    </button>
    <!-- Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
      id="detail" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true">
      <div class="modal-dialog relative w-auto pointer-events-none">
        <div
          class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div
            class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
            <h5 class="text-xl font-medium leading-normal text-gray-800" id="detailLabel">Detail
              <?= ucwords($this->setting->sebutan_desa) ?></h5>
            <button type="button"
              class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
              data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body relative p-4 content">
            <table class="max-w-full text-xs lg:text-sm table-auto w-full">
              <tbody>
                <tr>
                  <td>Alamat</td>
                  <td>:</td>
                  <td><?=$desa['alamat_kantor']?></td>
                </tr>
                <tr>
                  <td><?=ucwords($this->setting->sebutan_desa)." "?></td>
                  <td>:</td>
                  <td><?=$desa['nama_desa']?></td>
                </tr>
                <tr>
                  <td><?=ucwords($this->setting->sebutan_kecamatan)?></td>
                  <td>:</td>
                  <td><?=$desa['nama_kecamatan']?></td>
                </tr>
                <tr>
                  <td><?=ucwords($this->setting->sebutan_kabupaten)?></td>
                  <td>:</td>
                  <td><?=$desa['nama_kabupaten']?></td>
                </tr>
                <tr>
                  <td>Kodepos</td>
                  <td>:</td>
                  <td><?=$desa['kode_pos']?></td>
                </tr>
                <tr>
                  <td>Telepon</td>
                  <td>:</td>
                  <td><?=$desa['telepon']?></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>:</td>
                  <td><?=$desa['email_desa']?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
  <?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
    var posisi = [<?=$data_config['lat'].",".$data_config['lng']?>];
    var zoom = <?=$data_config['zoom'] ?: 10?>;
  <?php else: ?>
    var posisi = [-1.0546279422758742,116.71875000000001];
    var zoom = 10;
  <?php endif; ?>

  var options = {
      maxZoom: <?= setting('max_zoom_peta') ?>,
      minZoom: <?= setting('min_zoom_peta') ?>,
  };
  
  var lokasi_kantor = L.map('map_canvas', options).setView(posisi, zoom);

  //Menampilkan BaseLayers Peta
  var baseLayers = getBaseLayers(lokasi_kantor, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");

  L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(lokasi_kantor);

  //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
  <?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
    var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
  <?php endif; ?>
</script>