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

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class SuratDinasImports
{
    protected $path;

    public function __construct($path = null, protected $where = null)
    {
        $this->path = $path ?? DEFAULT_LOKASI_IMPOR . 'template-surat-dinas-tinymce.json';
    }

    public function import(): bool
    {
        $configId = $this->where['config_id'] ?? identitas('id');

        try {
            reset_auto_increment('surat_dinas');

            if (file_exists($this->path)) {
                $data = file_get_contents($this->path);
                $data = collect(json_decode($data, true));
                if ($this->where) {
                    $data = $data->where('url_surat', $this->where['url_surat']);
                }

                $data->each(static function (array $line) use ($configId): void {
                    $data = [
                        'config_id'           => $configId,
                        'nama'                => $line['nama'],
                        'url_surat'           => $line['url_surat'],
                        'kode_surat'          => $line['kode_surat'],
                        'lampiran'            => $line['lampiran'],
                        'jenis'               => $line['jenis'],
                        'masa_berlaku'        => $line['masa_berlaku'],
                        'satuan_masa_berlaku' => $line['satuan_masa_berlaku'],
                        'qr_code'             => $line['qr_code'],
                        'logo_garuda'         => $line['logo_garuda'],
                        'template'            => $line['template'],
                        'form_isian'          => $line['form_isian'],
                        'kode_isian'          => $line['kode_isian'],
                        'orientasi'           => $line['orientasi'],
                        'ukuran'              => $line['ukuran'],
                        'margin'              => $line['margin'],
                        'margin_global'       => $line['margin_global'],
                        'footer'              => $line['footer'],
                        'header'              => $line['header'],
                        'format_nomor'        => $line['format_nomor'],
                        'format_nomor_global' => $line['format_nomor_global'],
                        'created_at'          => Carbon::now(),
                        'created_by'          => auth()->id,
                        'updated_at'          => Carbon::now(),
                        'updated_by'          => auth()->id,
                    ];

                    $suratDinas = DB::table('surat_dinas')->where('config_id', $configId)->where('kode_surat', $line['kode_surat']);

                    if ($suratDinas->exists()) {
                        $suratDinas->update(['template' => $data['template']]);
                    } else {
                        $suratDinas->insert($data);
                    }
                });
            }
        } catch (Exception $e) {
            log_message('error', $e);

            return false;
        }

        return true;
    }
}
