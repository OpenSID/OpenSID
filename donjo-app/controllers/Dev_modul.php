<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Services\Module\LocalMarketplace;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Tab "Sumber (Pengembangan)" pada halaman Paket Tambahan: mengalihkan seluruh
 * halaman antara bursa paket Layanan (nyata) dan bursa paket repo lokal
 * (simulasi), plus melayani katalog lokal untuk tab Paket Tersedia/Pendaftaran.
 *
 * DEV-ONLY: konstruktor menolak selain `ENVIRONMENT=development`. Berkas ini,
 * rutenya (`Routes/Web/dev.php`), view-nya (`admin/dev_modul/*`), dan servis
 * {@see LocalMarketplace} semuanya di-`export-ignore` sehingga TIDAK ikut rilis.
 */
class Dev_modul extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'paket-tambahan';

    public function __construct()
    {
        parent::__construct();

        if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) !== 'development') {
            show_404();
        }

        isCan('b');
    }

    /**
     * Tab "Sumber": toggle mode Layanan/lokal + pendaftaran paket ke bursa paket
     * (URL repo atau folder lokal), dirender di kerangka tab Paket Tambahan (act_tab=5).
     */
    public function index(): void
    {
        $market = app(LocalMarketplace::class);

        $data = [
            'content'          => 'admin.dev_modul.sumber',
            'act_tab'          => 5,
            'lokal'            => LocalMarketplace::aktif(),
            'paket_repo'       => $market->repos(),
            'kandidat'         => $market->kandidat(),
            'server_layanan'   => (string) config_item('server_layanan'),
            'form_action'      => site_url('dev-modul/sumber'),
            'form_daftar'      => site_url('dev-modul/daftar'),
            'form_daftar_lokal' => site_url('dev-modul/daftar-lokal'),
            'form_batal'       => site_url('dev-modul/batal-daftar'),
        ];

        view('admin.plugin.index', $data);
    }

    /**
     * Setel mode sumber (Layanan/lokal), lalu kembali ke Paket Tersedia.
     */
    public function sumber()
    {
        isCan('u');

        $lokal = $this->input->post('lokal') === 'lokal';
        LocalMarketplace::setel($lokal);

        $pesan = $lokal
            ? 'Sumber paket dialihkan ke bursa paket lokal (simulasi Layanan).'
            : 'Sumber paket dikembalikan ke Layanan (server nyata).';

        return redirect_with('success', $pesan, 'plugin');
    }

    /**
     * Daftarkan paket dari URL repo (unduh ZIP ke bursa paket).
     */
    public function daftar()
    {
        isCan('u');

        try {
            $url  = trim((string) ($this->input->post('url') ?? ''));
            $ref  = trim((string) ($this->input->post('ref') ?? ''));
            $name = app(LocalMarketplace::class)->daftarkanUrl($url, $ref);

            return redirect_with('success', "Paket {$name} diunduh & didaftarkan ke bursa paket lokal.", 'dev-modul');
        } catch (Exception $e) {
            log_message('error', 'Dev_modul daftar URL: ' . $e->getMessage());

            return redirect_with('error', 'Gagal mendaftarkan paket: ' . $e->getMessage(), 'dev-modul');
        }
    }

    /**
     * Daftarkan paket dari folder lokal (snapshot working-tree ke gudang).
     */
    public function daftarLokal()
    {
        isCan('u');

        try {
            $path = trim((string) ($this->input->post('path') ?? ''));
            $name = app(LocalMarketplace::class)->daftarkanLokal($path);

            return redirect_with('success', "Paket {$name} didaftarkan ke bursa paket lokal (snapshot lokal).", 'dev-modul');
        } catch (Exception $e) {
            log_message('error', 'Dev_modul daftar lokal: ' . $e->getMessage());

            return redirect_with('error', 'Gagal mendaftarkan paket: ' . $e->getMessage(), 'dev-modul');
        }
    }

    /**
     * Batalkan pendaftaran paket dari bursa paket lokal (hapus dari gudang).
     */
    public function batalDaftar()
    {
        isCan('u');

        $name = (string) ($this->input->post('name') ?? '');

        try {
            app(LocalMarketplace::class)->batalDaftar($name);

            return redirect_with('success', "Paket {$name} dikeluarkan dari bursa paket lokal.", 'dev-modul');
        } catch (Exception $e) {
            log_message('error', 'Dev_modul batalDaftar: ' . $e->getMessage());

            return redirect_with('error', "Gagal mengeluarkan paket {$name}: " . $e->getMessage(), 'dev-modul');
        }
    }

    /**
     * Katalog bursa paket lokal dalam bentuk API Layanan — dikonsumsi JS tab
     * "Paket Tersedia"/"Form Pendaftaran" saat mode lokal aktif.
     */
    public function katalog()
    {
        $page = (int) ($this->input->get('page') ?: 1);
        $tipe = (string) ($this->input->get('tipe') ?? '');

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(app(LocalMarketplace::class)->katalog($page, $tipe)));
    }
}
