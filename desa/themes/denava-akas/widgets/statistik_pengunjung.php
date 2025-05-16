<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="module-visitor">
    <div class="head-widget flexleft bg-grey-dark2">
        <img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/icon/monitor.svg") ?>" alt=""/>
        <?= $judul_widget ?>
    </div>
    <div class="widget-height bg-white border-grey-soft">
        <div class="widgetscroll">
            <div class="p-5">
                <table style="width: 100%; text-align: left; font-size: 100% !important;" cellpadding="0" cellspacing="0" class="table-inverse counter">
                    <?php
                    $dataPoints = [
                        'Hari ini' => 'hari_ini',
                        'Kemarin' => 'kemarin',
                        'Total Pengunjung' => 'total',
                        'Sistem Operasi' => 'os',
                        'IP Address' => 'ip_address',
                        'Browser' => 'browser',
                    ];
                    foreach ($dataPoints as $label => $dataPoint):
                        if ($dataPoint === 'hari_ini' || $dataPoint === 'kemarin' || $dataPoint === 'total') {
                            $value = number_format($statistik_pengunjung[$dataPoint], 0, ',', '.');
                        } else {
                            $value = $statistik_pengunjung[$dataPoint];
                        }
                    ?>
                        <tr>
                            <td style="width: 40% !important; padding: 5px 0 !important;"><?= $label ?></td>
                            <td style="width: 5%">:</td>
                            <td><?= $value ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
