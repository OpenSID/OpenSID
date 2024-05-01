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

use App\Models\Config;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2311 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2310', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2023101151($hasil);
        $hasil = $hasil && $this->migrasi_2023101352($hasil);

        return $hasil && $this->migrasi_2023102551($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023101152($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023101351($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023101254($hasil);

        return $hasil && $this->migrasi_2023101651($hasil);
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }

    protected function migrasi_2023101151($hasil)
    {
        if (! $this->db->field_exists('slug', 'program')) {
            $fields = [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'after'      => 'nama',
                    'unique'     => true,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('program', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2023101152($hasil, $id)
    {
        if ($data_program = $this->db->where('config_id', $id)->get('program')->result_array()) {
            foreach ($data_program as $program) {
                $slug  = unique_slug('program', $program['nama'], $program['id'], 'slug', '-', $id);
                $hasil = $hasil && $this->db->where('id', $program['id'])->where('config_id', $program['config_id'])->update('program', ['slug' => $slug]);
            }
        }

        // Buat index setelah tambah data slug, karena harus ada nilai.
        return $hasil && $this->buat_ulang_index('program', 'slug', '(`config_id`, `slug`)');
    }

    protected function migrasi_2023101351($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'nonaktifkan_rtf',
            'judul'      => 'Non Aktifkan Surat RTF',
            'value'      => 0,
            'keterangan' => 'Aktif / Non-aktifkan Surat RTF',
            'jenis'      => 'boolean',
            'kategori'   => 'pengaturan-surat',
        ], $id);
    }

    protected function migrasi_2023101352($hasil)
    {
        if (! $this->cek_indeks('kelompok_anggota', 'no_anggota_config')) {
            $hasil = $hasil && $this->db->query('ALTER TABLE `kelompok_anggota` ADD UNIQUE INDEX `no_anggota_config` (`config_id`, `id_kelompok`, `no_anggota`)');
        }

        return $hasil;
    }

    protected function migrasi_2023101254($hasil)
    {
        $config_id    = DB::table('config')->pluck('id')->toArray();
        $configEmail  = ['protocol' => 'smtp', 'smtp_host' => config_item('smtp_host'), 'smtp_user' => config_item('smtp_user'), 'smtp_pass' => config_item('smtp_pass'), 'smtp_port' => config_item('smtp_port')];
        $defaultEmail = ['protocol' => 'smtp', 'smtp_host' => '', 'smtp_user' => '', 'smtp_pass' => '', 'smtp_port' => ''];
        // tidak menggunakan function identitas karena cache identitas desa dihapus ketika memanggil tambah_setting
        $desaMigrasi = Config::appKey()->first();

        foreach ($config_id as $id) {
            $emailSetting = $defaultEmail;
            if ($desaMigrasi) {
                if ($desaMigrasi->id == $id) {
                    $emailSetting = $configEmail;
                }
            }
            $hasil = $hasil && $this->migrasi_2023101255($hasil, $emailSetting, $id);
        }

        return $hasil;
    }

    protected function migrasi_2023101255($hasil, $emailSetting, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'email_notifikasi',
            'judul'      => 'Email Notifikasi',
            'value'      => $emailSetting['smtp_host'] ? 1 : 0,
            'keterangan' => 'Aktif atau nonaktifkan notifikasi email',
            'jenis'      => 'boolean',
            'kategori'   => 'email',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'email_protocol',
            'judul'      => 'Email protokol',
            'value'      => $emailSetting['protocol'],
            'keterangan' => 'Email protokol, misal : SMTP',
            'jenis'      => 'text',
            'kategori'   => 'email',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'email_smtp_host',
            'judul'      => 'Email Host',
            'value'      => $emailSetting['smtp_host'],
            'keterangan' => 'Email host',
            'jenis'      => 'text',
            'kategori'   => 'email',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'email_smtp_user',
            'judul'      => 'Email Username',
            'value'      => $emailSetting['smtp_user'],
            'keterangan' => 'Email username',
            'jenis'      => 'text',
            'kategori'   => 'email',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'email_smtp_pass',
            'judul'      => 'Email Password',
            'value'      => $emailSetting['smtp_pass'],
            'keterangan' => 'Email password',
            'jenis'      => 'password',
            'kategori'   => 'email',
        ], $id);

        return $hasil && $this->tambah_setting([
            'key'        => 'email_smtp_port',
            'judul'      => 'Email Port',
            'value'      => $emailSetting['smtp_port'],
            'keterangan' => 'Email port',
            'jenis'      => 'text',
            'kategori'   => 'email',
        ], $id);
    }

    protected function migrasi_2023101651($hasil)
    {
        $this->db->trans_start();
        $query = $this->db->where('form_isian is NOT NULL')->get('tweb_surat_format');

        foreach ($query->result() as $row) {
            $data = json_decode($row->form_isian, true);
            if (array_key_exists('data', $data)) {
                $data_value = $data['data'];
                unset($data['data']);
            }
            $individu = empty($data['individu']) ? [] : $data['individu'];
            if (array_key_exists('data', $individu)) {
                continue;
            }
            $data['individu'] = [];
            if (array_key_exists('data_orang_tua', $data)) {
                $individu['data_orang_tua'] = $data['data_orang_tua'];
                unset($data['data_orang_tua']);
            }
            if (array_key_exists('data_pasangan', $data)) {
                $individu['data_pasangan'] = $data['data_pasangan'];
                unset($data['data_pasangan']);
            }
            $data['individu'] = ['data' => $data_value] + $individu;
            $this->db->update('tweb_surat_format', ['form_isian' => json_encode($data)], ['id' => $row->id]);
        }
        $this->db->trans_complete();

        return $hasil;
    }

    protected function migrasi_2023102551($hasil)
    {
        if ($this->db->field_exists('created_at', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', [
                'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
            ]);
        }

        if ($this->db->field_exists('updated_at', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', [
                'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ]);
        }

        return $hasil;
    }
}
