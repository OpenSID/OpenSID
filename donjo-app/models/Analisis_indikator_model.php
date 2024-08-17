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

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_indikator_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('analisis_master_model');
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('pertanyaan', 'analisis_indikator');
    }

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari = $_SESSION['cari'];
            $kw   = $this->db->escape_like_str($cari);
            $kw   = '%' . $kw . '%';

            return " AND (u.pertanyaan LIKE '{$kw}' OR u.pertanyaan LIKE '{$kw}')";
        }
    }

    private function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $kf = $_SESSION['filter'];

            return " AND u.act_analisis = {$kf}";
        }
    }

    private function master_sql()
    {
        if (isset($_SESSION['analisis_master'])) {
            $kf = $_SESSION['analisis_master'];

            return " AND u.id_master = {$kf}";
        }
    }

    private function tipe_sql()
    {
        if (isset($_SESSION['tipe'])) {
            $kf = $_SESSION['tipe'];

            return " AND u.id_tipe = {$kf}";
        }
    }

    private function kategori_sql()
    {
        if (isset($_SESSION['kategori'])) {
            $kf = $_SESSION['kategori'];

            return " AND u.id_kategori = {$kf}";
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $sql = 'SELECT COUNT(id) AS id FROM analisis_indikator u WHERE u.config_id = ' . identitas('id');
        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();
        $sql .= $this->master_sql();
        $sql .= $this->tipe_sql();
        $sql .= $this->kategori_sql();
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        switch ($o) {
            case 1: $order_sql = ' ORDER BY LPAD(u.nomor, 10, " ")';
                break;

            case 2: $order_sql = ' ORDER BY LPAD(u.nomor, 10, " ") DESC';
                break;

            case 3: $order_sql = ' ORDER BY u.pertanyaan';
                break;

            case 4: $order_sql = ' ORDER BY u.pertanyaan DESC';
                break;

            case 5: $order_sql = ' ORDER BY u.id_kategori';
                break;

            case 6: $order_sql = ' ORDER BY u.id_kategori DESC';
                break;

            default:$order_sql = ' ORDER BY LPAD(u.nomor, 10, " ")';
        }

        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;
        $sql        = 'SELECT u.*,t.tipe AS tipe_indikator,k.kategori AS kategori FROM analisis_indikator u LEFT JOIN analisis_tipe_indikator t ON u.id_tipe = t.id LEFT JOIN analisis_kategori_indikator k ON u.id_kategori = k.id WHERE u.config_id = ' . identitas('id');

        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();
        $sql .= $this->master_sql();
        $sql .= $this->tipe_sql();
        $sql .= $this->kategori_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']           = $j + 1;
            $data[$i]['act_analisis'] = $data[$i]['act_analisis'] == 1 ? 'Ya' : 'Tidak';
            $j++;
        }

        return $data;
    }

    private function validasi_data($post)
    {
        $data = [
            'id_tipe'      => $post['id_tipe'],
            'referensi'    => $post['referensi'] ?? null,
            'nomor'        => nomor_surat_keputusan($post['nomor']),
            'pertanyaan'   => htmlentities($post['pertanyaan']),
            'id_kategori'  => $post['id_kategori'] ?? null,
            'bobot'        => bilangan($post['bobot']),
            'act_analisis' => $post['act_analisis'],
            'is_publik'    => $post['is_publik'],
        ];

        if ($data['id_tipe'] != 1) {
            $data['act_analisis'] = 2;
            $data['bobot']        = 0;
        }

        return $data;
    }

    public function insert(): void
    {
        // Analisis sistem tidak boleh diubah
        if ($this->analisis_master_model->is_analisis_sistem($this->session->analisis_master)) {
            return;
        }

        $data = $this->validasi_data($this->input->post());

        $data['id_master'] = $this->session->analisis_master;
        $data['config_id'] = identitas('id');
        $outp              = $this->db->insert('analisis_indikator', $data);
        $id                = $this->db->insert_id();

        // Tambahkan Isi dari pertanyaan berdasarkan referensi
        if ($id && $data['id_tipe'] == 1) {
            $referensi = $this->data_tabel($this->session->subjek_tipe)[$data['referensi']]['referensi'];
            if ($referensi) {
                foreach ($referensi as $value) {
                    $insert['kode_jawaban'] = bilangan($value['id']);
                    $insert['jawaban']      = htmlentities($value['nama']);
                    $insert['nilai']        = 1;
                    $this->p_insert($id, $insert);
                }
            }
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    private function update_indikator_sistem($id): void
    {
        // Hanya kolom yang boleh diubah untuk analisis sistem
        $data['is_publik'] = $_POST['is_publik'];
        $this->config_id()->where('id', $id)->update('analisis_indikator', $data);
        $this->session->success = 1;
    }

    public function update($id = 0): void
    {
        if ($this->analisis_master_model->is_analisis_sistem($this->session->analisis_master)) {
            $this->update_indikator_sistem($id);

            return;
        }

        $data = $this->validasi_data($this->input->post());

        $data['id_master'] = $this->session->analisis_master;
        $outp              = $this->config_id()->where('id', $id)->update('analisis_indikator', $data);
        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = 0, $semua = false): void
    {
        // Analisis sistem tidak boleh dihapus
        if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) {
            return;
        }

        // Hapus data analisis master
        $outp = $this->config_id()->where('id_indikator', $id)->delete('analisis_parameter');

        if (! $semua) {
            $this->session->success = 1;
        }
        $outp = $this->config_id()->where('id', $id)->delete('analisis_indikator');

        status_sukses($outp, true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, true);
        }
    }

    private function validasi_parameter($post)
    {
        return [
            'kode_jawaban' => bilangan($post['kode_jawaban']),
            'jawaban'      => htmlentities($post['jawaban']),
            'nilai'        => bilangan($post['nilai']),
        ];
    }

    public function p_insert($in = 0, $data_referensi = null): void
    {
        // Analisis sistem tidak boleh diubah
        if ($this->analisis_master_model->is_analisis_sistem($this->session->analisis_master)) {
            return;
        }

        $data                 = $data_referensi ?? $this->validasi_parameter($this->input->post());
        $data['id_indikator'] = $in;
        $data['config_id']    = identitas('id');
        $outp                 = $this->db->insert('analisis_parameter', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function p_update($id = 0): void
    {
        $data = $this->validasi_parameter($this->input->post());
        // Analisis sistem hanya kolom tertentu boleh diubah
        if ($this->analisis_master_model->is_analisis_sistem($this->session->analisis_master) || $this->input->post('referensi')) {
            unset($data['kode_jawaban'], $data['jawaban']);
        }
        $outp = $this->config_id()->where('id', $id)->update('analisis_parameter', $data);
        status_sukses($outp); //Tampilkan Pesan
    }

    public function p_delete($id = 0): void
    {
        $this->session->success = 1;
        // Analisis sistem tidak boleh dihapus
        if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) {
            return;
        }

        $outp = $this->config_id()->where('id', $id)->delete('analisis_parameter');

        status_sukses($outp, true); //Tampilkan Pesan
    }

    public function p_delete_all(): void
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->p_delete($id);
        }
    }

    public function list_indikator($id = 0)
    {
        return $this->config_id()
            ->get_where('analisis_parameter', ['id_indikator' => $id])
            ->result_array();
    }

    public function get_analisis_indikator($id = 0)
    {
        return $this->config_id()
            ->get_where('analisis_indikator', ['id' => $id])
            ->row_array();
    }

    public function get_analisis_parameter($id = 0)
    {
        return $this->config_id()
            ->get_where('analisis_parameter', ['id' => $id])
            ->row_array();
    }

    public function list_tipe()
    {
        return $this->db
            ->get('analisis_tipe_indikator')
            ->result_array();
    }

    // TODO: pindahkan ke analisis_kategori_model
    public function list_kategori()
    {
        $sql = 'SELECT u.* FROM analisis_kategori_indikator u WHERE u.config_id = ' . identitas('id');
        $sql .= $this->master_sql();
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function raw_analisis_indikator_by_id_master($id = 0)
    {
        return $this->config_id('i')
            ->select('i.*, k.kategori')
            ->from('analisis_indikator i')
            ->join('analisis_kategori_indikator k', 'k.id = i.id_kategori', 'left')
            ->where('i.id_master', $id)
            ->get()
            ->result_array();
    }

    public function get_analisis_indikator_by_id_master($id = 0)
    {
        $list_indikator = [];
        $list_parameter = [];

        $raw_indikator = $this->config_id('i')
            ->select('i.*')
            ->from('analisis_indikator i')
            ->where('i.id_master', $id)
            ->get()
            ->result_array();

        // Setting key array sesuai id
        foreach ($raw_indikator as $val_indikator) {
            $list_indikator[$val_indikator['id']] = $val_indikator['pertanyaan'];

            $temp_parameter = [];

            $raw_parameter = $this->config_id()->where('id_indikator', $val_indikator['id'])->get('analisis_parameter')->result_array();

            foreach ($raw_parameter as $val_parameter) {
                $temp_parameter[$val_parameter['id']] = $val_parameter['jawaban'];
            }

            $list_parameter[$val_indikator['id']] = $temp_parameter;
        }

        return [
            'indikator' => $list_indikator,
            'parameter' => $list_parameter,
        ];
    }

    public function data_tabel($sasaran)
    {
        $this->load->model('referensi_model');

        switch ($sasaran) {
            /*
             * Keterangan Tipe :
             * 1 => Pilihan (Tunggal)
             * 2 => Pilihan (Ganda)
             * 3 => Isian Jumlah (Kuantitatif) / Isian berupa angka
             * 4 => Isian Teks
             */

            // Penduduk
            case 1:
                $data = [
                    'kk_level' => [
                        'judul'     => 'Hubungan Dalam Keluarga',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_hubungan'),
                    ],
                    'rtm_level' => [
                        'judul'     => 'Hubungan Dalam Rumah Tangga',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_rtm_hubungan'),
                    ],
                    'sex' => [
                        'judul'     => 'Jenis Kelamin',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_sex'),
                    ],
                    'tempatlahir' => [
                        'judul' => 'Tempat Lahir',
                    ],
                    'tanggallahir' => [
                        'judul' => 'Tanggal Lahir',
                    ],
                    'agama_id' => [
                        'judul'     => 'Agama',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_agama'),
                    ],
                    'pendidikan_kk_id' => [
                        'judul'     => 'Pendidikan Dalam KK',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk'),
                    ],
                    'pendidikan_sedang_id' => [
                        'judul'     => 'Pendidikan Sedang Ditempuh',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_pendidikan'),
                    ],
                    'pekerjaan_id' => [
                        'judul'     => 'Pekerjaan',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_pekerjaan'),
                    ],
                    'status_kawin' => [
                        'judul'     => 'Status_perkawinan',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_kawin'),
                    ],
                    'warganegara_id' => [
                        'judul'     => 'Kewarganegaraan',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_warganegara'),
                    ],
                    'dokumen_pasport' => [
                        'judul' => 'Dokumen Passport',
                    ],
                    'dokumen_kitas' => [
                        'judul' => 'Dokumen KITAS',
                    ],
                    'ayah_nik' => [
                        'judul' => 'NIK Ayah',
                    ],
                    'nama_ayah' => [
                        'judul' => 'Nama Ayah',
                    ],
                    'ibu_nik' => [
                        'judul' => 'NIK Ibu',
                    ],
                    'nama_ibu' => [
                        'judul' => 'Nama Ibu',
                    ],
                    'golongan_darah_id' => [
                        'judul'     => 'Golongan Darah',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_golongan_darah'),
                    ],
                    // id_cluster => wilayah, agar tdk duplikasi
                    'wilayah' => [
                        'judul' => 'Wilayah (Dusun/RW/RT)',
                    ],
                    'status' => [
                        'judul'     => 'Status Penduduk',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_status'),
                    ],
                    'alamat_sebelumnya' => [
                        'judul' => 'Alamat Sebelumnya',
                    ],
                    'alamat_sekarang' => [
                        'judul' => 'Alamat Sekarang',
                    ],
                    'status_dasar' => [
                        'judul'     => 'Status Dasar',
                        'referensi' => $this->referensi_model->list_data('tweb_status_dasar'),
                    ],
                    'hamil' => [
                        'judul' => 'Status Kehamilan',
                    ],
                    'cacat_id' => [
                        'judul'     => 'Jenis Cacat',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_cacat'),
                    ],
                    'sakit_menahun_id' => [
                        'judul'     => 'Sakit Menahun',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_sakit_menahun'),
                    ],
                    'akta_lahir' => [
                        'judul' => 'Akta Lahir',
                    ],
                    'akta_perkawinan' => [
                        'judul' => 'Akta Perkawinan',
                    ],
                    'tanggalperkawinan' => [
                        'judul' => 'Tanggal Perkawinan',
                    ],
                    'akta_perceraian' => [
                        'judul' => 'Akta Perceraian',
                    ],
                    'tanggalperceraian' => [
                        'judul' => 'Tanggal Perceraian',
                    ],
                    'cara_kb_id' => [
                        'judul'     => 'Akseptor KB',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_cara_kb'),
                    ],
                    'telepon' => [
                        'judul' => 'Telepon',
                    ],
                    'tanggal_akhir_paspor' => [
                        'judul' => 'Tanggal Akhir Paspor',
                    ],
                    'no_kk_sebelumnya' => [
                        'judul' => 'No. KK Sebelumnya',
                    ],
                    'ktp_el' => [
                        'judul'     => 'E-KTP',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_status_ktp'),
                    ],
                    'status_rekam' => [
                        'judul'     => 'Status Rekam',
                        'referensi' => $this->referensi_model->list_status_rekam(),
                    ],
                    'waktu_lahir' => [
                        'judul' => 'Waktu Lahir',
                    ],
                    'tempat_dilahirkan' => [
                        'judul' => 'Tempat Dilahirkan',
                    ],
                    'jenis_kelahiran' => [
                        'judul' => 'Jenis Kelahiran',
                    ],
                    'kelahiran_anak_ke' => [
                        'judul' => 'Kelahiran Anak Ke - ',
                        'tipe'  => 3,
                    ],
                    'penolong_kelahiran' => [
                        'judul' => 'Penolong Kelahiran',
                    ],
                    'berat_lahir' => [
                        'judul' => 'Berat lahir',
                        'tipe'  => 3,
                    ],
                    'panjang_lahir' => [
                        'judul' => 'Panjang Lahir',
                        'tipe'  => 3,
                    ],
                    'tag_id_card' => [
                        'judul' => 'Tag ID Card',
                    ],
                    'id_asuransi' => [
                        'judul'     => 'ID Asuransi',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_asuransi'),
                    ],
                    'no_asuransi' => [
                        'judul' => 'No. Asusransi',
                    ],
                    'email' => [
                        'judul' => 'Email',
                    ],
                    'bahasa_id' => [
                        'judul'     => 'Dapat Membaca Huruf',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('ref_penduduk_bahasa'),
                    ],
                    'negara_asal' => [
                        'judul' => 'Negara Asal',
                    ],
                    'tempat_cetak_ktp' => [
                        'judul' => 'Tempat Cetak KTP',
                    ],
                    'tanggal_cetak_ktp' => [
                        'judul' => 'Tanggal Cetak KTP',
                    ],
                    'suku' => [
                        'judul' => 'Suku/Etnis',
                    ],
                    'bpjs_ketenagakerjaan' => [
                        'judul' => 'BPJS Ketenagakerjaan',
                    ],
                ];
                break;

                // Keluarga
            case 2:
                $data = [
                    'nik_kepala' => [
                        'judul' => 'NIK Kepala KK',
                    ],
                    'kelas_sosial' => [
                        'judul'     => 'Kelas Sosial',
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_keluarga_sejahtera'),
                    ],
                    'alamat' => [
                        'judul' => 'Alamat',
                    ],
                    // id_cluster => wilayah, agar tdk duplikasi
                    'wilayah' => [
                        'judul' => 'Wilayah (Dusun/RW/RT)',
                    ],
                ];
                break;

                // Desa
            default:

                $desa   = $this->setting->sebutan_desa;
                $kepala = $this->setting->sebutan_kepala_desa;

                $data = [

                    // IDENTITAS DESA
                    'nama_desa' => [
                        'judul' => 'Nama ' . $desa,
                    ],
                    'kode_desa' => [
                        'judul' => 'Kode ' . $desa,
                    ],
                    'kode_pos' => [
                        'judul' => 'Kode POS',
                    ],
                    'nama_kepala_desa' => [
                        'judul' => 'Nama ' . $kepala,
                    ],
                    'nip_kepala_desa' => [
                        'judul' => 'NIP ' . $kepala,
                    ],
                    'jk_kepala_desa' => [
                        'judul'     => 'Jenis Kelamin ' . $kepala,
                        'tipe'      => 1,
                        'referensi' => $this->referensi_model->list_data('tweb_penduduk_sex'),
                    ],
                    'titik_koordinat_desa' => [
                        'judul' => 'Titik Koordinat ' . $desa . ' (Lintang / Bujur)',
                    ],
                    'alamat_kantor' => [
                        'judul' => 'Alamat Kantor',
                    ],
                    'no_telepon_kepala_desa' => [
                        'judul' => 'Nomor Telepon Rumah / HP ' . $kepala,
                    ],
                    'no_telepon_kantor_desa' => [
                        'judul' => 'Nomor Telepon Kantor ' . $desa,
                    ],
                    'email_desa' => [
                        'judul' => 'Email ' . $desa,
                    ],
                    'pendidikan_kepala_desa' => [
                        'judul' => 'Pendidikan Terakhir ' . $kepala,
                    ],
                    'nama_kecamatan' => [
                        'judul' => 'Nama Kecamatan',
                    ],
                    'kode_kecamatan' => [
                        'judul' => 'Kode Kecamatan',
                    ],
                    'nama_kepala_camat' => [
                        'judul' => 'Nama Kepala Camat',
                    ],
                    'nip_kepala_camat' => [
                        'judul' => 'NIP Kepala Camat',
                    ],
                    'kode_kabupaten' => [
                        'judul' => 'Kode Kabupaten',
                    ],
                    'nama_propinsi' => [
                        'judul' => 'Nama Provinsi',
                    ],
                    'kode_propinsi' => [
                        'judul' => 'Kode Provinsi',
                    ],

                    // DEMOGRAFI
                    // # Penduduk
                    'jumlah_total_penduduk' => [
                        'judul' => 'Jumlah Total Penduduk',
                    ],
                    'jumlah_penduduk_laki_laki' => [
                        'judul' => 'Jumlah Penduduk Laki-laki',
                    ],
                    'jumlah_penduduk_perempuan' => [
                        'judul' => 'Jumlah Penduduk Perempuan',
                    ],
                    'jumlah_penduduk_pedatang' => [
                        'judul' => 'Jumlah Penduduk Pendatang',
                    ],
                    'jumlah_penduduk_yang_pergi' => [
                        'judul' => 'Jumlah Penduduk Yang Pergi',
                    ],

                    // # Kepala Keluarga
                    'jumlah_total_kepala_keluarga' => [
                        'judul' => 'Jumlah Total Kepala Keluarga',
                    ],
                    'jumlah_kepala_keluarga_laki_laki' => [
                        'judul' => 'Jumlah Kepala Keluarga Laki-laki',
                    ],
                    'jumlah_kepala_keluarga_perempuan' => [
                        'judul' => 'Jumlah Kepala Keluarga Perempuan',
                    ],
                    // 'jumlah_keluarga_miskin' => [
                    // 	'judul' => 'Jumlah Keluarga Miskin',
                    // ],

                    // # Jumlah Penduduk Berdasarkan Struktur Usia
                    // 'jumlah_penduduk_kategori_usia' => [
                    // 	'judul' => 'Jumlah Penduduk Berdasarkan Struktur Usia',
                    // ],

                    // # Jumlah Penduduk Berdasarkan Pekerjaan
                    // 'jumlah_penduduk_kategori_pekerjaan' => [
                    // 	'judul' => 'Jumlah Penduduk Berdasarkan Pekerjaan',
                    // ],

                    // # Jumlah Warga Penyandang Kebutuhan Khusus
                    // 'jumlah_warga_kebutuhan_khusus' => [
                    // 	'judul' => 'Jumlah Warga Penyandang Kebutuhan Khusus',
                    // ],

                    // Ketersediaan Saran Kesehatan
                    // 'sarana_kesehatan' => [
                    // 	'judul' => 'Sarana Kesehatan',
                    // 	'tipe' => 1,
                    // 	'referensi' => null, // Data diambil dari kategori  tipe lokasi
                    // ],

                    // Tingkat Kepesertaan BPJS (BPJS Kesehatan / JKN)
                    'jumlah_peserta_bpjs' => [
                        'judul' => 'Jumlah Penduduk Terdaftar BPJS Kesehatan / JKN',
                    ],

                    // Data Tingkat Pendidikan ???
                    // 'pendidikan_penduduk_desa' => [
                    // 	'judul' => 'Tingkat Pendidikan Sebagian Besar Penduduk ' . $desa,
                    // 	'tipe' => 1,
                    // 	'referensi' => $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk'),
                    // ],

                    // Data Keagamaan ???
                    // 'agama_penduduk_desa' => [
                    // 	'judul' => 'Agama Mayoritas',
                    // 	'tipe' => 1,
                    // 	'referensi' => $this->referensi_model->list_data('tweb_penduduk_agama'),
                    // ],
                ];
                break;
        }

        return $data;
    }
}
