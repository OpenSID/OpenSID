<?php
    if (empty($ekstensi)) {
        $ekstensi = 'xls';
    }

    if ($aksi == 'unduh') {
        header('Content-type: application/' . $ekstensi);
        header('Content-Disposition: attachment; filename=' . namafile($file) . '.' . $ekstensi);
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php if ($aksi == 'cetak' && ! isset($headjs)) : ?>
        <?php $this->load->view('print/headjs') ?>
    <?php elseif ($aksi == 'cetak' && $headjs) : ?>
        <?php $this->load->view('print/headjs') ?>
    <?php endif ?>
    <head>
        <title><?= ucwords($file); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="<?= asset('css/report.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <div id="container">
            <div id="body">
                <?php $this->load->view($isi); ?>
            </div>
            <?php if (count($letak_ttd ?? []) > 0): ?>
            <br />
            <table width="10%">
                <tr class="text-center">
                    <td colspan="<?= $letak_ttd[0]; ?>" width=<?= $width ? '"' . (0.1 * $width) . 'mm;"' : '5%'?>>&nbsp;</td>
                    <?php if (! empty($pamong_ketahui)) :?>
                        <td colspan="<?= $letak_ttd[1]; ?>" width=<?= $width ? '"' . (0.2 * $width) . 'mm;"' : '40%'?>>
                            MENGETAHUI
                            <br><?= strtoupper($pamong_ketahui['pamong_jabatan'] . ' ' . $config['nama_desa']) ?>
                            <br><br><br><br>
                            <br><u><?= strtoupper($pamong_ketahui['nama'] ?? $pamong_ketahui['pamong_nama']) ?></u>
                            <br><?= $this->setting->sebutan_nip_desa  ?>/NIP : <?= $pamong_ketahui['pamong_nip']?>
                        </td>
                    <?php endif; ?>
                    <td colspan="<?= $letak_ttd[2]; ?>" width=<?= $width ? '"' . (0.4 * $width) . 'mm;"' : '10%'?>>&nbsp;</td>
                    <td width=<?= $width ? '"' . (0.2 * $width) . 'mm;"' : '40%'?> nowrap>
                        <?= strtoupper($config['nama_desa'] . ', ' . tgl_indo($tgl_cetak ? date('Y m d', strtotime($tgl_cetak)) : date('Y m d'))) ?>
                        <br><?= strtoupper($pamong_ttd['pamong_jabatan'] . ' ' . $config['nama_desa']) ?>
                        <br><br><br><br>
                        <br><u><?= strtoupper($pamong_ttd['nama'] ?? $pamong_ttd['pamong_nama']) ?></u>
                        <br><?= $this->setting->sebutan_nip_desa  ?>/NIP : <?= $pamong_ttd['pamong_nip']?>
                    </td>
                    <td width=<?= $width ? '"' . (0.1 * $width) . 'mm;"' : '5%'?>>&nbsp;</td>
                </tr>
            </table>
            <?php endif; ?>
        </div>
    </body>
</html>
