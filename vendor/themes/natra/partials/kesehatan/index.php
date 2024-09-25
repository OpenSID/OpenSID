<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box-header">    
    <div class="container">
        <h1 class="text-h3"><?= $title; ?></h1>
        <form class="form form-inline" action="" method="get">                           
                <div class="form-group">
                    <select name="kuartal" id="kuartal" required class="form-control input-sm" title="Pilih salah satu">
                        <?php foreach (kuartal2() as $item): ?>
                        <option value="<?= $item['ke'] ?>" <?= $item['ke'] == $kuartal ? 'selected' : '' ?>>
                            Kuartal ke <?= $item['ke'] ?>
                            (<?= $item['bulan'] ?>)
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>
            
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control input-sm" title="Pilih salah satu">
                        <?php foreach ($dataTahun as $item): ?>
                        <option value="<?= $item->tahun ?>"><?= $item->tahun ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            
                <div class="form-group">
                    <select name="id_posyandu" class="form-control input-sm" title="Pilih salah satu">
                        <option value="">Semua</option>
                        <?php foreach ($posyandu as $item): ?>
                        <option value="<?= $item->id ?>" <?= $item->id == $idPosyandu ? 'selected' : '' ?>>
                            <?= $item->nama ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-social btn-info btn-sm" id="cari">
                    <i class="fa fa-search"></i> Cari
                </button>
            </div>
            
        </form>
    </div>
    <div class="box-body text-sm py-2 space-y-4">
    <?php $this->load->view($folder_themes . '/partials/kesehatan/widget'); ?>
    <?php $this->load->view($folder_themes . '/partials/kesehatan/chart_stunting_umur'); ?>
    <?php $this->load->view($folder_themes . '/partials/kesehatan/chart_stunting_posyandu'); ?>
    <?php $this->load->view($folder_themes . '/partials/kesehatan/scorecard', $scorecard); ?>
    </div>
</div>

