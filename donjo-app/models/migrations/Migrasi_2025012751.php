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

use App\Models\Modul;
use App\Traits\Migrator;
use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025012751
{
    use Migrator;

    public function up()
    {
        $this->pengaturanJumlahAduan();
        $this->hapusCredentialOpenDK();
        $this->updateUrlArsipSuratDinas();
    }

    protected function pengaturanJumlahAduan()
    {
        $this->createSetting([
            'judul'      => 'Jumlah Aduan Pengguna',
            'key'        => 'jumlah_aduan_pengguna',
            'value'      => 1,
            'keterangan' => 'Jumlah aduan yang dapat dilakukan oleh satu pengguna dalam hari',
            'jenis'      => 'input-number',
            'attribute'  => null,
            'option'     => null,
            'attribute'  => json_encode([
                'class'       => 'required',
                'min'         => 1,
                'max'         => 10,
                'step'        => 1,
                'placeholder' => '1',
            ]),
            'kategori' => 'Pengaduan',
        ]);
    }

    public function hapusCredentialOpenDK()
    {
        SettingAplikasi::whereIn('key', ['api_opendk_password', 'api_opendk_user'])->delete();
    }

    public function updateUrlArsipSuratDinas()
    {
        Modul::where('slug', 'arsip-surat-dinas')->update(['url' => 'surat_dinas_arsip']);
    }
}
