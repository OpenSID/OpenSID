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

namespace App\Exports;

use App\Models\FormatSurat;
use App\Models\SettingAplikasi;

class SuratLayananExport
{
    public function __construct(public $id)
    {
        $this->id = $id;
    }

    public function filename()
    {
        return 'template-surat-tinymce.json';
    }

    public function data()
    {
        $dataExport = FormatSurat::jenis(FormatSurat::TINYMCE)->whereIn('id', $this->id)->latest('id')->get();

        $setting_penduduk_luar = SettingAplikasi::where('key', 'form_penduduk_luar')->first()->value;
        $setting_penduduk_luar = json_decode($setting_penduduk_luar, true);
        $setting_penduduk_luar = collect($setting_penduduk_luar)->except([2, 3])->toArray();

        $dataExport = $dataExport->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'judul_surat', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa')->toArray())
            ->map(static function ($item) use ($setting_penduduk_luar) {
                $item['penduduk_luar'] = $setting_penduduk_luar;

                return $item;
            })
            ->toArray();

        return $dataExport;
    }

    public function download()
    {
        return app('ci')->output
            ->set_header("Content-Disposition: attachment; filename={$this->filename()}")
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($this->data(), JSON_PRETTY_PRINT));
    }
}
