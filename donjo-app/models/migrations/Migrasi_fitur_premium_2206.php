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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2206 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2205');

        $hasil = $hasil && $this->migrasi_2022050951($hasil);
        $hasil = $hasil && $this->migrasi_2022051051($hasil);
        $hasil = $hasil && $this->migrasi_2022051171($hasil);
        $hasil = $hasil && $this->migrasi_2022051271($hasil);
        $hasil = $hasil && $this->migrasi_2022051371($hasil);
        $hasil = $hasil && $this->migrasi_2022052451($hasil);
        $hasil = $hasil && $this->migrasi_2022052571($hasil);
        $hasil = $hasil && $this->migrasi_2022052771($hasil);

        return $hasil && $this->migrasi_2022053051($hasil);
    }

    protected function migrasi_2022050951($hasil)
    {
        $parrent = $this->db->select('parrent')->like('link', 'https://berputar.opendesa.id/')->get('menu')->row()->parrent;

        if ($parrent) {
            $hasil = $hasil && $this->db->where('id', $parrent)->delete('menu');
        }

        return $hasil && $this->db->like('link', 'https://berputar.opendesa.id/')->delete('menu');
    }

    protected function migrasi_2022051051($hasil)
    {
        return $hasil && $this->dbforge->modify_column('keuangan_ta_spj_sisa', [
            'keterangan' => ['type' => 'text', 'null' => true],
        ]);
    }

    protected function migrasi_2022051171($hasil)
    {
        // Tambahkan Pengaturan latar kehadiran
        return $hasil && $this->tambah_setting([
            'key'        => 'latar_kehadiran',
            'value'      => null,
            'keterangan' => 'Latar Kehadiran',
            'jenis'      => 'unggah',
            'kategori'   => 'kehadiran',
        ]);
    }

    protected function migrasi_2022051271($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'id_pengunjung_kehadiran',
            'value'      => '',
            'keterangan' => 'ID Pengunjung Perangkat Kehadiran',
            'jenis'      => null,
            'kategori'   => 'kehadiran',
        ]);
    }

    protected function migrasi_2022051371($hasil)
    {
        $hasil = $hasil && $this->hapusFileQrCode($hasil);

        return $hasil && $this->perbaikiTabelUrls($hasil);
    }

    protected function hapusFileQrCode($hasil)
    {
        $log_surat = $this->db->get('log_surat')->result();

        if ($log_surat) {
            foreach ($log_surat as $log) {
                unlink(LOKASI_MEDIA . pathinfo($log->nama_surat, PATHINFO_FILENAME) . '.png');
            }
        }

        return $hasil;
    }

    protected function perbaikiTabelUrls($hasil)
    {
        // Tambahkan kolom urls_id pada tabel log_surat
        if (! $this->db->field_exists('urls_id', 'log_surat')) {
            $fields = [
                'urls_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unique'     => true,
                    'null'       => true,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        $dataUrl = $this->db->get('urls')->result();

        if ($dataUrl) {
            foreach ($dataUrl as $data) {
                $urlsId = str_replace([site_url('c1/'), 'index.php'], '', $data->url);
                $hasil  = $hasil && $this->db->where('id', $urlsId)->update('log_surat', ['urls_id' => $data->id]);
            }
        }

        return $hasil;
    }

    protected function migrasi_2022052451($hasil)
    {
        // Hapus token_opensid; cukup gunakan token_pantau
        return $hasil && $this->db->where('key', 'token_opensid')->delete('setting_aplikasi');
    }

    protected function migrasi_2022052571($hasil)
    {
        if (! $this->db->field_exists('logo_garuda', 'tweb_surat_format')) {
            $fields = [
                'logo_garuda' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022052771($hasil)
    {
        // Buat tabel log sinkronisasi
        if (! $this->db->table_exists('log_sinkronisasi')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'modul' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'unique'     => true,
                    'null'       => false,
                ],
            ];
            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('log_sinkronisasi', true);

            $hasil = $hasil && $this->timestamps('log_sinkronisasi', true);
        }

        $hasil = $hasil && $this->timestamps('program', true);

        return $hasil && $this->timestamps('program_peserta', true);
    }

    protected function migrasi_2022053051($hasil)
    {
        $result = $this->db->get_where('setting_aplikasi', [
            'key'   => 'sebutan_kepala_desa',
            'value' => 'Kepala',
        ])->row();

        if (! $result) {
            return $hasil;
        }

        return $hasil && $this->db->update(
            'setting_aplikasi',
            ['value' => 'Kepala Desa'],
            ['key'   => 'sebutan_kepala_desa']
        );
    }
}
