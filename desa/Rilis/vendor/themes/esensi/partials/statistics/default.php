<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="breadcrumb">
    <ol>
        <li><a href="<?= site_url() ?>">Beranda</a></li>
        <li>Data Statistik</li>
    </ol>
</div>
<h1 class="text-h2"><?= $judul ?></h1>
<?php if(isset($list_tahun)): ?>
<div class="flex justify-between items-center space-x-3 py-2">
    <label for="owner" class="text-xs lg:text-sm">Tahun</label>
    <select class="form-control input-sm" id="tahun" name="tahun">
        <option selected="" value="">Semua</option>
        <?php foreach ($list_tahun as $item_tahun): ?>
            <option <?= $item_tahun == ($selected_tahun ?? NULL) ? 'selected' : '' ?> value="<?= $item_tahun ?>"><?= $item_tahun ?></option>
        <?php endforeach ?>
    </select>
</div>
<?php endif ?>
<div class="flex justify-between items-center space-x-1 py-5">
    <h2 class="text-h4">Grafik <?= $heading ?></h2>    
    <div class="text-right btn-switch-chart space-x-2 text-sm space-y-2 md:space-y-0">
        <button class="btn btn-secondary button-switch" data-type="column">Bar Graph</button>
        <button class="btn btn-secondary button-switch is-active" data-type="pie">Pie Graph</button>
        <a href="<?= site_url("data-statistik/{$slug_aktif}/cetak/cetak") ?>?tahun=<?= $selected_tahun ?>"
            class="btn btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Laporan" target="_blank">
            <i class="fa fa-print "></i> Cetak
        </a>
        <a href="<?= site_url("data-statistik/{$slug_aktif}/cetak/unduh") ?>?tahun=<?= $selected_tahun ?>"
            class="btn btn-accent btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Laporan" target="_blank">
            <i class="fa fa-print "></i> Unduh
        </a>
    </div>
</div>
<div id="statistics"></div>
<h2 class="text-h4">Tabel <?= $heading ?></h2>
<div class="content py-3">
    <div class="table-responsive">
        <table class="w-full text-sm">
            <thead>
                <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kelompok</th>
                <th colspan="2">Jumlah</th>
                <th colspan="2">Laki-laki</th>
                <th colspan="2">Perempuan</th>
                </tr>
                <tr>
                <th>n</th>
                <th>%</th>
                <th>n</th>
                <th>%</th>
                <th>n</th>
                <th>%</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; $l=0; $p=0; $hide=''; $h=0; $jm1=1; $jm = count($stat ?? []);?>
                <?php foreach ($stat as $data):?>
                <?php $jm1++; if (1):?>
                <?php $h++; if ($h > 12 AND $jm > 10): $hide='more'; ?>
                <?php endif;?>
                <tr class="<?=$hide?>">
                    <td class="text-center">
                    <?php if ($jm1 > $jm - 2):?>
                        <?=$data['no']?>
                    <?php else:?>
                        <?=$h?>
                    <?php endif;?>
                    </td>
                    <td><?=$data['nama']?></td>
                    <td class="text-right <?php ($jm1 <= $jm - 2) and ($data['jumlah'] == 0) and print('zero')?>"><?=$data['jumlah']?>
                    </td>
                    <td class="text-right"><?=$data['persen']?></td>
                    <td class="text-right"><?=$data['laki']?></td>
                    <td class="text-right"><?=$data['persen1']?></td>
                    <td class="text-right"><?=$data['perempuan']?></td>
                    <td class="text-right"><?=$data['persen2']?></td>
                </tr>
                <?php $i += $data['jumlah'];?>
                <?php $l += $data['laki']; $p += $data['perempuan'];?>
                <?php endif;?>
                <?php endforeach;?>
            </tbody>
        </table>
        <p style="color: red">
            Diperbarui pada : <?= tgl_indo($last_update); ?>
        </p>
    </div>
    <div class="flex justify-between py-5">
        <?php if($hide == 'more') : ?>
            <button class="btn btn-primary button-more" id="showData">Selengkapnya...</button>
        <?php endif ?>
        <button id="showZero" class="btn btn-secondary">Tampilkan Nol</button>
    </div>

    <?php if ($this->setting->daftar_penerima_bantuan && $bantuan) : ?>
        <script>
        const bantuanUrl = '<?= site_url('first/ajax_peserta_program_bantuan')?>?tahun=<?= $selected_tahun ?? '' ?>';
        </script>

        <input id="stat" type="hidden" value="<?=$st?>">
        <h2 class="text-h4">Daftar <?= $heading ?></h2>

        <div class="table-responsive content py-3">
            <table class="w-full text-sm" id="peserta_program">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Program</th>
                        <th>Nama Peserta</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .button-switch.is-active {
        background-color: #efefef;
        color: #999;
    }
</style>
<script>
    const dataStats = Object.values(<?= json_encode($stat) ?>);
    $(function(){
        $('#tahun').change(function(){
            const current_url = window.location.href.split('?')[0]
            window.location.href = `${current_url}?tahun=${$(this).val()}`;
        })

        const _chartType = '<?= $default_chart_type  ?? 'pie' ?>';
        
        if(_chartType == 'column') {            
            setTimeout(function(){
                $('.btn-switch-chart>.button-switch[data-type=column]').click()
            }, 1000)
        }
    })
</script>