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

use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class Suplemen_model extends MY_Model
{
    protected $table = 'suplemen';

    public function create()
    {
        $data  = $this->validasi($this->input->post());
        $hasil = $this->db->insert($this->table, $data);

        status_sukses($hasil); //Tampilkan Pesan
    }

    private function validasi($post)
    {
        $nama = nomor_surat_keputusan($post['nama']);

        return [
            'sasaran'    => $post['sasaran'],
            'nama'       => $nama,
            'slug'       => unique_slug($this->table, $nama),
            'keterangan' => htmlentities($post['keterangan']),
        ];
    }

    public function paging_suplemen($page_number = 1)
    {
        $this->db->select('COUNT(DISTINCT s.id) AS jml');
        $this->list_data_sql();

        $row      = $this->db->get()->row_array();
        $jml_data = $row['jml'];

        return $this->paginasi($page_number, $jml_data);
    }

    private function list_data_sql()
    {
        $sasaran = $this->session->sasaran;

        if ($sasaran > 0) {
            $this->db->where('s.sasaran', $sasaran);
        }

        $this->db
            ->from('suplemen s')
            ->join('suplemen_terdata st', 's.id = st.id_suplemen', 'left');

        $this->search_sql();
    }

    public function list_data($order_by = 1, $offset = 0, $limit = 0)
    {
        $this->list_data_sql();
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $this->db
            ->select('s.*')
            ->select('COUNT(st.id) AS jml')
            ->order_by('s.nama')
            ->group_by('s.id');

        return $this->db->get()->result_array();
    }

    public function list_sasaran($id, $sasaran)
    {
        $data = [];

        switch ($sasaran) {
            // Sasaran Penduduk
            case '1':
                $data['judul'] = 'NIK / Nama Penduduk';
                $data['data']  = $this->list_penduduk($id);
                break;

                // Sasaran Keluarga
            case '2':
                $data['judul'] = 'No.KK / Nama Kepala Keluarga';
                $data['data']  = $this->list_kk($id);

                // no break
            default:
                // code...
                break;
        }

        return $data;
    }

    private function get_id_terdata_penduduk($id_suplemen)
    {
        $list_penduduk = $this->db
            ->select('p.id')
            ->from('tweb_penduduk p')
            ->join('suplemen_terdata t', 'p.id = t.id_terdata', 'left')
            ->where('t.id_suplemen', $id_suplemen)
            ->get()
            ->result_array();

        return sql_in_list(array_column($list_penduduk, 'id'));
    }

    private function list_penduduk($id)
    {
        // Penduduk yang sudah terdata untuk suplemen ini
        $terdata = $this->get_id_terdata_penduduk($id);
        if ($terdata) {
            $this->db->where("p.id NOT IN ({$terdata})");
        }

        $data = $this->db->select('p.id as id, p.nik as nik, p.nama, w.rt, w.rw, w.dusun')
            ->from('penduduk_hidup p')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
            ->get()
            ->result_array();

        $hasil = [];

        foreach ($data as $item) {
            $penduduk = [
                'id'   => $item['id'],
                'nama' => strtoupper($item['nama']) . ' [' . $item['nik'] . ']',
                'info' => 'RT/RW ' . $item['rt'] . '/' . $item['rw'] . ' - ' . strtoupper($item['dusun']),
            ];
            $hasil[] = $penduduk;
        }

        return $hasil;
    }

    private function get_id_terdata_kk($id_suplemen)
    {
        $list_kk = $this->db
            ->select('k.id')
            ->from('tweb_keluarga k')
            ->join('suplemen_terdata t', 'k.id = t.id_terdata', 'left')
            ->where('t.id_suplemen', $id_suplemen)
            ->get()
            ->result_array();

        return sql_in_list(array_column($list_kk, 'id'));
    }

    private function list_kk($id)
    {
        // Keluarga yang sudah terdata untuk suplemen ini
        $terdata = $this->get_id_terdata_kk($id);
        if ($terdata) {
            $this->db->where("k.id NOT IN ({$terdata})");
        }

        // Daftar keluarga, tidak termasuk keluarga yang sudah terdata
        $data = $this->db->select('k.id as id, k.no_kk, p.nama, w.rt, w.rw, w.dusun')
            ->from('keluarga_aktif k')
            ->join('penduduk_hidup p', 'p.id = k.nik_kepala', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
            ->get()
            ->result_array();

        $hasil = [];

        foreach ($data as $item) {
            $item['id'] = preg_replace('/[^a-zA-Z0-9]/', '', $item['id']); //hapus non_alpha di no_kk
            $kk         = [
                'id'   => $item['id'],
                'nama' => strtoupper($item['nama']) . ' [' . $item['no_kk'] . ']',
                'info' => 'RT/RW ' . $item['rt'] . '/' . $item['rw'] . ' - ' . strtoupper($item['dusun']),
            ];
            $hasil[] = $kk;
        }

        return $hasil;
    }

    public function get_suplemen($id)
    {
        return $this->db
            ->select('s.*')
            ->select('COUNT(st.id) AS jml')
            ->from('suplemen s')
            ->join('suplemen_terdata st', 's.id = st.id_suplemen', 'left')
            ->where('s.id', $id)
            ->group_by('s.id')
            ->get()
            ->row_array();
    }

    public function get_rincian($p, $suplemen_id)
    {
        $suplemen = $this->db->where('id', $suplemen_id)->get($this->table)->row_array();

        switch ($suplemen['sasaran']) {
            // Sasaran Penduduk
            case '1':
                $data                                = $this->get_penduduk_terdata($suplemen_id, $p);
                $data['judul']['judul_terdata_info'] = 'No. KK';
                $data['judul']['judul_terdata_plus'] = 'NIK Penduduk';
                $data['judul']['judul_terdata_nama'] = 'Nama Penduduk';
                break;

                // Sasaran Keluarga
            case '2':
                $data                                = $this->get_kk_terdata($suplemen_id, $p);
                $data['judul']['judul_terdata_info'] = 'NIK KK';
                $data['judul']['judul_terdata_plus'] = 'No. KK';
                $data['judul']['judul_terdata_nama'] = 'Kepala Keluarga';

                break;

                // Sasaran X
            default:
                // code...
                break;
        }

        $data[$this->table] = $suplemen;
        $data['keyword']    = $this->autocomplete($suplemen['sasaran']);

        return $data;
    }

    private function paging($p)
    {
        $jml_data = $this->db
            ->select('COUNT(s.id) as jumlah')
            ->get()
            ->row()->jumlah;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function get_penduduk_terdata_sql($suplemen_id)
    {
        // Data Penduduk
        $this->db
            ->from('suplemen_terdata s')
            ->join('tweb_penduduk o', ' s.id_terdata = o.id', 'left')
            ->join('tweb_keluarga k', 'k.id = o.id_kk', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'left')
            ->where('s.id_suplemen', $suplemen_id);
    }

    public function get_penduduk_terdata($suplemen_id, $p = 0)
    {
        $hasil = [];
        // Paging
        if ((! empty($this->session->per_page) && $this->session->per_page > 0) || $p > 0) {
            $this->get_penduduk_terdata_sql($suplemen_id);
            $hasil['paging'] = $this->paging($p);
            $this->db->limit($hasil['paging']->per_page, $hasil['paging']->offset);
        }

        $this->get_penduduk_terdata_sql($suplemen_id);
        $this->db
            ->select('s.*, s.id_terdata, o.nik, o.nama, o.tempatlahir, o.tanggallahir, o.sex, k.no_kk, w.rt, w.rw, w.dusun')
            ->select('(case when (o.id_kk IS NULL or o.id_kk = 0) then o.alamat_sekarang else k.alamat end) AS alamat');
        $this->search_sql('1');
        if ($sex = $this->session->sex) {
            $this->db->where('o.sex', $sex);
        }
        if ($dusun = $this->session->dusun) {
            $this->db->where('w.dusun', $dusun);
        }
        if ($rw = $this->session->rw) {
            $this->db->where('w.rw', $rw);
        }
        if ($rt = $this->session->rt) {
            $this->db->where('w.rt', $rt);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data = $query->result_array();

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['terdata_info']  = $data[$i]['no_kk'];
                $data[$i]['terdata_plus']  = $data[$i]['nik'];
                $data[$i]['terdata_nama']  = strtoupper($data[$i]['nama']);
                $data[$i]['tempat_lahir']  = strtoupper($data[$i]['tempatlahir']);
                $data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
                $data[$i]['sex']           = ($data[$i]['sex'] == 1) ? 'LAKI-LAKI' : 'PEREMPUAN';
                $data[$i]['info']          = strtoupper($data[$i]['alamat'] . ' ' . 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . ' - ' . $this->setting->sebutan_dusun . ' ' . $data[$i]['dusun']);
            }
            $hasil['terdata'] = $data;
        }

        return $hasil;
    }

    private function get_kk_terdata_sql($suplemen_id)
    {
        // Data KK
        $this->db
            ->from('suplemen_terdata s')
            ->join('tweb_keluarga o', 's.id_terdata = o.id', 'left')
            ->join('tweb_penduduk q', 'o.nik_kepala = q.id', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = q.id_cluster', 'left')
            ->where('s.id_suplemen', $suplemen_id);
    }

    public function get_kk_terdata($suplemen_id, $p = 0)
    {
        $hasil = [];
        // Paging
        if ((! empty($this->session->per_page) && $this->session->per_page > 0) || $p > 0) {
            $this->get_kk_terdata_sql($suplemen_id);
            $hasil['paging'] = $this->paging($p);
            $this->db->limit($hasil['paging']->per_page, $hasil['paging']->offset);
        }

        $this->get_kk_terdata_sql($suplemen_id);
        $this->db
            ->select('s.*, s.id_terdata, o.no_kk, s.id_suplemen, o.nik_kepala, o.alamat, q.nik, q.nama, q.tempatlahir, q.tanggallahir, q.sex, w.rt, w.rw, w.dusun');
        $this->search_sql('2');
        if ($sex = $this->session->sex) {
            $this->db->where('q.sex', $sex);
        }
        if ($dusun = $this->session->dusun) {
            $this->db->where('w.dusun', $dusun);
        }
        if ($rw = $this->session->rw) {
            $this->db->where('w.rw', $rw);
        }
        if ($rt = $this->session->rt) {
            $this->db->where('w.rt', $rt);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data = $query->result_array();

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['terdata_info']  = $data[$i]['nik'];
                $data[$i]['terdata_plus']  = $data[$i]['no_kk'];
                $data[$i]['terdata_nama']  = strtoupper($data[$i]['nama']);
                $data[$i]['tempat_lahir']  = strtoupper($data[$i]['tempatlahir']);
                $data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
                $data[$i]['sex']           = ($data[$i]['sex'] == 1) ? 'LAKI-LAKI' : 'PEREMPUAN';
                $data[$i]['info']          = strtoupper($data[$i]['alamat'] . ' ' . 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . ' - ' . $this->setting->sebutan_dusun . ' ' . $data[$i]['dusun']);
            }
            $hasil['terdata'] = $data;
        }

        return $hasil;
    }

    // Mengambil data individu terdata
    public function get_terdata($id_terdata, $sasaran)
    {
        $this->load->model('surat_model');

        switch ($sasaran) {
            // Sasaran Penduduk
            case 1:
                $sql = "SELECT u.id AS id, u.nama AS nama, x.nama AS sex, u.id_kk AS id_kk,
				u.tempatlahir AS tempatlahir, u.tanggallahir AS tanggallahir,
				(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
				from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
				w.nama AS status_kawin, f.nama AS warganegara, a.nama AS agama, d.nama AS pendidikan, j.nama AS pekerjaan, u.nik AS nik, c.rt AS rt, c.rw AS rw, c.dusun AS dusun, k.no_kk AS no_kk, k.alamat,
				(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
				from tweb_penduduk u
				left join tweb_penduduk_sex x on u.sex = x.id
				left join tweb_penduduk_kawin w on u.status_kawin = w.id
				left join tweb_penduduk_agama a on u.agama_id = a.id
				left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
				left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
				left join tweb_wil_clusterdesa c on u.id_cluster = c.id
				left join tweb_keluarga k on u.id_kk = k.id
				left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
				WHERE u.id = ?";
                $query                  = $this->db->query($sql, $id_terdata);
                $data                   = $query->row_array();
                $data['terdata_info']   = $data['nik'];
                $data['terdata_plus']   = $data['no_kk'];
                $data['terdata_nama']   = $data['nama'];
                $data['alamat_wilayah'] = $this->surat_model->get_alamat_wilayah($data);
                break;

                // Sasaran Keluarga
            case 2:
                $data                 = $this->keluarga_model->get_kepala_kk($id_terdata);
                $data['terdata_info'] = $data['nik'];
                $data['terdata_plus'] = $data['no_kk'];
                $data['terdata_nama'] = $data['nama'];
                $data['id']           = $data['id_kk']; // id_kk digunakan sebagai id terdata
                break;

            default:
                break;
        }

        return $data;
    }

    public function hapus($id)
    {
        $ada = $this->db->where('id_suplemen', $id)
            ->get('suplemen_terdata')->num_rows();
        if ($ada) {
            $this->session->success   = '-1';
            $this->session->error_msg = ' --> Tidak bisa dihapus, karena masih digunakan';

            return;
        }
        $hasil = $this->db->where('id', $id)->delete($this->table);

        status_sukses($hasil); //Tampilkan Pesan
    }

    public function update($id)
    {
        $data  = $this->validasi($this->input->post());
        $hasil = $this->db->where('id', $id)->update($this->table, $data);

        status_sukses($hasil); //Tampilkan Pesan
    }

    public function add_terdata($post, $id)
    {
        $id_terdata = $post['id_terdata'];
        $sasaran    = $this->db->select('sasaran')->where('id', $id)->get($this->table)->row()->sasaran;
        $hasil      = $this->db->where('id_suplemen', $id)->where('id_terdata', $id_terdata)->get('suplemen_terdata');
        if ($hasil->num_rows() > 0) {
            return false;
        }

        $data = [
            'id_suplemen' => $id,
            'id_terdata'  => $id_terdata,
            'sasaran'     => $sasaran,
            'keterangan'  => substr(htmlentities($post['keterangan']), 0, 100), // Batasi 100 karakter
        ];

        return $this->db->insert('suplemen_terdata', $data);
    }

    public function hapus_terdata($id_terdata)
    {
        $this->db->where('id', $id_terdata);
        $this->db->delete('suplemen_terdata');
    }

    public function hapus_terdata_all()
    {
        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->hapus_terdata($id);
        }
    }

    // $id = suplemen_terdata.id
    public function edit_terdata($post, $id)
    {
        $data['keterangan'] = substr(htmlentities($post['keterangan']), 0, 100); // Batasi 100 karakter
        $this->db->where('id', $id);
        $this->db->update('suplemen_terdata', $data);
    }

    // Mengambil data individu terdata menggunakan id tabel suplemen_terdata
    public function get_suplemen_terdata_by_id($id)
    {
        $data = $this->db->where('id', $id)->get('suplemen_terdata')->row_array();
        // Data tambahan untuk ditampilkan
        $terdata = $this->get_terdata($data['id_terdata'], $data['sasaran']);

        switch ($data['sasaran']) {
            case 1:
                $data['judul_terdata_nama'] = 'NIK';
                $data['judul_terdata_info'] = 'Nama Terdata';
                $data['terdata_nama']       = $terdata['nik'];
                $data['terdata_info']       = $terdata['nama'];
                break;

            case 2:
                $data['judul_terdata_nama'] = 'No. KK';
                $data['judul_terdata_info'] = 'Kepala Keluarga';
                $data['terdata_nama']       = $terdata['no_kk'];
                $data['terdata_info']       = $terdata['nama'];
                break;

            default:
            }

        return $data;
    }

    public function get_terdata_suplemen($sasaran, $id_terdata)
    {
        $list_suplemen = [];
        // Menampilkan keterlibatan $id_terdata dalam data suplemen yang ada
        $strSQL = "SELECT p.id as id, o.id_terdata as nik, p.nama as nama, p.keterangan
			FROM suplemen_terdata o
			LEFT JOIN suplemen p ON p.id = o.id_suplemen
			WHERE ((o.id_terdata='" . $id_terdata . "') AND (o.sasaran='" . $sasaran . "'))";
        $query = $this->db->query($strSQL);
        if ($query->num_rows() > 0) {
            $list_suplemen = $query->result_array();
        }

        switch ($sasaran) {
            case 1:
                // Rincian Penduduk
                $strSQL = "SELECT o.nama, o.foto, o.nik, w.rt, w.rw, w.dusun,
				(case when (o.id_kk IS NULL or o.id_kk = 0) then o.alamat_sekarang else k.alamat end) AS alamat
					FROM tweb_penduduk o
					LEFT JOIN tweb_keluarga k ON k.id = o.id_kk
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = o.id_cluster
					WHERE o.id = '" . $id_terdata . "'";
                $query = $this->db->query($strSQL);
                if ($query->num_rows() > 0) {
                    $row         = $query->row_array();
                    $data_profil = [
                        'id'    => $id_terdata,
                        'nama'  => $row['nama'] . ' - ' . $row['nik'],
                        'ndesc' => 'Alamat: ' . $row['alamat'] . ' RT ' . strtoupper($row['rt']) . ' / RW ' . strtoupper($row['rw']) . ' ' . strtoupper($row['dusun']),
                        'foto'  => $row['foto'],
                    ];
                }

                break;

            case 2:
                // KK
                $strSQL = "SELECT o.nik_kepala, o.no_kk, o.alamat, p.nama, w.rt, w.rw, w.dusun
					FROM tweb_keluarga o
					LEFT JOIN tweb_penduduk p ON o.nik_kepala = p.id
					LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster
					WHERE o.id = '" . $id_terdata . "'";
                $query = $this->db->query($strSQL);
                if ($query->num_rows() > 0) {
                    $row         = $query->row_array();
                    $data_profil = [
                        'id'    => $id_terdata,
                        'nama'  => 'Kepala KK : ' . $row['nama'] . ', NO KK: ' . $row['no_kk'],
                        'ndesc' => 'Alamat: ' . $row['alamat'] . ' RT ' . strtoupper($row['rt']) . ' / RW ' . strtoupper($row['rw']) . ' ' . strtoupper($row['dusun']),
                        'foto'  => '',
                    ];
                }

                break;

            default:
            }
        if (! empty($list_suplemen)) {
            return ['daftar_suplemen' => $list_suplemen, 'profil' => $data_profil];
        }

        return null;
    }

    protected function search_sql($sasaran = '')
    {
        if ($this->session->cari) {
            $cari = $this->session->cari;

            switch ($sasaran) {
                case '1':
                    //# sasaran penduduk
                    $this->db
                        ->group_start()
                        ->like('o.nama', $cari)
                        ->or_like('o.nik', $cari)
                        ->or_like('k.no_kk', $cari)
                        ->or_like('o.tag_id_card', $cari)
                        ->group_end();
                    break;

                case '2':
                    //# sasaran keluarga / KK
                    $this->db
                        ->group_start()
                        ->like('o.no_kk', $cari)
                        ->or_like('o.nik_kepala', $cari)
                        ->or_like('q.nik', $cari)
                        ->or_like('q.nama ', $cari)
                        ->or_like('q.tag_id_card', $cari)
                        ->group_end();
                    break;
            }
        }
    }

    private function autocomplete($sasaran)
    {
        switch ($sasaran) {
            case '1':
                //# sasaran penduduk
                $data = $this->db
                    ->select('p.nama')
                    ->from('suplemen_terdata s')
                    ->join('tweb_penduduk p', 'p.id = s.id_terdata', 'left')
                    ->where('s.sasaran', $sasaran)
                    ->group_by('p.nama')
                    ->get()
                    ->result_array();
                break;

            case '2':
                //# sasaran keluarga / KK
                $data = $this->db
                    ->select('p.nama')
                    ->from('suplemen_terdata s')
                    ->join('tweb_keluarga k', 'k.id = s.id_terdata', 'left')
                    ->join('tweb_penduduk p', 'p.id = k.nik_kepala', 'left')
                    ->where('s.sasaran', $sasaran)
                    ->group_by('p.nama')
                    ->get()
                    ->result_array();
                break;

            default:
                break;
        }

        return autocomplete_data_ke_str($data);
    }

    public function ekspor($id = 0)
    {
        $data_suplemen = $this->get_rincian(0, $id);

        $writer    = WriterEntityFactory::createXLSXWriter();
        $file_name = namafile($data_suplemen[$this->table]['nama']) . '.xlsx';
        $writer->openToBrowser($file_name);

        // Ubah Nama Sheet
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Peserta');

        // Deklarasi Style
        $border = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->build();

        $borderStyle = (new StyleBuilder())
            ->setBorder($border)
            ->build();

        $yellowBackgroundStyle = (new StyleBuilder())
            ->setBackgroundColor(Color::YELLOW)
            ->setFontBold()
            ->setBorder($border)
            ->build();

        $greenBackgroundStyle = (new StyleBuilder())
            ->setBackgroundColor(Color::LIGHT_GREEN)
            ->build();

        // Cetak Header Tabel
        $values        = ['Peserta', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Keterangan'];
        $rowFromValues = WriterEntityFactory::createRowFromArray($values, $yellowBackgroundStyle);
        $writer->addRow($rowFromValues);

        // Cetak Data Anggota Suplemen
        $data_anggota = $data_suplemen['terdata'];

        foreach ($data_anggota as $data) {
            $cells = [
                WriterEntityFactory::createCell($data['nik']),
                WriterEntityFactory::createCell(strtoupper($data['nama'])),
                WriterEntityFactory::createCell($data['tempatlahir']),
                WriterEntityFactory::createCell(tgl_indo_out($data['tanggallahir'])),
                WriterEntityFactory::createCell(strtoupper($data['alamat'] . ' RT ' . $data['rt'] . ' / RW ' . $data['rw'] . ' ' . $this->setting->sebutan_dusun . ' ' . $data['dusun'])),
                WriterEntityFactory::createCell(empty($data['keterangan']) ? '-' : $data['keterangan']),
            ];

            $singleRow = WriterEntityFactory::createRow($cells);
            $singleRow->setStyle($borderStyle);
            $writer->addRow($singleRow);
        }

        $cells = [
            '###', '', '', '', '', '',
        ];
        $singleRow = WriterEntityFactory::createRowFromArray($cells);
        $writer->addRow($singleRow);

        // Cetak Catatan
        $array_catatan = [
            [
                'Catatan:', '', '', '', '', '',
            ],
            [
                '1. Sesuaikan kolom peserta (A) berdasarkan sasaran : - penduduk = nik, - keluarga = no. kk', '', '', '', '', '',
            ],
            [
                '2. Kolom Peserta (A)  wajib di isi', '', '', '', '', '',
            ],
            [
                '3. Kolom (B, C, D, E) diambil dari database kependudukan', '', '', '', '', '',
            ],
            [
                '4. Kolom (F) opsional', '', '', '', '', '',
            ],
        ];

        $rows_catatan = [];

        foreach ($array_catatan as $catatan) {
            $rows_catatan[] = WriterEntityFactory::createRowFromArray($catatan, $greenBackgroundStyle);
        }
        $writer->addRows($rows_catatan);

        $writer->close();
    }

    public function impor($suplemen_id)
    {
        $this->load->library('upload');

        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'xls|xlsx|xlsm';
        $config['file_name']     = namafile('Impor Peserta Data Suplemen');

        $this->upload->initialize($config);

        if (! $this->upload->do_upload('userfile')) {
            return session_error($this->upload->display_errors());
        }

        // Data Suplemen
        $temp                    = $this->session->per_page;
        $this->session->per_page = 1000000000;

        $upload = $this->upload->data();
        $file   = LOKASI_DOKUMEN . $upload['file_name'];

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file);

        $data_peserta      = [];
        $terdaftar_peserta = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            $baris_pertama = true;
            $no_baris      = 0;
            $no_gagal      = 0;
            $no_sukses     = 0;
            $pesan         = '';

            $field = ['id', 'nama', 'sasaran', 'keterangan'];

            // Sheet Program
            if ($sheet->getName() == 'Peserta') {
                $suplemen_record = $this->get_suplemen($suplemen_id);
                $sasaran         = $suplemen_record['sasaran'];

                if ($sasaran == '1') {
                    $ambil_peserta     = $this->get_penduduk_terdata($suplemen_id);
                    $terdaftar_peserta = array_column($ambil_peserta['terdata'], 'nik');
                } elseif ($sasaran == '2') {
                    $ambil_peserta     = $this->get_kk_terdata($suplemen_id);
                    $terdaftar_peserta = array_column($ambil_peserta['terdata'], 'no_kk');
                }

                foreach ($sheet->getRowIterator() as $row) {
                    $cells   = $row->getCells();
                    $peserta = trim((string) $cells[0]); // NIK atau No_kk sesuai sasaran

                    // Data terakhir
                    if ($peserta == '###') {
                        break;
                    }

                    // Abaikan baris pertama / judul
                    if ($baris_pertama) {
                        $baris_pertama = false;

                        continue;
                    }

                    $no_baris++;

                    // Cek valid data peserta sesuai sasaran
                    $cek_peserta = $this->cek_peserta($peserta, $sasaran);
                    if (! in_array($peserta, $cek_peserta['valid'])) {
                        $no_gagal++;
                        $pesan .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / ' . $cek_peserta['sasaran_peserta'] . ' = ' . $peserta . '</b> tidak ditemukan <br>';

                        continue;
                    }

                    $penduduk_sasaran = $this->cek_penduduk($sasaran, $peserta);
                    if (! $penduduk_sasaran['id_terdata']) {
                        $no_gagal++;
                        $pesan .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / ' . $penduduk_sasaran['id_sasaran'] . ' = ' . $peserta . '</b> yang terdaftar tidak ditemukan <br>';

                        continue;
                    }
                    $id_terdata = $penduduk_sasaran['id_terdata'];

                    // Cek data peserta yg akan dimpor dan yg sudah ada
                    if (in_array($peserta, $terdaftar_peserta)) {
                        $no_gagal++;
                        $pesan .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> sudah ada <br>';

                        continue;
                    }

                    $terdaftar_peserta[] = $peserta;

                    // Simpan data peserta yg diimpor dalam bentuk array
                    $simpan = [
                        'id_suplemen' => $suplemen_id,
                        'id_terdata'  => $id_terdata,
                        'sasaran'     => $sasaran, // Duplikasi
                        'keterangan'  => (string) $cells[1],
                    ];

                    $data_peserta[] = $simpan;
                    $no_sukses++;
                }

                // Proses impor peserta
                if ($no_baris <= 0) {
                    $pesan .= '- Data peserta tidak tersedia<br>';
                } else {
                    $this->impor_peserta($suplemen_id, $data_peserta);
                }
            }
        }

        $reader->close();
        unlink($file);

        $notif = [
            'gagal'  => $no_gagal,
            'sukses' => $no_sukses,
            'pesan'  => $pesan,
        ];

        $this->session->set_flashdata('notif', $notif);
        $this->session->per_page = $temp;
    }

    // Cek valid data peserta sesuai sasaran (by NIK atau No. KK)
    private function cek_penduduk($sasaran, $peserta)
    {
        $terdata = [];
        if ($sasaran == '1') {
            $terdata['id_sasaran'] = 'NIK';
            $cek_penduduk          = $this->penduduk_model->get_penduduk_by_nik($peserta);
            if ($cek_penduduk['id']) {
                $terdata['id_terdata'] = $cek_penduduk['id'];
            }
        } elseif ($sasaran == '2') {
            $terdata['id_sasaran'] = 'KK';
            $kepala_kk             = $this->keluarga_model->get_kepala_kk($peserta, true);
            if ($kepala_kk['nik']) {
                $terdata['id_terdata'] = $kepala_kk['id_kk'];
            }
        }

        return $terdata;
    }

    public function impor_peserta($suplemen_id = '', $data_peserta = [])
    {
        $this->session->success = 1;

        if ($data_peserta) {
            $outp = $this->db->insert_batch('suplemen_terdata', $data_peserta);
        }

        status_sukses($outp, true);
    }

    public function cek_peserta($peserta = '', $sasaran = 1)
    {
        if (in_array($peserta, [null, '-', ' ', '0'])) {
            return false;
        }

        switch ($sasaran) {
            case 1:
                // Penduduk
                $sasaran_peserta = 'NIK';

                $data = $this->db
                    ->select('id, nik as no')
                    ->where('nik', $peserta)
                    ->get('penduduk_hidup')
                    ->result_array();
                break;

            case 2:
                // Keluarga
                $sasaran_peserta = 'No. KK';

                $data = $this->db
                    ->select('id, no_kk as no')
                    ->from('keluarga_aktif')
                    ->where('no_kk', $peserta)
                    ->get()
                    ->result_array();
                break;

            default:
                // Lainnya
                break;
        }

        return [
            'id'              => $data[0]['id'], // untuk nik, no_kk, no_rtm, kode konversi menjadi id issue #3417
            'sasaran_peserta' => $sasaran_peserta,
            'valid'           => array_column($data, 'no'), // untuk daftar valid anggota keluarga
        ];
    }

    public function slug($slug = null)
    {
        return $this->db
            ->select('id')
            ->get_where($this->table, ['slug' => $slug])
            ->row()
            ->id;
    }
}
