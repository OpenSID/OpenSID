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

class Ekspor_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
    }

    /** ==================================================================================
     * expor ke format Excel yang bisa diimpor mempergunakan Import Excel
     * Tabel: dari tweb_wil_clusterdesa, c; tweb_keluarga, k; tweb_penduduk:, p
     * Kolom: c.dusun,c.rw,c.rt,p.nama,k.no_kk,p.nik,p.sex,p.tempatlahir,p.tanggallahir,p.agama_id,p.pendidikan_kk_id,p.pendidikan_sedang_id,p.pekerjaan_id,p.status_kawin,p.kk_level,p.warganegara_id,p.nama_ayah,p.nama_ibu,p.golongan_darah_id
     *
     * @param mixed $str
     * @param mixed $key
     */
    private function bersihkanData(&$str, $key): void
    {
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
        // Kode yang tersimpan sebagai '0' harus '' untuk dibaca oleh Import Excel
        $kecuali = ['nik', 'no_kk'];
        if ($str != '0') {
            return;
        }
        if (in_array($key, $kecuali)) {
            return;
        }
        $str = '';
    }

    // Expor data penduduk ke format Impor Excel
    public function expor($huruf = null)
    {
        $filter = $this->config_id('p')
            ->select(['k.alamat', 'c.dusun', 'c.rw', 'c.rt', 'p.nama', 'k.no_kk', 'p.nik', 'p.sex', 'p.tempatlahir', 'p.tanggallahir', 'p.agama_id', 'p.pendidikan_kk_id', 'p.pendidikan_sedang_id', 'p.pekerjaan_id', 'p.status_kawin', 'p.kk_level', 'p.warganegara_id', 'p.nama_ayah', 'p.nama_ibu', 'p.golongan_darah_id', 'p.akta_lahir', 'p.dokumen_pasport', 'p.tanggal_akhir_paspor', 'p.dokumen_kitas', 'p.ayah_nik', 'p.ibu_nik', 'p.akta_perkawinan', 'p.tanggalperkawinan', 'p.akta_perceraian', 'p.tanggalperceraian', 'p.cacat_id', 'p.cara_kb_id', 'p.hamil', 'p.id', 'p.foto', 'p.ktp_el', 'p.status_rekam', 'p.alamat_sekarang', 'p.status_dasar', 'p.suku', 'p.tag_id_card', 'p.id_asuransi as asuransi', 'p.no_asuransi', 'm.lat', 'm.lng'])
            ->from('tweb_penduduk p')
            ->join('tweb_keluarga k', 'k.id = p.id_kk', 'left')
            ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
            ->join('tweb_penduduk_map m', 'p.id = m.id', 'left')
            ->order_by('k.no_kk ASC', 'p.kk_level ASC');

        if ($this->session->filter) {
            $this->db->where('p.status', $this->session->filter);
        }

        if ($this->session->status_dasar) {
            $this->db->where('p.status_dasar', $this->session->status_dasar);
        }

        if ($this->session->sex) {
            $this->db->where('p.sex', $this->session->sex);
        }

        if ($this->session->dusun) {
            $this->db->where('c.dusun', $this->session->dusun);
        }

        if ($this->session->rw) {
            $this->db->where('c.rw', $this->session->rw);
        }

        if ($this->session->rt) {
            $this->db->where('c.rt', $this->session->rt);
        }

        $data    = $filter->get()->result();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $baris = $data[$i];
            array_walk($baris, fn (&$str, $key) => $this->bersihkanData($str, $key));
            if (! empty($baris->tanggallahir)) {
                $baris->tanggallahir = date_format(date_create($baris->tanggallahir), 'Y-m-d');
            }
            if (! empty($baris->tanggalperceraian)) {
                $baris->tanggalperceraian = date_format(date_create($baris->tanggalperceraian), 'Y-m-d');
            }
            if (! empty($baris->tanggalperkawinan)) {
                $baris->tanggalperkawinan = date_format(date_create($baris->tanggalperkawinan), 'Y-m-d');
            }
            if (! empty($baris->tanggal_akhir_paspor)) {
                $baris->tanggal_akhir_paspor = date_format(date_create($baris->tanggal_akhir_paspor), 'Y-m-d');
            }
            if (empty($baris->dusun)) {
                $baris->dusun = '-';
            }
            if (empty($baris->rt)) {
                $baris->rt = '-';
            }
            if (empty($baris->rw)) {
                $baris->rw = '-';
            }

            if ($huruf) {
                $baris = $this->ekspor_huruf($baris);
            }

            $data[$i] = $baris;
        }

        return $data;
    }

    private function ekspor_huruf(&$baris)
    {
        $baris->sex                  = DB::table('tweb_penduduk_sex')->find($baris->sex)->nama;
        $baris->agama_id             = DB::table('tweb_penduduk_agama')->find($baris->agama_id)->nama;
        $baris->pendidikan_kk_id     = DB::table('tweb_penduduk_pendidikan_kk')->find($baris->pendidikan_kk_id)->nama;
        $baris->pendidikan_sedang_id = DB::table('tweb_penduduk_pendidikan')->find($baris->pendidikan_sedang_id)->nama;
        $baris->pekerjaan_id         = DB::table('tweb_penduduk_pekerjaan')->find($baris->pekerjaan_id)->nama;
        $baris->status_kawin         = DB::table('tweb_penduduk_kawin')->find($baris->status_kawin)->nama;
        $baris->kk_level             = DB::table('tweb_penduduk_hubungan')->find($baris->kk_level)->nama;
        $baris->warganegara_id       = DB::table('tweb_penduduk_warganegara')->find($baris->warganegara_id)->nama;
        $baris->golongan_darah_id    = DB::table('tweb_golongan_darah')->find($baris->golongan_darah_id)->nama;
        $baris->cacat_id             = DB::table('tweb_cacat')->find($baris->cacat_id)->nama;
        $baris->cara_kb_id           = DB::table('tweb_cara_kb')->find($baris->cara_kb_id)->nama;
        $baris->status_dasar         = DB::table('tweb_penduduk_status')->find($baris->status_dasar)->nama;
        $baris->warganegara_id       = DB::table('tweb_penduduk_warganegara')->find($baris->warganegara_id)->nama;
        $baris->hamil                = $baris->hamil == 1 ? 'YA' : 'TIDAK';
        $baris->ktp_el               = DB::table('tweb_penduduk_warganegara')->find($baris->warganegara_id)->nama;
        $baris->status_rekam         = DB::table('tweb_penduduk_warganegara')->find($baris->warganegara_id)->nama;

        return $baris;
    }

    // ====================== End expor_by_keluarga ========================

    private function do_backup(array $prefs)
    {
        $this->load->dbutil();
        $backup = &$this->dbutil->backup($prefs);

        return $backup;
    }

    /*
        Backup menggunakan CI dilakukan per table. Tidak memperhatikan relational constraint antara table. Jadi perlu disesuaikan supaya bisa di-impor menggunakan
        Database > Backup/Restore > Restore atau menggunakan phpmyadmin.

        TODO: cari cara backup yang menghasilkan .sql seperti menu expor di phpmyadmin.
    */
    public function backup(): void
    {
        if (setting('multi_desa')) {
            session_error('Backup database tidak diizinkan');

            redirect('database');
        }

        // Tabel dengan foreign key dan
        // semua views ditambah di belakang.
        $views = $this->database_model->get_views();

        $prefs = [
            'format' => 'sql',
            'tables' => [],
            'ignore' => $views,
        ];
        $tabelBackup = $this->do_backup($prefs);

        $prefs = [
            'format'     => 'sql',
            'tables'     => $views,
            'add_drop'   => false,
            'add_insert' => false,
        ];
        $create_views = $this->do_backup($prefs);

        $backup = '';

        // Hapus semua views dulu
        foreach ($views as $view) {
            $backup .= 'DROP VIEW IF EXISTS ' . $view . ";\n";
        }

        // Hapus tabel dgn foreign key
        // $allTables = $this->db->list_tables();
        // foreach ($allTables as $table) {
        //     $backup .= 'DROP TABLE IF EXISTS ' . $table . ";\n";
        // }

        $backup .= $tabelBackup;
        $backup .= $create_views;

        // Hilangkan ketentuan user dan baris-baris lain yang
        // dihasilkan oleh dbutil->backup untuk view karena bermasalah
        // pada waktu import dgn restore ataupun phpmyadmin
        $backup = $this->ketentuan_backup_restore($backup);

        $db_name = 'backup-on-' . date('Y-m-d-H-i-s') . '.sql';
        $save    = base_url($db_name);

        $this->load->helper('file');
        write_file($save, $backup);
        $this->load->helper('download');
        force_download($db_name, $backup);

        $_SESSION['success'] = $backup ? 1 : -1;
    }

    public function restore()
    {
        if (setting('multi_desa')) {
            session_error('Restore database tidak diizinkan');

            redirect('database');
        }

        $this->load->library('MY_Upload', null, 'upload');
        $this->uploadConfig = [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'sql', // File sql terdeteksi sebagai text/plain
            'file_ext'      => 'sql',
            'max_size'      => max_upload() * 1024,
            'cek_script'    => false,
        ];
        $this->upload->initialize($this->uploadConfig);
        // Upload sukses
        if (! $this->upload->do_upload('userfile')) {
            $pesan = $this->upload->display_errors(null, null) . ': ' . $this->upload->file_type;

            session_error($pesan);
            set_session('error', $pesan);

            return false;
        }
        $uploadData = $this->upload->data();
        $filename   = $this->uploadConfig['upload_path'] . '/' . $uploadData['file_name'];

        return $this->proses_restore($filename);
    }

    public function proses_restore($filename = null)
    {
        if (! $filename) {
            return false;
        }

        $lines = file($filename);

        $versi = 0;

        foreach ($lines as $line) {
            if (strpos($line, 'current_version') !== false) {
                $line  = substr($line, strpos($line, 'current_version') + 19, 5);
                $versi = str_replace('.', '', $line);
                break;
            }
        }

        if ((int) $versi < (int) MINIMUM_VERSI) {
            $pesan = 'Versi OpenSID yang bisa di restore minimal backup dari v' . MINIMUM_VERSI;
            set_session('error', $pesan);
            log_message('error', $pesan);

            return false;
        }

        if (count($lines) < 20) {
            set_session('error', 'Sepertinya bukan file backup');

            return false;
        }

        $_SESSION['success'] = 1;
        // $this->drop_views();
        log_message('error', 'mulai hapus tabel awal');
        $this->drop_tables();
        log_message('error', 'selesai hapus tabel awal');
        $this->db->simple_query('SET FOREIGN_KEY_CHECKS=0');
        $query = '';

        foreach ($lines as $key => $sql_line) {
            // Abaikan baris apabila kosong atau komentar
            $sql_line = trim($sql_line);
            $sql_line = $this->ketentuan_backup_restore($sql_line);

            if ($sql_line != '' && (strpos($sql_line, '--') === false || strpos($sql_line, '--') != 0) && $sql_line[0] != '#') {
                $query .= $sql_line;
                if (substr(rtrim($query), -1) == ';') {
                    $result = $this->db->simple_query($query);
                    if (! $result) {
                        $_SESSION['success'] = -1;
                        $error               = $this->db->error();
                        log_message('error', '<br><br>[' . $key . ']>>>>>>>> Error: ' . $query . '<br>');
                        log_message('error', $error['message'] . '<br>'); // (mysql_error equivalent)
                        log_message('error', $error['code'] . '<br>'); // (mysql_errno equivalent)
                    }
                    $query = '';
                }
            }
        }
        $this->db->simple_query('SET FOREIGN_KEY_CHECKS=1');
        $this->perbaiki_collation();

        $this->load->helper('directory');

        // Hapus isi folder desa/cache
        $dir = config_item('cache_path');

        foreach (directory_map($dir) as $file) {
            if ($file !== 'index.html') {
                unlink($dir . DIRECTORY_SEPARATOR . $file);
            }
        }

        // ganti isi file app_key dengan config yang baru sesuai dengan database yang di restore
        $app_key = Config::first()->app_key;
        if (empty($app_key)) {
            $app_key = set_app_key();
            Config::first()->update(['app_key' => $app_key]);
        }

        file_put_contents(DESAPATH . 'app_key', $app_key);
        // enkripsi ulang password menggunakan appkey baru
        updateConfigFile('password', encrypt($this->db->password));
        // reset cache blade
        kosongkanFolder(config_item('cache_blade'));
        cache()->flush();
        session_destroy();

        return true;
    }

    private function drop_tables(): void
    {
        $this->db->simple_query('SET FOREIGN_KEY_CHECKS=0');
        $db    = $this->db->database;
        $sql   = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$db}'";
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        foreach ($data as $dat) {
            $tbl = $dat['TABLE_NAME'];
            $this->db->simple_query('DROP TABLE ' . $tbl);
        }
        $this->db->simple_query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function perbaiki_collation(): void
    {
        $list = $this->db
            ->select(
                "
                concat(
                    'ALTER TABLE ',
                    TABLE_NAME,
                    ' CONVERT TO CHARACTER SET utf8 COLLATE {$this->db->dbcollat};'
                ) as execute
                "
            )
            ->from('INFORMATION_SCHEMA.TABLES')
            ->where([
                'TABLE_SCHEMA' => $this->db->database,
                'TABLE_TYPE'   => 'BASE TABLE',
                "TABLE_COLLATION != {$this->db->dbcollat}",
            ])
            ->get()
            ->result();

        if ($list) {
            foreach ($list as $script) {
                $this->db->query("{$script->execute}");
            }
        }
    }

    protected function ketentuan_backup_restore($ketentuan)
    {
        $ketentuan = preg_replace('/ALGORITHM=UNDEFINED DEFINER=.+SQL SECURITY DEFINER /', '', $ketentuan);
        $ketentuan = preg_replace('/ENGINE=MyISAM|ENGINE=MEMORY|ENGINE=CSV|ENGINE=ARCHIVE|ENGINE=MRG_MYISAM|ENGINE=BLACKHOLE|ENGINE=FEDERATED/', 'ENGINE=InnoDB', $ketentuan);

        return preg_replace("/COLLATE={$this->db->dbcollat}|COLLATE=cp850_general_ci|COLLATE=utf8mb4_general_ci|COLLATE=utf8mb4_unicode_ci|{$this->db->dbcollat};/", '', $ketentuan);
    }

    /**
     * Sinkronasi Data dan Foto Penduduk ke OpenDK.
     *
     * @return array
     */
    public function hapus_penduduk_sinkronasi_opendk()
    {
        $kode_desa = kode_wilayah(identitas()->kode_desa);

        $data_hapus = $this->config_id('p')
            ->select([
                "CONCAT('{$kode_desa}') as desa_id",
                'p.id_pend as id_pend_desa',
                'p.foto',
            ])
            ->from('log_hapus_penduduk p')
            ->get()
            ->result_array();

        $response['hapus_penduduk'] = $data_hapus;

        return $response;
    }

    public function tambah_penduduk_sinkronasi_opendk()
    {
        $data = $this->config_id('p')
            ->select(['k.alamat', 'c.dusun', 'c.rw', 'c.rt', 'p.nama', 'k.no_kk', 'p.nik', 'p.sex', 'p.tempatlahir', 'p.tanggallahir', 'p.agama_id', 'p.pendidikan_kk_id', 'p.pendidikan_sedang_id', 'p.pekerjaan_id', 'p.status_kawin', 'p.kk_level', 'p.warganegara_id', 'p.nama_ayah', 'p.nama_ibu', 'p.golongan_darah_id', 'p.akta_lahir', 'p.dokumen_pasport', 'p.tanggal_akhir_paspor', 'p.dokumen_kitas', 'p.ayah_nik', 'p.ibu_nik', 'p.akta_perkawinan', 'p.tanggalperkawinan', 'p.akta_perceraian', 'p.tanggalperceraian', 'p.cacat_id', 'p.cara_kb_id', 'p.hamil', 'p.id', 'p.foto', 'p.status_dasar', 'p.ktp_el', 'p.status_rekam', 'p.alamat_sekarang', 'p.created_at', 'p.updated_at'])
            ->from('tweb_penduduk p')
            ->join('tweb_keluarga k', 'k.id = p.id_kk', 'left')
            ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
            ->order_by('k.no_kk ASC', 'p.kk_level ASC')
            ->get()
            ->result();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $baris = $data[$i];
            array_walk($baris, fn (&$str, $key) => $this->bersihkanData($str, $key));
            if (! empty($baris->tanggallahir)) {
                $baris->tanggallahir = date_format(date_create($baris->tanggallahir), 'Y-m-d');
            }
            if (! empty($baris->tanggalperceraian)) {
                $baris->tanggalperceraian = date_format(date_create($baris->tanggalperceraian), 'Y-m-d');
            }
            if (! empty($baris->tanggalperkawinan)) {
                $baris->tanggalperkawinan = date_format(date_create($baris->tanggalperkawinan), 'Y-m-d');
            }
            if (! empty($baris->tanggal_akhir_paspor)) {
                $baris->tanggal_akhir_paspor = date_format(date_create($baris->tanggal_akhir_paspor), 'Y-m-d');
            }
            if (empty($baris->dusun)) {
                $baris->dusun = '-';
            }
            if (empty($baris->rt)) {
                $baris->rt = '-';
            }
            if (empty($baris->rw)) {
                $baris->rw = '-';
            }
            if (! empty($baris->foto)) {
                $baris->foto = 'kecil_' . $baris->foto;
            }
            $data[$i] = $baris;
        }

        return $data;
    }
}
