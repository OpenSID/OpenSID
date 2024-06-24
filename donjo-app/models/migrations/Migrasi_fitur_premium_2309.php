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

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2309 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2308', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_23082351($hasil, $id);
            $hasil = $hasil && $this->migrasi_23082352($hasil, $id);
            $hasil = $hasil && $this->migrasi_23082353($hasil, $id);
            $hasil = $hasil && $this->migrasi_23082354($hasil, $id);
            $hasil = $hasil && $this->migrasi_23082355($hasil, $id);
            $hasil = $hasil && $this->migrasi_23082356($hasil, $id);
            $hasil = $hasil && $this->migrasi_23082357($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_23080851($hasil);
        $hasil = $hasil && $this->migrasi_23081451($hasil);
        $hasil = $hasil && $this->migrasi_23081452($hasil);
        $hasil = $hasil && $this->migrasi_23081551($hasil);
        $hasil = $hasil && $this->migrasi_23081651($hasil);
        $hasil = $hasil && $this->migrasi_23082151($hasil);
        $hasil = $hasil && $this->migrasi_23082456($hasil);

        return $hasil && $this->migrasi_23093151($hasil);
    }

    protected function migrasi_23080851($hasil)
    {
        $periksa = ['setting_aplikasi', 'setting_modul', 'user_grup', 'user', 'grup_akses', 'media_sosial', 'kehadiran_jam_kerja', 'ref_jabatan', 'klasifikasi_surat', 'anjungan_menu', 'gis_simbol', 'ref_syarat_surat', 'widget', 'tweb_surat_format', 'tweb_penduduk_umur', 'notifikasi', 'analisis_indikator', 'analisis_kategori_indikator', 'analisis_master', 'analisis_parameter', 'analisis_periode'];

        foreach ($periksa as $tabel) {
            if ($this->db->where('config_id', null)->get($tabel)->num_rows() > 0) {
                $hasil = $hasil && $this->db->where('config_id', null)->delete($tabel);
            }
        }

        return $hasil;
    }

    protected function migrasi_23081452($hasil)
    {
        return $hasil && $this->dbforge->modify_column('pembangunan', [
            'manfaat' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'lokasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    protected function migrasi_23081451($hasil)
    {
        DB::table('produk')->whereNull('potongan')->update(['potongan' => '0']);

        $this->db->query('ALTER TABLE produk MODIFY COLUMN potongan INTEGER(11) NOT NULL Default 0');

        return $hasil;
    }

    protected function migrasi_23081551($hasil)
    {
        $this->db
            ->where('key', 'rentang_waktu_kehadiran')
            ->group_start()
            ->where('value is null')
            ->or_where("value = ''")
            ->group_end()
            ->update('setting_aplikasi', ['value' => 10]);

        return $hasil;
    }

    protected function migrasi_23081651($hasil)
    {
        return $hasil && $this->db->where('key', 'warna_tema')->where('kategori !=', 'openkab')->update('setting_aplikasi', ['kategori' => 'openkab']);
    }

    protected function migrasi_23082151($hasil)
    {
        // Hapus pengaturan tgl_data_lengkap
        $this->db->delete('setting_aplikasi', ['key' => 'tgl_data_lengkap']);

        return $hasil;
    }

    private function update_parent_sub_modul($hasil, int $config_id, array $modul, string $parent, $hidden = [])
    {
        $parent_id = $this->db->select('id')->where('config_id', $config_id)->where('slug', $parent)->get('setting_modul')->row()->id;
        $max_urut  = $this->db->select('urut')->where('config_id', $config_id)->where('parent', $parent_id)->order_by('urut', 'desc')->get('setting_modul')->row()->urut;

        foreach ($modul as $slug) {
            $update = [
                'parent' => $parent_id,
                'urut'   => $max_urut++,
            ];

            if (in_array($slug, $hidden)) {
                $update['hidden'] = 2;
            }

            $hasil = $hasil && $this->db->where('config_id', $config_id)->where('slug', $slug)->where('parent !=', $parent_id)->update('setting_modul', $update);
        }

        return $hasil;
    }

    protected function migrasi_23082351($hasil, $config_id)
    {
        // sub modul pemeteaan
        $modul = [
            'peta',
            'pengaturan-peta',
            'plan',
            'point',
            'garis',
            'line',
            'area',
            'polygon',
        ];

        return $hasil && $this->update_parent_sub_modul($hasil, $config_id, $modul, 'pemetaan');
    }

    protected function migrasi_23082352($hasil, $config_id)
    {
        // sub modul buku administrasi umum
        $modul = [
            'administrasi-umum',
            'administrasi-penduduk',
            'administrasi-pembangunan',
            'administrasi-keuangan',
            'arsip-desa',
        ];

        $hidden = [
            'buku-eskpedisi',
            'buku-lembaran-dan-berita-desa',
            'buku-tanah-kas-desa',
            'buku-tanah-di-desa',
            'buku-inventaris-dan-kekayaan-desa',
            'buku-mutasi-penduduk',
            'buku-rekapitulasi-jumlah-penduduk',
            'buku-penduduk-sementara',
            'buku-ktp-dan-kk',
            'buku-rencana-kerja-pembangunan',
            'bumindes-kegiatan-pembangunan',
            'bumindes-kader',
            'bumindes-hasil-pembangunan',
        ];

        return $hasil && $this->update_parent_sub_modul($hasil, $config_id, [...$modul, ...$hidden], 'buku-administrasi-desa', $hidden);
    }

    protected function migrasi_23082353($hasil, $config_id)
    {
        // sub modul kelompok
        $modul = [
            'kategori-kelompok',
            'log-penduduk',
        ];

        return $hasil && $this->update_parent_sub_modul($hasil, $config_id, $modul, 'kependudukan');
    }

    protected function migrasi_23082354($hasil, $config_id)
    {
        // sub modul lembaga
        $modul = ['kategori-lembaga'];

        return $hasil && $this->update_parent_sub_modul($hasil, $config_id, $modul, 'info-desa');
    }

    protected function migrasi_23082355($hasil, $config_id)
    {
        // sub modul inventaris
        $modul = [
            'informasi-publik',
            'inventaris-asset',
            'inventaris-gedung',
            'inventaris-jalan',
            'inventaris-kontruksi',
            'inventaris-peralatan',
            'api-inventaris-asset',
            'api-inventaris-gedung',
            'api-inventaris-gedung-1',
            'api-inventaris-jalan',
            'api-inventaris-kontruksi',
            'api-inventaris-peralatan',
            'api-inventaris-tanah',
            'laporan-inventaris',
        ];

        return $hasil && $this->update_parent_sub_modul($hasil, $config_id, $modul, 'sekretariat');
    }

    protected function migrasi_23082356($hasil, $config_id)
    {
        // sub modul kategori
        $modul = [
            'pengaturan-web',
            'kategori',
        ];

        return $hasil && $this->update_parent_sub_modul($hasil, $config_id, $modul, 'admin-web');
    }

    protected function migrasi_23082357($hasil, $config_id)
    {
        // sub modul kategori
        $modul = [
            'pengaturan-grup',
        ];

        return $hasil && $this->update_parent_sub_modul($hasil, $config_id, $modul, 'pengaturan');
    }

    protected function migrasi_23082456($hasil)
    {
        // Perbaiki keterangan telegram
        $this->db->where('key', 'telegram_token')->update('setting_aplikasi', ['keterangan' => 'Telegram token']);
        $this->db->where('key', 'telegram_user_id')->update('setting_aplikasi', ['keterangan' => 'Telegram user id untuk notifikasi ke pengguna']);

        return $hasil;
    }

    protected function migrasi_23093151($hasil)
    {
        return $hasil && $this->dbforge->modify_column('log_tte', [
            'message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }
}
