<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($w_cos): ?>
    <div class="relative-row ptb-10">
        <div class="container-page">
            <div class="forwidget">
                <div class="widgetgrid-body">
                    <div class="widgetgrid-body-line"></div>
                    <div class="withscrollbig" style="z-index:3;">
                        <div class="widgetgrid-inner">
                            <div class="margin-min5">
                                <?php foreach ($w_cos as $widget): ?>
                                    <?php
                                    $judul_widget = [
                                        'judul_widget' => str_replace('Desa', ucwords($this->setting->sebutan_desa), strip_tags($widget['judul']))
                                    ];
                                    ?>
                                    <div class="column-3">
                                        <div class="pd-lr-5">
                                            <?php if ($widget["jenis_widget"] == 1): ?>
                                                <?php if (in_array($widget["isi"], ['agenda.php', 'jam_kerja.php', 'komentar.php', 'arsip_artikel.php', 'sinergi_program.php', 'menu_kategori.php', 'statistik_pengunjung.php'])): ?>
                                                <?php $this->load->view("{$folder_themes}/widgets/{$widget['isi']}", $judul_widget) ?>
                                                <?php endif ?>
                                            <?php elseif ($widget["jenis_widget"] == 2): ?>	
                                                <?php $this->load->view("../../{$widget['isi']}", $judul_widget) ?>
                                            <?php else : ?>
                                                <?php if (!in_array(strtoupper(strip_tags($widget['judul'])), [strtoupper('event'),strtoupper('logo')])): ?>
                                                <div class="head-widget flexleft bg-grey-dark2">
                                                    <img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/>
                                                    <?= strip_tags($widget['judul']) ?>
                                                </div>
                                                <div class="widget-height bg-white border-grey-soft">
                                                    <div class="widgetscroll">
                                                        <div class="p-10">
                                                            <img class="img-responsive mb-2 mw-100" src="<?= to_base64(LOKASI_GAMBAR_WIDGET . $widget['foto']); ?>" alt="">
                                                            <?= htmlspecialchars_decode(html_entity_decode($widget['isi']), ENT_QUOTES) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>			
                    </div>
                </div>	
            </div>
        </div>
    </div>
<?php endif; ?>
