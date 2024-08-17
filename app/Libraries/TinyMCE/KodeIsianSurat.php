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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\TinyMCE;

use App\Libraries\DateConv;

class KodeIsianSurat
{
    private $dataSurat;

    public function __construct($dataSurat)
    {
        $this->dataSurat = $dataSurat;
    }

    public static function get($dataSurat): array
    {
        return (new self($dataSurat))->kodeIsian();
    }

    public function kodeIsian(): array
    {
        $DateConv = new DateConv();

        return [
            [
                'judul' => 'Format Nomor Surat',
                'isian' => 'format_nomor_surat',
                'data'  => strtoupper(substitusiNomorSurat($this->dataSurat['no_surat'], $this->dataSurat['surat']['format_nomor_global'] ? setting('format_nomor_surat') : $this->dataSurat['surat']['format_nomor'])),
            ],
            [
                'judul' => 'Kode',
                'isian' => 'kode_surat',
                'data'  => $this->dataSurat['surat']['kode_surat'],
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Nomer',
                'isian'         => 'nomer_surat',
                'data'          => $this->dataSurat['no_surat'],
            ],
            [
                'judul' => 'Judul',
                'isian' => 'judul_surat',
                'data'  => $this->dataSurat['surat']['judul_surat'],
            ],
            [
                'judul' => 'Tanggal',
                'isian' => 'tgl_surat',
                'data'  => formatTanggal(date('Y-m-d')),
            ],
            [
                'judul' => 'Tanggal Hijri',
                'isian' => 'tgl_surat_hijrI',
                'data'  => $DateConv->HijriDateId('j F Y'),
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Tahun',
                'isian'         => 'tahuN',
                'data'          => $this->dataSurat['log_surat']['bulan'] ?? date('Y'),
            ],
            [
                'judul' => 'Bulan Romawi',
                'isian' => 'bulan_romawi',
                'data'  => bulan_romawi((int) ($this->dataSurat['log_surat']['bulan'] ?? date('m'))),
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Logo Surat',
                'isian'         => 'logo',
                'data'          => '[logo]',
            ],
            [
                'case_sentence' => true,
                'judul'         => 'QRCode',
                'isian'         => 'qr_code',
                'data'          => '[qr_code]',
            ],
            [
                'case_sentence' => true,
                'judul'         => 'QRCode BSrE',
                'isian'         => 'qr_bsre',
                'data'          => '[qr_bsre]',
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Logo BSrE',
                'isian'         => 'logo_bsre',
                'data'          => '[logo_bsre]',
            ],
        ];
    }
}
