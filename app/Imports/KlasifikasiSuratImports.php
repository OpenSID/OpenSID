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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Imports;

use App\Models\KlasifikasiSurat;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;

class KlasifikasiSuratImports
{
    protected $path;
    protected $fields = [
        'kode',
        'nama',
        'uraian',
    ];

    // constructor with a parameter
    public function __construct($path = null)
    {
        $this->path = $path ?? DEFAULT_LOKASI_IMPOR . 'klasifikasi-surat.xlsx';
    }

    public function import(): bool
    {
        $configId = identitas('id');

        try {
            $dataImport = [];

            reset_auto_increment('klasifikasi_surat');

            (new FastExcel())->import($this->path, static function (array $line) use ($configId, &$dataImport): void {
                $dataUpdate = [
                    'kode'      => alfanumerik_titik($line['kode']),
                    'nama'      => alfa_spasi($line['nama']),
                    'uraian'    => strip_tags((string) $line['uraian']),
                    'config_id' => $configId,
                ];

                $dataImport[] = $dataUpdate;
            });

            KlasifikasiSurat::upsert($dataImport, ['kode', 'config_id']);
        } catch (Exception $e) {
            log_message('error', $e);

            return false;
        }

        return true;
    }
}
