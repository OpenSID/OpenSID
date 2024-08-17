<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Lembar Disposisi</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="<?= asset('css/report.css') ?>" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="<?= favico_desa() ?>"/>
        <style type="text/css">
            #disposisi td.judul {
                text-align: center;
                font-weight: bold;
                font-size: larger;
            }
            #disposisi th {
                text-align: left;
                font-style: normal;
                font-weight: normal;
                width: 1%;
                white-space: nowrap;
                padding-left: 5px;
                padding-right: 10px;
                background-color: inherit;
                border: 0px;
                text-transform: none;
            }
            table.border th.no-border {
                border: 0px !important;
            }
            table.border td.no-border {
                border: 0px !important;
            }
            table.border td.no-border-kanan {
                border-right: 0px !important;
            }
            table.border td.no-border-kiri {
                border-left: 0px !important;
            }
            td.nostretch {
                width:1%;
                white-space:nowrap;
            }
            .header {
                border-bottom: 0px;
            }
            .kop {
                text-align: center;
                font-size: larger;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <!-- Print Body -->
            <div id="body">
                <table width="100%">
                    <tr>
                        <td class="nostretch">
                            <img src="<?= gambar_desa($desa['logo']); ?>" alt=""  width="100" height="80" class="logo">
                        </td>
                        <td>
                            <div class="header">
                                <h4 class="kop">PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($desa['nama_kabupaten'])?></h4>
                                <h4 class="kop">KECAMATAN <?= strtoupper($desa['nama_kecamatan'])?></h4>
                                <h4 class="kop"><?= strtoupper($this->setting->sebutan_desa)?> <?= strtoupper($desa['nama_desa'])?></h4>
                            </div>
                        </td>
                    </tr>
                </table>
                <table id="disposisi" class="border thick">
                    <tbody>
                        <tr>
                            <td colspan="6" class="judul">LEMBAR DISPOSISI</td>
                        </tr>
                        <tr>
                            <th class="no-border">Nomor Urut</th>
                            <td class="no-border nostretch">:</td>
                            <td colspan="4" class="no-border"><?= $surat['nomor_urut']?></td>
                        </tr>
                        <tr>
                            <th class="no-border">Nomor & Tgl Surat</th>
                            <td class="no-border nostretch">:</td>
                            <td colspan="4" class="no-border"><?= $surat['nomor_surat'] . ', ' . tgl_indo($surat['tanggal_surat'])?></td>
                        </tr>
                        <tr>
                            <th class="no-border">Dari</th>
                            <td class="no-border nostretch">:</td>
                            <td colspan="4" class="no-border"><?= $surat['pengirim']?></td>
                        </tr>
                        <tr>
                            <th class="no-border">Perihal</th>
                            <td class="no-border nostretch">:</td>
                            <td colspan="4" class="no-border"><?= $surat['isi_singkat']?></td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="judul">DISPOSISI KEPADA</td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <table>

                                <?php for ($i = 0; $i < count($ref_disposisi); $i += 2): ?>
                                        <tr>
                                            <td class="nostretch no-border-kanan" style="vertical-align: text-top;">
                                                <?= $ref_disposisi[$i]['nama']?>
                                            </td>
                                            <td class="no-border-kiri" style="vertical-align: text-top;">
                                                <input type="checkbox" style="zoom: 2; padding: 10px" disabled="disabled"<?= selected(in_array($ref_disposisi[$i]['id'], $disposisi_surat_masuk), true, true) ?>/>
                                            </td>
                                            <td class="nostretch no-border-kanan" style="vertical-align: text-top;">
                                                <?= $ref_disposisi[$i + 1]['nama']?>
                                            </td>
                                            <td class="no-border-kiri" style="vertical-align: text-top;">
                                                <input type="checkbox" style="zoom: 2; padding: 10px" disabled="disabled" <?= selected(in_array($ref_disposisi[$i + 1]['id'], $disposisi_surat_masuk), true, true) ?>/>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" height="100px;" style="vertical-align: text-top;">
                                <span style="text-decoration: underline;">Isi Disposisi</span><br>
                                <p style="margin: 5px 0 0 10px;">
                                    <?= $surat['isi_disposisi']?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php $this->load->view('global/blok_ttd_pamong.php', ['total_col' => 6, 'spasi_kiri' => 1, 'spasi_tengah' => 2]); ?>
            </div>
        </div>
    </body>
</html>
