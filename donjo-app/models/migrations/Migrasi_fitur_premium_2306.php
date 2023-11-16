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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\FormatSurat;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2306 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2305');

        $hasil = $hasil && $this->migrasi_2023052351($hasil);
        $hasil = $hasil && $this->migrasi_2023052451($hasil);
        $hasil = $hasil && $this->migrasi_2023052551($hasil);
        $hasil = $hasil && $this->migrasi_2023052951($hasil);
        $hasil = $hasil && $this->migrasi_2023053051($hasil);
        $hasil = $hasil && $this->migrasi_2023053052($hasil);

        return $hasil && $this->migrasi_2023053053($hasil);
    }

    protected function migrasi_2023052351($hasil)
    {
        if (! $this->db
            ->where('id', 63)
            ->where('modul', 'Klasfikasi Surat')
            ->get('setting_modul')
            ->result()
        ) {
            return $hasil;
        }

        return $hasil && $this->ubah_modul(63, [
            'modul' => 'Klasifikasi Surat',
        ]);
    }

    protected function migrasi_2023052451($hasil)
    {
        $result = $this->db
            ->select('id')
            ->where('parrent !=', 0)
            ->where('parrent not in (select id from kategori where parrent = 0)')
            ->get('kategori')
            ->result_array();

        if ($result) {
            $hasil = $hasil && $this->db->where_in('id', collect($result)->pluck('id')->all())->delete('kategori');
        }

        return $hasil;
    }

    protected function migrasi_2023052551($hasil)
    {
        $surat = FormatSurat::select(['id', 'url_surat', 'kode_isian'])
            ->whereRaw("kode_isian LIKE '%rquired%'")
            ->where('jenis', FormatSurat::TINYMCE_SISTEM)
            ->get();

        foreach ($surat as $key => $value) {
            FormatSurat::whereId($value->id)
                ->update([
                    'kode_isian' => str_replace('rquired', 'required', json_encode($value->kode_isian)),
                ]);
        }

        return $hasil;
    }

    protected function migrasi_2023052951($hasil)
    {
        if (! $this->db->field_exists('No_RPJM', 'keuangan_ta_rpjm_visi')) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_rpjm_visi', [
                'No_RPJM' => [
                    'type'       => 'varchar',
                    'constraint' => 100,
                    'after'      => 'No_Visi',
                ],
                'Tgl_RPJM' => [
                    'type'       => 'varchar',
                    'constraint' => 100,
                    'after'      => 'No_RPJM',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2023053051($hasil)
    {
        return $hasil && $this->dbforge->modify_column('inventaris_gedung', [
            'kontruksi_bertingkat' => [
                'type'       => 'varchar',
                'constraint' => 255,
                'null'       => true,
            ],
            'harga' => [
                'type' => 'double',
                'null' => true,
            ],
        ]);
    }

    protected function migrasi_2023053052($hasil)
    {
        $this->db->where('slug', 'administrasi-keuangan')->delete('setting_modul');

        // Hapus cache menu navigasi
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    protected function migrasi_2023053053($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'rentang_waktu_kehadiran',
            'judul'      => 'Rentang Waktu Kehadiran',
            'value'      => '10',
            'jenis'      => 'text',
            'attribute'  => 'class="bilangan" placeholder="10"',
            'keterangan' => 'Rentang waktu kehadiran ketika keluar. (satuan: menit)',
            'kategori'   => 'kehadiran',
        ]);
    }
}
