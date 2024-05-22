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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dev extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024052151($hasil, $id);
        }
        $hasil = $hasil && $this->migrasi_2024051251($hasil);
        $hasil = $hasil && $this->migrasi_2024051252($hasil);

        return $hasil && true;
    }

    protected function migrasi_2024051251($hasil)
    {
        DB::table('analisis_master')->where('jenis', 1)->update(['jenis' => 2]);

        return $hasil;
    }

    protected function migrasi_2024051252($hasil)
    {
        DB::table('tweb_penduduk_umur')->where('nama', 'Di Atas 75 Tahun')->update(['nama' => '75 Tahun ke Atas']);

        return $hasil;
    }

    protected function migrasi_2024052151($hasil, $id)
    {
        $media_sosial = DB::table('media_sosial')
            ->where('config_id', $id)
            ->pluck('nama')->map(static fn ($item) => Str::slug($item))->toArray();

        $setting = DB::table('setting_aplikasi')
            ->where('config_id', $id)
            ->where('key', 'media_sosial_pemerintah_desa')
            ->first();

        $value  = json_decode($setting->value, true);
        $option = json_decode($setting->option, true);

        if (count($value) > count($media_sosial) || count($option) > count($media_sosial)) {
            $value  = array_values(array_filter(array_unique($value), static fn ($item) => in_array($item, $media_sosial)));
            $option = array_filter(array_unique($option, SORT_REGULAR), static fn ($item) => in_array($item['id'], $media_sosial));

            DB::table('setting_aplikasi')
                ->where('config_id', $id)
                ->where('key', 'media_sosial_pemerintah_desa')
                ->update([
                    'value'  => json_encode($value),
                    'option' => json_encode($option),
                ]);
        }

        return $hasil;
    }
}
