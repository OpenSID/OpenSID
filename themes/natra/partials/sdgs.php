<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    .info-box {
        border: 1px solid;
        border-radius: 10px;
        background-color: #fff;
        border-color: #d8dbe0;
    }

    .info-box-icon {
        width: 120px;
        height: 120px;
    }

    .info-box-content {
        padding: 5px 10px;
        margin-left: 130px;
        height: 120px;
    }

    .info-box-icon {
        padding-top: 0;
        background: white;
    }

    .info-box-text {
        text-transform: capitalize;
    }
    
    .sdgs-logo {
        width: 120px;
        height: 120px;
    }

    .total-bumds {
        font-size: 32px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: left;
        color: #232b39;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .desc-bumds {
        margin-top: 8px;
        font-size: 21px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: left;
        color: #5a677d;
    }
</style>
<div class="single_page_area">
    <h2 class="post_titile">SDGs <?= ucwords($this->setting->sebutan_desa) ?></h2>
    <div class="box-body">
        <?php $evaluasi = sdgs() ?>
        <?php if ($evaluasi): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="info-box" style="display: flex;justify-content: center;">
                        <span class="info-box-number total-bumds" style="text-align: center;" id="total">
                                <span class="info-box-text desc-bumds" style="text-align: center;">Skor SDGs <?= ucwords($this->setting->sebutan_desa) ?></span>
                            </span>
                        </div>
                </div>
                
                <?php $bagi = 0; foreach ($evaluasi as $key => $value): ?>
                    <?php
                        $total += $value->data->capaian;
                        
                        if (is_numeric($value->data->capaian)) {
                            $bagi++;
                        }
                    ?>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon">
                                <img class="sdgs-logo" src="https://sid.kemendesa.go.id/images/<?=$value->name?>.webp" alt="sdgs-logo">
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-number total-bumds"><?= $value->data->capaian ?>
                                    <span class="info-box-text desc-bumds">Nilai</span>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
                <?php $hasil = ($bagi > 0) ? round($total / $bagi, 2) : 'N/A' ?>
            </div>
        <?php else: ?>
            <b>Maaf. Halaman ini tidak dapat di akses.</b>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#total').prepend('<?= $hasil ?>')
});
</script>