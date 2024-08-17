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

class Cdesa_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('data_persil_model');
    }

    public function autocomplete($cari = '')
    {
        $sql_kolom  = [];
        $list_kolom = [
            'nomor'             => 'cdesa',
            'nama_pemilik_luar' => 'cdesa',
            'nama_kepemilikan'  => 'cdesa',
        ];

        foreach ($list_kolom as $kolom => $tabel) {
            $this->config_id()
                ->select($kolom . ' as item')
                ->distinct()
                ->from($tabel)
                ->order_by('item');
            if ($cari) {
                $this->db->like($kolom, $cari);
            }
            $sql_kolom[] = $this->db->get_compiled_select();
        }
        $this->config_id('c')
            ->select('u.nama as item')
            ->from('cdesa c')
            ->distinct()
            ->join('cdesa_penduduk cu', 'cu.id_cdesa = c.id', 'left')
            ->join('tweb_penduduk u', 'u.id = cu.id_pend', 'left')
            ->order_by('item');
        if ($cari) {
            $this->db->like('u.nama', $cari);
        }
        $sql_kolom[] = $this->db->get_compiled_select();
        $sql         = '(' . implode(') UNION (', $sql_kolom) . ')';

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        return autocomplete_data_ke_str($data);
    }

    private function search_sql(): void
    {
        $cari = $this->session->cari;
        if ($cari) {
            $this->db
                ->group_start()
                ->like('u.nama', $cari)
                ->or_like('c.nama_pemilik_luar', $cari)
                ->or_like('c.nomor', $cari)
                ->group_end();
        }
    }

    private function main_sql_c_desa(): void
    {
        $this->config_id('c')
            ->from('cdesa c')
            ->join('mutasi_cdesa m', 'm.id_cdesa_masuk = c.id or m.cdesa_keluar = c.id', 'left')
            ->join('persil p', 'p.id = m.id_persil or c.id = p.cdesa_awal', 'left')
            ->join('ref_persil_kelas k', 'k.id = p.kelas', 'left')
            ->join('cdesa_penduduk cu', 'cu.id_cdesa = c.id', 'left')
            ->join('tweb_penduduk u', 'u.id = cu.id_pend', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = u.id_cluster', 'left');
        $this->search_sql();
    }

    public function paging_c_desa($p = 1)
    {
        $this->main_sql_c_desa();
        $jml_data = $this->db
            ->select('COUNT(DISTINCT c.id) AS jml')
            ->get()
            ->row()
            ->jml;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function list_c_desa($offset = 0, $per_page = '', $kecuali = [])
    {
        $kecuali = sql_in_list($kecuali);
        $data    = [];
        $this->main_sql_c_desa();
        $this->db
            ->select('c.*, c.id as id_cdesa, c.created_at as tanggal_daftar, cu.id_pend')
            ->select('u.nik AS nik, u.nama as namapemilik, w.*')
            ->select('(CASE WHEN c.jenis_pemilik = 1 THEN u.nama ELSE c.nama_pemilik_luar END) AS namapemilik')
            ->select('(CASE WHEN c.jenis_pemilik = 1 THEN CONCAT("RT ", w.rt, " / RW ", w.rw, " - ", w.dusun) ELSE c.alamat_pemilik_luar END) AS alamat')
            ->select('COUNT(DISTINCT p.id) AS jumlah')
            ->order_by('cast(c.nomor as unsigned)')
            ->group_by('c.id, cu.id');

        if ($per_page) {
            $this->db->limit($per_page, $offset);
        }
        if ($kecuali) {
            $this->db->where("c.id not in ({$kecuali})");
        }
        $data = $this->db
            ->get()
            ->result_array();
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']     = $j + 1;
            $luas_persil        = $this->jumlah_luas($data[$i]['id_cdesa']);
            $data[$i]['basah']  = $luas_persil['BASAH'];
            $data[$i]['kering'] = $luas_persil['KERING'];
            $j++;
        }

        return $data;
    }

    // Untuk cetak daftar C-Desa, menghitung jumlah luas per kelas persil
    // Perhitungkan kasus suatu C-Desa adalah pemilik awal keseluruhan persil
    public function jumlah_luas($id_cdesa)
    {
        // luas total = jumlah luas setiap persil untuk cdesa
        // luas persil = luas keseluruhan persil (kalau pemilik awal) +/- luas setiap mutasi tergantung masuk atau keluar
        // Jumlahkan sesuai dengan tipe kelas persil (basah atau kering)
        $persil_awal = $this->config_id('p')
            ->select('p.id, luas_persil, k.tipe')
            ->from('persil p')
            ->join('ref_persil_kelas k', 'p.kelas = k.id')
            ->where('cdesa_awal', $id_cdesa)
            ->get()
            ->result_array();

        $luas_persil = [];

        foreach ($persil_awal as $persil) {
            $luas_persil[$persil['tipe']][$persil['id']] = $persil['luas_persil'];
        }

        $list_mutasi = $this->config_id('m')
            ->select('m.id_persil, m.luas, m.cdesa_keluar, k.tipe')
            ->from('mutasi_cdesa m')
            ->join('persil p', 'p.id = m.id_persil')
            ->join('ref_persil_kelas k', 'p.kelas = k.id')
            ->where('m.id_cdesa_masuk', $id_cdesa)
            ->or_where('m.cdesa_keluar', $id_cdesa)
            ->get()
            ->result_array();

        $luas_persil_mutasi = [];

        foreach ($list_mutasi as $key => $mutasi) {
            if ($mutasi['cdesa_keluar'] == $id_cdesa) {
                $luas_persil_mutasi[$mutasi['tipe']][$mutasi['id_persil']] -= $mutasi['luas'];
            } else {
                $luas_persil_mutasi[$mutasi['tipe']][$mutasi['id_persil']] += $mutasi['luas'];
            }
        }

        $luas_total = [];

        foreach ($luas_persil_mutasi as $key => $luas) {
            $luas_total[$key] += array_sum($luas);
        }

        return $luas_total;
    }

    public function get_persil($id_mutasi)
    {
        return $this->config_id('m')
            ->select('p.*, k.kode, k.tipe, k.ndesc')
            ->select('(CASE WHEN p.id_wilayah IS NOT NULL THEN CONCAT("RT ", w.rt, " / RW ", w.rw, " - ", w.dusun) ELSE p.lokasi END) AS alamat')
            ->from('mutasi_cdesa m')
            ->join('cdesa c', 'c.id = m.id_cdesa_masuk', 'left')
            ->join('persil p', 'm.id_persil = p.id', 'left')
            ->join('ref_persil_kelas k', 'k.id = p.kelas', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_wilayah', 'left')
            ->where('m.id', $id_mutasi)
            ->get()
            ->row_array();
    }

    public function get_mutasi($id_mutasi)
    {
        return $this->config_id('m')
            ->select('m.*')
            ->from('mutasi_cdesa m')
            ->where('m.id', $id_mutasi)
            ->get('')
            ->row_array();
    }

    public function get_cdesa($id)
    {
        return $this->config_id('c')
            ->where('c.id', $id)
            ->select('c.*')
            ->select('(CASE WHEN c.jenis_pemilik = 1 THEN u.nama ELSE c.nama_pemilik_luar END) AS namapemilik')
            ->select('(CASE WHEN c.jenis_pemilik = 1 THEN CONCAT("RT ", w.rt, " / RW ", w.rw, " - ", w.dusun) ELSE c.alamat_pemilik_luar END) AS alamat')
            ->from('cdesa c')
            ->join('cdesa_penduduk cu', 'cu.id_cdesa = c.id', 'left')
            ->join('tweb_penduduk u', 'u.id = cu.id_pend', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = u.id_cluster', 'left')
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function simpan_cdesa()
    {
        $data                        = [];
        $data['nomor']               = bilangan_spasi($this->input->post('c_desa'));
        $data['nama_kepemilikan']    = nama($this->input->post('nama_kepemilikan'));
        $data['jenis_pemilik']       = $this->input->post('jenis_pemilik');
        $data['nama_pemilik_luar']   = nama($this->input->post('nama_pemilik_luar'));
        $data['alamat_pemilik_luar'] = strip_tags($this->input->post('alamat_pemilik_luar'));
        if ($id_cdesa = $this->input->post('id')) {
            $data_lama = $this->config_id()
                ->where('id', $id_c_desa)
                ->get('cdesa')
                ->row_array();
            if ($data['nomor'] == $data_lama['nomor']) {
                unset($data['nomor']);
            }
            if ($data['nama_kepemilikan'] == $data_lama['nama_kepemilikan']) {
                unset($data['nama_kepemilikan']);
            }
            $data['updated_by'] = $this->session->user;
            $this->config_id()
                ->where('id', $id_cdesa)
                ->update('cdesa', $data);
        } else {
            $data['created_by'] = $this->session->user;
            $data['updated_by'] = $this->session->user;
            $data['config_id']  = $this->config_id;
            $this->db->insert('cdesa', $data);
            $id_cdesa = $this->db->insert_id();
        }

        if ($this->input->post('jenis_pemilik') == 1) {
            $this->simpan_pemilik($id_cdesa, $this->input->post('id_pend'));
        } else {
            $this->hapus_pemilik($id_cdesa);
        }

        return $id_cdesa;
    }

    private function hapus_pemilik($id_cdesa): void
    {
        $this->config_id()
            ->where('id_cdesa', $id_cdesa)
            ->delete('cdesa_penduduk');
    }

    private function simpan_pemilik($id_cdesa, $id_pend): void
    {
        // Hapus pemilik lama
        $this->hapus_pemilik($id_cdesa);
        // Tambahkan pemiliki baru
        $data              = [];
        $data['id_cdesa']  = $id_cdesa;
        $data['id_pend']   = $id_pend;
        $data['config_id'] = $this->config_id;
        $this->db->insert('cdesa_penduduk', $data);
    }

    public function simpan_mutasi($id_cdesa, $id_mutasi, $post)
    {
        $data                     = [];
        $data['id_persil']        = $post['id_persil'];
        $data['id_cdesa_masuk']   = $id_cdesa;
        $data['no_bidang_persil'] = bilangan($post['no_bidang_persil']) ?: null;
        $data['no_objek_pajak']   = strip_tags($post['no_objek_pajak']);
        $data['tanggal_mutasi']   = $post['tanggal_mutasi'] ? tgl_indo_in($post['tanggal_mutasi']) : null;
        $data['jenis_mutasi']     = $post['jenis_mutasi'] ?: null;
        $data['luas']             = bilangan_titik($post['luas']) ?: null;
        $data['cdesa_keluar']     = bilangan($post['cdesa_keluar']) ?: null;
        $data['keterangan']       = strip_tags($post['keterangan']) ?: null;
        $data['path']             = $post['path'];
        $data['id_peta']          = ($post['area_tanah'] == 1 || $post['area_tanah'] == null) ? $post['id_peta'] : null;
        $data['id_peta']          = $data['id_peta'] ?: null;

        if ($id_mutasi) {
            $outp = $this->config_id()->where('id', $id_mutasi)->update('mutasi_cdesa', $data);
        } else {
            $data['config_id'] = $this->config_id;
            $outp              = $this->db->insert('mutasi_cdesa', $data);
        }
        if ($outp) {
            $_SESSION['success'] = 1;
            $_SESSION['pesan']   = 'Data Persil telah DISIMPAN';
            $data['hasil']       = true;
            $data['id']          = $_POST['id_persil'];
            $data['jenis']       = $_POST['jenis'];
        }

        return $data;
    }

    public function hapus_cdesa($id): void
    {
        $this->config_id()
            ->where('id', $id)
            ->delete('cdesa');
        status_sukses($this->db->affected_rows());
    }

    public function get_pemilik($id_cdesa)
    {
        return $this->config_id('c')
            ->select('p.id, p.nik, p.nama, k.no_kk, w.rt, w.rw, w.dusun')
            ->select('(CASE WHEN c.jenis_pemilik = 1 THEN p.nama ELSE c.nama_pemilik_luar END) AS namapemilik')
            ->select('(CASE WHEN c.jenis_pemilik = 1 THEN CONCAT("RT ", w.rt, " / RW ", w.rw, " - ", w.dusun) ELSE c.alamat_pemilik_luar END) AS alamat')
            ->from('cdesa c')
            ->join('cdesa_penduduk cp', 'c.id = cp.id_cdesa', 'left')
            ->join('tweb_penduduk p', 'p.id = cp.id_pend', 'left')
            ->join('tweb_keluarga k', 'k.id = p.id_kk', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
            ->where('c.id', $id_cdesa)
            ->get()
            ->row_array();
    }

    public function get_list_mutasi($id_cdesa, $id_persil = '')
    {
        $this->config_id()
            ->select('nomor')
            ->where('id', $id_cdesa)
            ->get('cdesa')
            ->row()
            ->nomor;

        $this->lokasi_persil_query();
        $this->config_id('m')
            ->select('m.*, p.nomor, rk.kode as kelas_tanah')
            ->select("IF (m.id_cdesa_masuk = {$id_cdesa}, m.luas, '') AS luas_masuk")
            ->select("IF (m.cdesa_keluar = {$id_cdesa}, m.luas, '') AS luas_keluar")
            ->select("IF (m.jenis_mutasi = '9', 0, 1) AS awal")
            ->from('mutasi_cdesa m')
            ->join('cdesa c', 'c.id = m.id_cdesa_masuk', 'left')
            ->join('persil p', 'p.id = m.id_persil', 'left')
            ->join('ref_persil_kelas rk', 'p.kelas = rk.id', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_wilayah', 'left')
            ->group_start()
            ->where('m.id_cdesa_masuk', $id_cdesa)
            ->or_where('m.cdesa_keluar', $id_cdesa)
            ->group_end()
            ->order_by('awal, tanggal_mutasi');
        if ($id_persil) {
            $this->db->where('m.id_persil', $id_persil);
        }

        return $this->db->get()->result_array();
    }

    private function lokasi_persil_query(): void
    {
        $this->db->select("(CASE WHEN p.id_wilayah = w.id THEN CONCAT(
            (CASE WHEN w.rt != '0' THEN CONCAT('RT ', w.rt, ' / ') ELSE '' END),
            (CASE WHEN w.rw != '0' THEN CONCAT('RW ', w.rw, ' - ') ELSE '' END),
            w.dusun
        ) ELSE CASE WHEN p.lokasi IS NOT NULL THEN p.lokasi ELSE '=== Lokasi Tidak Ditemukan ===' END END) AS alamat");
    }

    public function get_list_persil($id_cdesa)
    {
        $this->lokasi_persil_query();
        $this->config_id('p')
            ->select('p.*, rk.kode as kelas_tanah')
            ->select('COUNT(m.id) as jml_mutasi')
            ->from('persil p')
            ->join('mutasi_cdesa m', 'p.id = m.id_persil', 'left')
            ->join('ref_persil_kelas rk', 'p.kelas = rk.id', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_wilayah', 'left')
            ->group_start()
            ->where('m.id_cdesa_masuk', $id_cdesa)
            ->or_where('m.cdesa_keluar', $id_cdesa)
            ->or_where('p.cdesa_awal', $id_cdesa)
            ->group_end()
            ->group_by('p.id')
            ->order_by('cast(p.nomor as unsigned), nomor_urut_bidang');

        return $this->db->get()->result_array();
    }

    // TODO: ganti ke impor cdesa
    public function impor_persil(): void
    {
        $this->load->library('Spreadsheet_Excel_Reader');
        $data = new Spreadsheet_Excel_Reader($_FILES['persil']['tmp_name']);

        $sheet = 0;
        $baris = $data->rowcount($sheet_index = $sheet);
        $data->colcount($sheet_index = $sheet);

        // TODO: Cek apakah ini masih digunakan
        for ($i = 2; $i <= $baris; $i++) {
            $nik            = $data->val($i, 2, $sheet);
            $upd['id_pend'] = $this->config_id()
                ->select('id')
                ->where('nik', $nik)
                ->get('tweb_penduduk')
                ->row()
                ->id;
            $upd['nama']                 = $data->val($i, 3, $sheet);
            $upd['persil_jenis_id']      = $data->val($i, 4, $sheet);
            $upd['id_clusterdesa']       = $data->val($i, 5, $sheet);
            $upd['luas']                 = $data->val($i, 6, $sheet);
            $upd['kelas']                = $data->val($i, 7, $sheet);
            $upd['no_sppt_pbb']          = $data->val($i, 8, $sheet);
            $upd['persil_peruntukan_id'] = $data->val($i, 9, $sheet);
            $outp                        = $this->db->insert('data_persil', $upd);
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_cetak_mutasi($id_cdesa, $tipe = '')
    {
        // Mutasi masuk
        $this->config_id('m')
            ->select('m.tanggal_mutasi, m.luas, m.cdesa_keluar as id_cdesa_keluar, p.id as id_persil, p.nomor as nopersil, p.nomor_urut_bidang, 0 as cdesa_awal, p.luas_persil, c.nomor as cdesa_masuk, 0 as cdesa_keluar, rk.kode as kelas_tanah, rm.nama as sebabmutasi')
            ->from('mutasi_cdesa m')
            ->join('persil p', 'p.id = m.id_persil', 'left')
            ->join('ref_persil_kelas rk', 'p.kelas = rk.id', 'left')
            ->join('ref_persil_mutasi rm', 'm.jenis_mutasi = rm.id', 'left')
            ->join('cdesa c', 'c.id = m.cdesa_keluar', 'left')
            ->where('m.id_cdesa_masuk', $id_cdesa)
            ->where('m.jenis_mutasi <> 9')
            ->where('rk.tipe', $tipe);
        $sql_masuk = $this->db->get_compiled_select();
        // Mutasi keluar
        $this->config_id('m')
            ->select('m.tanggal_mutasi, m.luas, m.cdesa_keluar as id_cdesa_keluar, p.id as id_persil, p.nomor as nopersil, p.nomor_urut_bidang, 0 as cdesa_awal, p.luas_persil, 0 as cdesa_masuk, c.nomor as cdesa_keluar, rk.kode as kelas_tanah, rm.nama as sebabmutasi')
            ->from('mutasi_cdesa m')
            ->join('persil p', 'p.id = m.id_persil', 'left')
            ->join('ref_persil_kelas rk', 'p.kelas = rk.id', 'left')
            ->join('ref_persil_mutasi rm', 'm.jenis_mutasi = rm.id', 'left')
            ->join('cdesa c', 'c.id = m.id_cdesa_masuk', 'left')
            ->where('m.cdesa_keluar', $id_cdesa)
            ->where('rk.tipe', $tipe);
        $sql_keluar = $this->db->get_compiled_select();
        // Persil milik awal
        $this->config_id('p')
            ->select('"" as tanggal_mutasi, 0 as luas, 0 as id_cdesa_keluar, p.id as id_persil, p.nomor as nopersil, p.nomor_urut_bidang, p.cdesa_awal, p.luas_persil, 0 as cdesa_masuk, 0 as cdesa_keluar, rk.kode as kelas_tanah, "" as sebabmutasi')
            ->from('persil p')
            ->join('ref_persil_kelas rk', 'p.kelas = rk.id', 'left')
            ->where('p.cdesa_awal', $id_cdesa)
            ->where('rk.tipe', $tipe);
        $sql_cdesa_awal = $this->db->get_compiled_select();
        $sql            = '(' . $sql_masuk . ') UNION (' . $sql_keluar . ') UNION (' . $sql_cdesa_awal . ') ORDER BY nopersil, nomor_urut_bidang, cdesa_awal DESC, tanggal_mutasi';
        $data           = $this->db->query($sql)->result_array();

        $persil_ini = 0;

        foreach ($data as $key => $mutasi) {
            if ($persil_ini != $mutasi['id_persil'] && $id_cdesa == $mutasi['cdesa_awal']) {
                // Cek kalau memiliki keseluruhan persil sekali saja untuk setiap persil
                // Data terurut berdasarkan persil
                $data[$key]['luas']   = $data[$key]['luas_persil'];
                $data[$key]['mutasi'] = '<p>Memiliki keseluruhan persil sejak awal</p>';
            } else {
                if ($persil_ini == $mutasi['id_persil']) {
                    // Tidak ulangi info persil
                    $data[$key]['nopersil']    = '';
                    $data[$key]['kelas_tanah'] = '';
                }
                $data[$key]['mutasi'] = $this->format_mutasi($id_cdesa, $mutasi);
            }
            if ($persil_ini != $mutasi['id_persil']) {
                $persil_ini = $mutasi['id_persil'];
            }
        }

        return $data;
    }

    private function format_mutasi($id_cdesa, $mutasi)
    {
        $keluar = $mutasi['id_cdesa_keluar'] == $id_cdesa;
        $div    = $keluar ? 'class="out"' : null;
        $hasil  = "<p {$div}>";
        $hasil .= $mutasi['sebabmutasi'];
        $hasil .= $keluar ? ' ke C No ' . str_pad($mutasi['cdesa_keluar'], 4, '0', STR_PAD_LEFT) : ' dari C No ' . str_pad($mutasi['cdesa_masuk'], 4, '0', STR_PAD_LEFT);
        $hasil .= empty($mutasi['luas']) ? null : ', Seluas ' . number_format($mutasi['luas']) . ' m<sup>2</sup>, ';
        $hasil .= empty($mutasi['tanggal_mutasi']) ? null : tgl_indo_out($mutasi['tanggal_mutasi']) . '<br />';
        $hasil .= empty($mutasi['keterangan']) ? null : $mutasi['keterangan'];

        return $hasil . '</p>';
    }

    // TODO: apakah bisa diambil dari penduduk_model?
    public function get_penduduk($id, $nik = false)
    {
        if ($nik) {
            $this->db->where('p.nik', $id);
        } else {
            $this->db->where('p.id', $id);
        }

        return $this->config_id('p')
            ->select('p.id, p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun')
            ->from('tweb_penduduk p')
            ->join('tweb_keluarga k', 'k.id = p.id_kk', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
            ->get()
            ->row_array();
    }

    // TODO: apakah bisa diambil dari penduduk_model?
    public function list_penduduk()
    {
        $query = $this->config_id('p')
            ->select('p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun')
            ->from('tweb_penduduk p')
            ->join('tweb_keluarga k', 'k.id = p.id_kk', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
            ->order_by('nama')
            ->get();

        $data = $query->result_array();

        if ($query->num_rows() > 0) {
            $j       = 0;
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                if ($data[$i]['nik'] != '') {
                    $data1[$j]['id']   = $data[$i]['nik'];
                    $data1[$j]['nik']  = $data[$i]['nik'];
                    $data1[$j]['nama'] = strtoupper($data[$i]['nama']) . ' [NIK: ' . $data[$i]['nik'] . '] / [NO KK: ' . $data[$i]['no_kk'] . ']';
                    $data1[$j]['info'] = 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . ' - ' . strtoupper($data[$i]['dusun']);
                    $j++;
                }
            }

            return $data1;
        }

        return false;
    }
}
