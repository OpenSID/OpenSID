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

use App\Services\Layanan\LocalLayanan;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Emulator server Layanan untuk PENGEMBANGAN — melayani endpoint `/api/v1/...`
 * dari gudang lokal, seolah server Layanan nyata. Diaktifkan saat "Sumber paket"
 * = bursa lokal: {@see App\Services\Layanan\PengalihLayananLokal} mengalihkan
 * `server_layanan`/`bursa.url_penyedia` ke `site_url('layanan-lokal')`, sehingga
 * klien Guzzle modul **dan** JS browser menembak controller ini via self-HTTP
 * (TLS lolos di Herd).
 *
 * PUBLIK (bukan Admin_Controller): self-HTTP modul tak membawa sesi admin, jadi
 * controller ini TAK boleh menuntut login — ia meniru server eksternal
 * (autentikasi via token Bearer, sama seperti Layanan). Konstruktor menolak
 * selain `ENVIRONMENT=development`.
 *
 * DEV-ONLY: berkas ini, rutenya (`Routes/Web/dev.php`), servis pendukung
 * ({@see LocalLayanan}) di-`export-ignore` → tak ikut rilis.
 *
 * @property CI_Input  $input
 * @property CI_Output $output
 */
class Layanan_lokal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) !== 'development') {
            show_404();
        }
    }

    /**
     * `POST|GET /api/v1/pelanggan/pemesanan` — data pemesanan langganan desa.
     * Bentuk respons = body yang diharapkan klien langganan (LayananClient).
     */
    public function pemesanan(): void
    {
        $this->kirimJson(app(LocalLayanan::class)->body($this->tokenBearer()));
    }

    /**
     * `POST /api/v1/pelanggan/perpanjang` — stub sukses perpanjangan.
     */
    public function perpanjang(): void
    {
        $this->kirimJson(['success' => true, 'message' => 'Perpanjangan (simulasi) diterima.']);
    }

    /**
     * `POST /api/v1/pelanggan/daftarhitam` — stub (fire-and-forget di klien).
     */
    public function daftarhitam(): void
    {
        $this->kirimJson(['success' => true]);
    }

    /**
     * `POST /api/v1/pelanggan/catat-versi` — stub telemetri versi.
     */
    public function catatVersi(): void
    {
        $this->kirimJson(['success' => true]);
    }

    /**
     * `POST /api/v1/pelanggan/terdaftar` — cek status pendaftaran (belum terdaftar).
     */
    public function terdaftar(): void
    {
        $this->kirimJson(['terdaftar' => false]);
    }

    /**
     * `POST /api/v1/pelanggan/form-register` — stub form pendaftaran kerjasama.
     */
    public function formRegister(): void
    {
        $this->kirimJson(['success' => true, 'data' => []]);
    }

    /**
     * `POST /api/v1/pelanggan/register` — stub hasil pendaftaran kerjasama.
     */
    public function register(): void
    {
        $this->kirimJson(['success' => true, 'message' => 'Pendaftaran (simulasi) diterima.']);
    }

    /**
     * `GET /api/v1/pelanggan/pemesanan/faktur|deskripsi-faktur` — halaman nota
     * sederhana (dibuka `_blank` dari tabel pemesanan).
     */
    public function faktur(): void
    {
        $invoice = (string) ($this->input->get('invoice') ?? '-');

        $this->output
            ->set_content_type('text/html')
            ->set_output(
                '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Nota ' . html_escape($invoice) . '</title></head>'
                . '<body style="font-family:sans-serif;padding:2rem"><h2>Nota / Faktur (simulasi Layanan lokal)</h2>'
                . '<p>Invoice: <strong>' . html_escape($invoice) . '</strong></p>'
                . '<p>Ini nota tiruan dari emulator Layanan lokal (mode pengembangan). Tak ada faktur nyata.</p>'
                . '</body></html>'
            );
    }

    /**
     * Token Bearer dari header Authorization (kosong bila absen).
     */
    private function tokenBearer(): string
    {
        $header = (string) ($this->input->get_request_header('Authorization', true) ?? '');

        if (stripos($header, 'Bearer ') === 0) {
            return trim(substr($header, 7));
        }

        return (string) setting('layanan_opendesa_token');
    }

    /**
     * Kirim payload sebagai JSON (bentuk API Layanan).
     *
     * @param mixed $data
     */
    private function kirimJson($data): void
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output((string) json_encode($data));
    }
}
