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

use App\Models\AnggotaGrup;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2205 extends MY_model
{
    protected $grup_kontak = [];

    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2204');
        $hasil = $hasil && $this->pengaturanStatusDesa($hasil);
        $hasil = $hasil && $this->pengaturanDataLengkap($hasil);
        $hasil = $hasil && $this->ubahKolomNama($hasil);
        $hasil = $hasil && $this->pantauWarga($hasil);
        $hasil = $hasil && $this->modulPesanOpenDK($hasil);
        $hasil = $hasil && $this->tambahkanModulHubungWarga($hasil);
        $hasil = $hasil && $this->hapusTabelTidakDigunakan($hasil);
        $hasil = $hasil && $this->pebaikiNotifikasi($hasil);

        return $hasil && $this->migrasi_2022042751($hasil);
    }

    protected function migrasi_2022042751($hasil)
    {
        // Hapus key covid_data dan provinsi_covid
        return $hasil && $this->db
            ->where_in('key', ['covid_data', 'provinsi_covid'])
            ->delete('setting_aplikasi');
    }

    protected function pengaturanStatusDesa($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'tahun_idm',
            'value'      => date('Y'),
            'keterangan' => 'Default tahun IDM saat pertamakali dibuka',
            'kategori'   => 'status desa',
        ]);
    }

    protected function pengaturanDataLengkap($hasil)
    {
        return $hasil && $this->db
            ->where_in('key', ['tgl_data_lengkap', 'tgl_data_lengkap_aktif'])
            ->update('setting_aplikasi', ['kategori' => 'data_lengkap']);
    }

    protected function ubahKolomNama($hasil)
    {
        if ($this->db->field_exists('nama', 'analisis_klasifikasi')) {
            $fields = [
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('analisis_klasifikasi', $fields);
        }

        return $hasil;
    }

    protected function pantauWarga($hasil)
    {
        if (! $this->db->field_exists('pantau', 'covid19_pemudik')) {
            $fields = [
                'pantau' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 1,
                    'after'      => 'id_terdata',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('covid19_pemudik', $fields);
        }

        return $hasil;
    }

    protected function tambahkanModulHubungWarga($hasil)
    {
        $hasil = $hasil && $this->settingModul($hasil);
        $hasil = $hasil && $this->modulHubungWarga($hasil);
        $hasil = $hasil && $this->hubungWarga($hasil);
        $hasil = $hasil && $this->pengaturanHubungWarga($hasil);
        $hasil = $hasil && $this->kontakHubungWarga($hasil);
        $hasil = $hasil && $this->modulGrupKontak($hasil);
        $hasil = $hasil && $this->perbaikiDataTeleponPenduduk($hasil);
        $hasil = $hasil && $this->kirimPesanHubungWarga($hasil);

        return $hasil && $this->modulKirimPesan($hasil);
    }

    protected function settingModul($hasil)
    {
        // Ubah panjang integer pada kolom parent tabel setting_modul
        if ($this->db->field_exists('parent', 'setting_modul')) {
            $fields = [
                'parent' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'default'    => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('setting_modul', $fields);
        }

        return $hasil;
    }

    protected function modulHubungWarga($hasil)
    {
        // Ubah modul SMS jadi Hubung Warga
        return $hasil && $this->ubah_modul(10, [
            'modul' => 'Hubung Warga',
        ]);
    }

    protected function hubungWarga($hasil)
    {
        if (! $this->db->field_exists('hubung_warga', 'tweb_penduduk')) {
            $fields = [
                'hubung_warga' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                    'default'    => 'Telegram',
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', $fields);
            $hasil = $hasil && $this->tambahIndeks('tweb_penduduk', 'hubung_warga', 'INDEX');
        }

        return $hasil;
    }

    protected function pengaturanHubungWarga($hasil)
    {
        // Tambahkan Pengaturan Aktifkan SMS
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'aktifkan_sms',
            'value'      => 0,
            'keterangan' => 'Aktif / Non-aktifkan Kirim SMS ke Warga',
            'jenis'      => 'boolean',
            'kategori'   => 'hubung warga',
        ]);

        // Hapus modul Pengaturan SMS
        if ($this->db->table_exists('setting_sms')) {
            $balas = $this->db->get('setting_sms')->row();

            // Hapus modul Pengaturan SMS
            $this->db->where('id', 41)->delete('setting_modul');

            // Hapus tabel setting_sms
            $this->dbforge->drop_table('setting_sms');
        }

        // Pindahkan Pengaturan Pesan Balas Otomatis
        return $hasil && $this->tambah_setting([
            'key'        => 'hubung_warga_balas_otomatis',
            'value'      => $balas->autoreply_text ?? 'Terima kasih pesan Anda telah kami terima.',
            'keterangan' => 'Hubung warga isi pesan bawaan balas otomatis',
            'jenis'      => 'textarea',
            'kategori'   => 'hubung warga',
        ]);
    }

    protected function kontakHubungWarga($hasil)
    {
        // Ubah modul link modul Daftar Kontak
        $hasil = $hasil && $this->ubah_modul(40, [
            'url' => 'daftar_kontak',
        ]);

        // Pindahkan no HP yang ada di tabel kontak ke tabel tweb_penduduk
        if ($this->db->field_exists('id_kontak', 'kontak')) {
            $dataKontak = $this->db->get('kontak')->result();

            // Simpan data sementra kotak penduduk jika terdaftar pada grup
            $this->grup_kontak = $this->db
                ->select('agk.id_grup_kontak, agk.id_grup, agk.id_kontak AS id_penduduk')
                ->join('kontak k', 'k.id_kontak = agk.id_kontak', 'LEFT')
                ->from('anggota_grup_kontak agk')
                ->get()
                ->result_array();

            if ($dataKontak) {
                foreach ($dataKontak as $data) {
                    // Pindahkan data kontak yang ada sebelumnya ke tweb_penduduk dengan asumsi, kontak sms (ada no telepon) yang akurat/sering digunakan
                    $hasil = $hasil && $this->db->where('id', $data->id_pend)->update('tweb_penduduk', ['telepon' => $data->no_hp]);

                    // Hapus data dengan id_kontak yang sudah dipindahkan ke tweb_penduduk
                    $hasil = $hasil && $this->db->where('id_kontak', $data->id_kontak)->delete('kontak');
                }
            }
        }

        // Hapus kolom id_pend tabel kontak
        if ($this->db->field_exists('id_pend', 'kontak')) {
            $this->db->simple_query('SET FOREIGN_KEY_CHECKS=0');
            $hasil = $hasil && $this->hapus_foreign_key('tweb_penduduk', 'kontak_ke_tweb_penduduk', 'kontak');
            $hasil = $hasil && $this->hapus_indeks('kontak', 'kontak_ke_tweb_penduduk');
            $hasil = $hasil && $this->dbforge->drop_column('kontak', 'id_pend');
            $this->db->simple_query('SET FOREIGN_KEY_CHECKS=1');
        }

        $hasil = $hasil && $this->db->query('DROP VIEW IF EXISTS `daftar_kontak`');
        $hasil = $hasil && $this->db->query('DROP VIEW IF EXISTS `daftar_anggota_grup`');
        $hasil = $hasil && $this->db->query('DROP VIEW IF EXISTS `daftar_grup`');

        // Ganti kolom no_hp pada tabel kontak
        if ($this->db->field_exists('no_hp', 'kontak')) {
            $hasil && $this->dbforge->modify_column('kontak', [
                'no_hp' => [
                    'name'       => 'telepon',
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'unique'     => true,
                    'null'       => true,
                ],
            ]);
        }

        // Tambahkan kolom telegram, email dan cara_hubung
        $fields = [];
        if (! $this->db->field_exists('nama', 'kontak')) {
            $fields['nama'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'after'      => 'id_kontak',
            ];
        }

        if (! $this->db->field_exists('email', 'kontak')) {
            $fields['email'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('telegram', 'kontak')) {
            $fields['telegram'] = [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('hubung_warga', 'kontak')) {
            $fields['hubung_warga'] = [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'Telegram',
            ];
        }

        if (! $this->db->field_exists('keterangan', 'kontak')) {
            $fields['keterangan'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('kontak', $fields);
        }

        return $hasil && $this->timestamps('kontak');
    }

    protected function modulGrupKontak($hasil)
    {
        $hasil = $hasil && $this->tambah_modul([
            'id'         => '345',
            'modul'      => 'Grup Kontak',
            'url'        => 'grup_kontak',
            'aktif'      => '1',
            'ikon'       => 'fa fa-list',
            'urut'       => '2',
            'level'      => '2',
            'parent'     => '10',
            'hidden'     => '2',
            'ikon_kecil' => 'fa fa-list',
        ]);

        if (! $this->db->field_exists('keterangan', 'kontak_grup')) {
            $hasil = $hasil && $this->dbforge->add_column('kontak_grup', [
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }

        $hasil = $hasil && $this->timestamps('kontak_grup');

        if ($this->db->field_exists('id_kontak', 'anggota_grup_kontak')) {
            $hasil && $this->dbforge->modify_column('anggota_grup_kontak', [
                'id_kontak' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);
        }

        if (! $this->db->field_exists('id_penduduk', 'anggota_grup_kontak')) {
            $hasil = $hasil && $this->dbforge->add_column('anggota_grup_kontak', [
                'id_penduduk' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);

            $hasil && $this->tambahIndeks('anggota_grup_kontak', 'id_penduduk', 'INDEX');
            $hasil && $this->tambahForeignKey('anggota_grup_kontak_id_penduduk_fk', 'anggota_grup_kontak', 'id_penduduk', 'tweb_penduduk', 'id');

            // Kembalikan data grup kontak yang lama
            if ($this->grup_kontak) {
                $hasil && AnggotaGrup::insert($this->grup_kontak);
            }
        }

        return $hasil && $this->timestamps('anggota_grup_kontak');
    }

    protected function perbaikiDataTeleponPenduduk($hasil)
    {
        $this->db->where('telepon', '')->update('tweb_penduduk', ['telepon' => null]);

        return $hasil && true;
    }

    protected function kirimPesanHubungWarga($hasil)
    {
        // Tambahkan tabel hubung_warga
        if (! $this->db->table_exists('hubung_warga')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'id_grup' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'subjek' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'isi' => [
                    'type' => 'TEXT',
                    'null' => false,
                ],
            ];
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('hubung_warga', true);
            $hasil = $hasil && $this->timestamps('hubung_warga', true);
            $hasil = $hasil && $this->tambahForeignKey('hubung_warga_id_grup_fk', 'hubung_warga', 'id_grup', 'kontak_grup', 'id_grup');
        }

        return $hasil;
    }

    protected function modulKirimPesan($hasil)
    {
        // Ubah modul Sub Modul SMS jadi Kirim Pesan
        return $hasil && $this->ubah_modul(39, [
            'modul' => 'Kirim Pesan',
        ]);
    }

    protected function modulPesanOpenDK($hasil)
    {
        // Tambah modul
        $hasil = $hasil && $this->tambah_modul([
            'id'         => '343',
            'modul'      => 'OpenDK',
            'url'        => '',
            'aktif'      => '1',
            'ikon'       => 'fa-university',
            'urut'       => '124',
            'level'      => '2',
            'parent'     => '0',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-university',
        ]);

        $hasil = $hasil && $this->tambah_modul([
            'id'         => '344',
            'modul'      => 'Pesan',
            'url'        => 'opendk_pesan/clear',
            'aktif'      => '1',
            'ikon'       => 'fa-envelope',
            'urut'       => '124',
            'level'      => '2',
            'parent'     => '343',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-envelope',
        ]);

        // pindah modul sinkronisasi
        $hasil = $hasil && $this->ubah_modul(326, ['parent' => 343, 'urut' => 125]);

        // Buat tabel pesan
        if (! $this->db->table_exists('pesan')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => false,
                ],
                'judul' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'jenis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'sudah_dibaca' => [
                    'type'       => 'INT',
                    'constraint' => 2,
                    'default'    => 1,
                ],
                'diarsipkan' => [
                    'type'       => 'INT',
                    'constraint' => 2,
                    'default'    => 0,
                ],
                'created_at' => [
                    'type' => 'timestamp',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'timestamp',
                    'null' => true,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->add_key('id', true);
            $hasil = $hasil && $this->dbforge->create_table('pesan', true);
        }

        if (! $this->db->table_exists('pesan_detail')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => false,
                ],
                'pesan_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'text' => [
                    'type' => 'TEXT',
                ],
                'pengirim' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'nama_pengirim' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'timestamp',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'timestamp',
                    'null' => true,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->add_key('id', true);
            $hasil = $hasil && $this->dbforge->create_table('pesan_detail', true);
        }

        return $hasil;
    }

    protected function hapusTabelTidakDigunakan($hasil)
    {
        $daftartabel = [
            'detail_log_penduduk',
            'klasifikasi_analisis_keluarga',
            'pertanyaan',
            'tweb_surat_atribut',
        ];

        foreach ($daftartabel as $tabel) {
            Schema::dropIfExists($tabel);
        }

        return $hasil;
    }

    protected function pebaikiNotifikasi($hasil)
    {
        return $hasil && $this->db->where('kode', 'persetujuan_penggunaan')->update('notifikasi', ['aksi' => 'notif/update_pengumuman,siteman/logout']);
    }
}
