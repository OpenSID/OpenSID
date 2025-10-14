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

use App\Enums\StatusEnum;
use App\Models\SettingAplikasi;
use App\Models\Widget;
use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->sesuaikanTanggalPengirimanBukuEkspedisi();
        $this->tambahWidgetProfilDesa();
        $this->sesuaikanPasportDanKitasNull();
        $this->perbaikiSyaratSuratPermohonanSurat();
        $this->perbaikiAksesWilayahUser();

        $this->perbaikiPengaturanJumlahHalamanArtikel();
    }

    public function sesuaikanTanggalPengirimanBukuEkspedisi()
    {
        DB::table('surat_keluar')
            ->where('config_id', identitas('id'))
            ->whereNull('tanggal_pengiriman')
            ->where('ekspedisi', 1)
            ->update(['tanggal_pengiriman' => DB::raw('updated_at')]);
    }

    public function tambahWidgetProfilDesa()
    {
        if (Widget::where('isi', 'profil_desa')->exists()) {
            return;
        }

        Widget::create([
            'isi'          => 'profil_desa',
            'enabled'      => StatusEnum::TIDAK,
            'judul'        => 'Profil [Desa]',
            'jenis_widget' => Widget::WIDGET_SISTEM,
            'form_admin'   => 'identitas_desa',
        ]);
    }

    public function sesuaikanPasportDanKitasNull()
    {
        $fields = ['dokumen_kitas', 'dokumen_pasport'];

        foreach ($fields as $field) {
            DB::table('tweb_penduduk')
                ->where(static function ($q) use ($field) {
                    $q->whereNull($field)
                        ->orWhere($field, '');
                })
                ->update([$field => '-']);
        }
    }

    public function perbaikiAksesWilayahUser()
    {
        require_once APPPATH . 'models/migrations/Migrasi_2024050171.php';

        (new Migrasi_2024050171())->migrasi_2024040451();
    }

    public function perbaikiSyaratSuratPermohonanSurat()
    {
        $permohonanSuratList = DB::table('permohonan_surat')
            ->select('id', 'syarat')
            ->whereNotNull('syarat')
            ->where('syarat', '!=', '')
            ->where('syarat', 'like', '"%')
            ->where('config_id', identitas('id'))
            ->get();

        foreach ($permohonanSuratList as $permohonan) {
            $firstDecode = json_decode($permohonan->syarat, true);

            if (is_string($firstDecode)) {
                $secondDecode = json_decode($firstDecode, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($secondDecode)) {
                    DB::table('permohonan_surat')
                        ->where('id', $permohonan->id)
                        ->where('config_id', identitas('id'))
                        ->update(['syarat' => json_encode($secondDecode, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
                }
            }
        }
    }

    public function perbaikiPengaturanJumlahHalamanArtikel()
    {
        SettingAplikasi::withoutGlobalScopes()
            ->where('key', 'web_artikel_per_page')
            ->where('jenis', '!=', 'input-number')
            ->update([
                'kategori'  => 'Website',
                'jenis'     => 'input-number',
                'attribute' => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ]),
            ]);
    }
}
