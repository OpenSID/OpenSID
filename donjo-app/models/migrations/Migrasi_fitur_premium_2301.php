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

use App\Models\Area;
use App\Models\Garis;
use App\Models\LogSurat;
use App\Models\Lokasi;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2301 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2212');
        $hasil = $hasil && $this->migrasi_2022120651($hasil);
        $hasil = $hasil && $this->migrasi_2022121251($hasil);
        $hasil = $hasil && $this->migrasi_2022122151($hasil);
        $hasil = $hasil && $this->migrasi_2022122152($hasil);
        $hasil = $hasil && $this->migrasi_2022122153($hasil);
        $hasil = $hasil && $this->migrasi_2022122154($hasil);
        $hasil = $hasil && $this->migrasi_2022122751($hasil);
        $hasil = $hasil && $this->migrasi_2022122851($hasil);
        $hasil = $hasil && $this->migrasi_2022122852($hasil);
        $hasil = $hasil && $this->migrasi_2022123051($hasil);
        $hasil = $hasil && $this->migrasi_2022123052($hasil);
        $hasil = $hasil && $this->migrasi_2022123053($hasil);

        return $hasil && true;
    }

    protected function migrasi_2022120651($hasil)
    {
        // Ubah Perdes menjadi Peraturan
        $this->db
            ->where([
                'id'   => 3,
                'nama' => 'Perdes',
            ])
            ->set('nama', 'Peraturan')
            ->update('ref_dokumen');

        return $hasil;
    }

    protected function migrasi_2022121251($hasil)
    {
        // Ubah panjang kolom judul 100 menjadi 200
        $fields = [
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('artikel', $fields);
    }

    protected function migrasi_2022122151($hasil)
    {
        $semua_foto = Area::pluck('foto')->toArray();

        foreach (get_filenames(LOKASI_FOTO_AREA, false, false) as $file) {
            if ($file == 'index.html' || in_array(str_replace(['kecil_', 'sedang_'], '', $file), $semua_foto)) {
                continue;
            }

            unlink(LOKASI_FOTO_AREA . $file);
        }

        return $hasil;
    }

    protected function migrasi_2022122152($hasil)
    {
        $semua_foto = Garis::pluck('foto')->toArray();

        foreach (get_filenames(LOKASI_FOTO_GARIS, false, false) as $file) {
            if ($file == 'index.html' || in_array(str_replace(['kecil_', 'sedang_'], '', $file), $semua_foto)) {
                continue;
            }

            unlink(LOKASI_FOTO_GARIS . $file);
        }

        return $hasil;
    }

    protected function migrasi_2022122153($hasil)
    {
        $hasil && $this->tambah_setting([
            'judul'      => 'Latar Login Mandiri',
            'key'        => 'latar_login_mandiri',
            'value'      => 'latar_login_mandiri.jpg',
            'keterangan' => 'Latar untuk Login Layanan Mandiri',
            'jenis'      => 'unggah',
            'kategori'   => 'latar',
        ]);

        return $hasil;
    }

    protected function migrasi_2022122154($hasil)
    {
        $semua_foto = Lokasi::pluck('foto')->toArray();

        foreach (get_filenames(LOKASI_FOTO_LOKASI, false, false) as $file) {
            if ($file == 'index.html' || in_array(str_replace(['kecil_', 'sedang_'], '', $file), $semua_foto)) {
                continue;
            }

            unlink(LOKASI_FOTO_LOKASI . $file);
        }

        return $hasil;
    }

    protected function migrasi_2022122751($hasil)
    {
        $hasil && $this->tambah_setting([
            'judul'      => 'Latar Website',
            'key'        => 'latar_website',
            'value'      => 'latar_website.jpg',
            'keterangan' => 'Latar untuk login ke halaman website',
            'jenis'      => 'unggah',
            'kategori'   => 'latar',
        ]);

        $hasil && $this->tambah_setting([
            'judul'      => 'Latar Login Admin',
            'key'        => 'latar_login',
            'value'      => 'latar_login.jpg',
            'keterangan' => 'Latar untuk login ke halaman admin',
            'jenis'      => 'unggah',
            'kategori'   => 'latar',
        ]);

        return $hasil;
    }

    protected function migrasi_2022122851($hasil)
    {
        if ($this->db->where('nama', 'Keputusan Kades')->get('ref_dokumen')->row()) {
            $hasil = $hasil && $this->db
                ->where('nama', 'Keputusan Kades')
                ->set('nama', 'Keputusan Kepala Desa')
                ->update('ref_dokumen');
        }

        return $hasil;
    }

    protected function migrasi_2022122852($hasil)
    {
        $check = $this->db
            ->where_in('nama', [
                'Jual Beli',
                'Hibah / Sumbangan',
                'Lain - lain',
            ])
            ->get('ref_asal_tanah_kas')
            ->result_array();

        if ($check) {
            $hasil = $hasil && $this->db->update('ref_asal_tanah_kas', ['nama' => 'APB Desa'], ['nama' => 'Jual Beli']);
            $hasil = $hasil && $this->db->update('ref_asal_tanah_kas', ['nama' => 'Perolehan Lainnya yang Sah'], ['nama' => 'Hibah / Sumbangan']);
            $hasil = $hasil && $this->db->update('ref_asal_tanah_kas', ['nama' => 'Kekayaan Asli Desa'], ['nama' => 'Lain - lain']);
        }

        return $hasil;
    }

    protected function migrasi_2022123051($hasil)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Inspect Element',
            'key'        => 'inspect_element',
            'value'      => 1,
            'keterangan' => 'Mengaktifkan inspect element pada halaman website',
            'jenis'      => 'boolean',
            'kategori'   => 'sistem',
        ]);
    }

    protected function migrasi_2022123052($hasil)
    {
        // Ganti status kehadiran dari 'keluar' menjadi 'tidak berada di kantor'
        DB::table('kehadiran_perangkat_desa')
            ->where('status_kehadiran', 'keluar')
            ->update(['status_kehadiran' => 'tidak berada di kantor']);

        return $hasil;
    }

    protected function migrasi_2022123053($hasil)
    {
        if (! $this->db->field_exists('nama_jabatan', 'log_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('log_surat', [
                'nama_jabatan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'id_pamong',
                ],
            ]);
        }

        // ganti nama pamong yang masih null
        $check = LogSurat::whereNull('nama_jabatan')->get();

        foreach ($check as $surat) {
            $surat->nama_jabatan = $surat->pamong->jabatan->nama;
            $hasil               = $surat->save();
        }

        return $hasil;
    }
}
