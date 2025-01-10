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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Libraries\Paging;
use App\Models\Suplemen;

defined('BASEPATH') || exit('No direct script access allowed');

class Suplemen_model extends MY_Model
{
    protected $table = 'suplemen';

    // TODO:: Hapus ini, masih dipanggil di model ini
    private function list_data_sql(): void
    {
        $sasaran = $this->session->sasaran;

        if ($sasaran > 0) {
            $this->db->where('s.sasaran', $sasaran);
        }

        $this->config_id('s')
            ->from('suplemen s')
            ->join('suplemen_terdata st', 's.id = st.id_suplemen', 'left');

        $this->search_sql();
    }

    // TODO:: Hapus ini, masih dipanggil di modul menu
    public function list_data($order_by = 1, $offset = 0, $limit = 0)
    {
        $this->list_data_sql();
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        return $this->db
            ->select('s.*')
            ->select('COUNT(st.id) AS jml')
            ->order_by('s.nama')
            ->group_by('s.id')
            ->get()
            ->result_array();
    }

    // TODO:: Hapus ini, masih dipanggil di helper menu_slug
    public function get_suplemen($id)
    {
        $data = Suplemen::select('suplemen.*')
            ->selectRaw('COUNT(suplemen_terdata.id) AS jml')
            ->leftJoin('suplemen_terdata', 'suplemen_terdata.id_suplemen', '=', 'suplemen.id')
            ->where('suplemen.id', $id)
            ->groupBy('suplemen.id')
            ->first();

        return $data ? $data->toArray() : null;
    }

    // TODO:: Hapus ini, masih dipanggil di halaman web suplemen
    public function get_rincian($p, $suplemen_id)
    {
        $suplemen = $this->config_id()->where('id', $suplemen_id)->get($this->table)->row_array();

        if (null === $suplemen) {
            return null;
        }

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

    // TODO:: Hapus ini, masih dipanggil di model ini
    private function paging($p)
    {
        $jml_data = $this->db
            ->select('COUNT(s.id) as jumlah')
            ->get()
            ->row()
            ->jumlah;

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    // TODO:: Hapus ini, masih dipanggil di model ini
    private function get_penduduk_terdata_sql($suplemen_id): void
    {
        // Data Penduduk
        $this->config_id('s')
            ->from('suplemen_terdata s')
            ->join('tweb_penduduk o', ' s.penduduk_id = o.id', 'left')
            ->join('tweb_keluarga k', 'k.id = o.id_kk', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = o.id_cluster', 'left')
            ->where('s.id_suplemen', $suplemen_id);
    }

    // TODO:: Hapus ini, masih dipanggil di model ini
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
            ->select('s.*, s.penduduk_id, o.nik, o.nama, o.tempatlahir, o.tanggallahir, o.sex, k.no_kk, w.rt, w.rw, w.dusun')
            ->select('(case when (o.id_kk is null) then o.alamat_sekarang else k.alamat end) AS alamat');
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
            $data    = $query->result_array();
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['terdata_info']  = $data[$i]['no_kk'];
                $data[$i]['terdata_plus']  = $data[$i]['nik'];
                $data[$i]['terdata_nama']  = strtoupper($data[$i]['nama']);
                $data[$i]['tempat_lahir']  = strtoupper($data[$i]['tempatlahir']);
                $data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
                $data[$i]['sex']           = ($data[$i]['sex'] == 1) ? 'LAKI-LAKI' : 'PEREMPUAN';
                $data[$i]['info']          = strtoupper($data[$i]['alamat'] . ' ' . 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . ' - ' . setting('sebutan_dusun') . ' ' . $data[$i]['dusun']);
            }
            $hasil['terdata'] = $data;
        }

        return $hasil;
    }

    // TODO:: Hapus ini, masih dipanggil di model ini
    private function get_kk_terdata_sql($suplemen_id): void
    {
        // Data KK
        $this->config_id('s')
            ->from('suplemen_terdata s')
            ->join('tweb_keluarga o', 's.keluarga_id = o.id', 'left')
            ->join('tweb_penduduk q', 'o.nik_kepala = q.id', 'left')
            ->join('tweb_wil_clusterdesa w', 'w.id = q.id_cluster', 'left')
            ->where('s.id_suplemen', $suplemen_id);
    }

    // TODO:: Hapus ini, masih dipanggil di model ini
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
            ->select('s.*, s.keluarga_id, o.no_kk, s.id_suplemen, o.nik_kepala, o.alamat, q.nik, q.nama, q.tempatlahir, q.tanggallahir, q.sex, w.rt, w.rw, w.dusun');
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
            $data    = $query->result_array();
            $counter = count($data);

            for ($i = 0; $i < $counter; $i++) {
                $data[$i]['terdata_info']  = $data[$i]['nik'];
                $data[$i]['terdata_plus']  = $data[$i]['no_kk'];
                $data[$i]['terdata_nama']  = strtoupper($data[$i]['nama']);
                $data[$i]['tempat_lahir']  = strtoupper($data[$i]['tempatlahir']);
                $data[$i]['tanggal_lahir'] = tgl_indo($data[$i]['tanggallahir']);
                $data[$i]['sex']           = ($data[$i]['sex'] == 1) ? 'LAKI-LAKI' : 'PEREMPUAN';
                $data[$i]['info']          = strtoupper($data[$i]['alamat'] . ' ' . 'RT/RW ' . $data[$i]['rt'] . '/' . $data[$i]['rw'] . ' - ' . setting('sebutan_dusun') . ' ' . $data[$i]['dusun']);
            }
            $hasil['terdata'] = $data;
        }

        return $hasil;
    }

    // TODO:: Hapus ini, masih dipanggil di model ini
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

    // TODO:: Hapus ini, masih dipanggil di model ini
    private function autocomplete($sasaran)
    {
        switch ($sasaran) {
            case '1':
                //# sasaran penduduk
                $data = $this->config_id('s')
                    ->select('p.nama')
                    ->from('suplemen_terdata s')
                    ->join('tweb_penduduk p', 'p.id = s.penduduk_id', 'left')
                    ->where('s.sasaran', $sasaran)
                    ->group_by('p.nama')
                    ->get()
                    ->result_array();
                break;

            case '2':
                //# sasaran keluarga / KK
                $data = $this->config_id('s')
                    ->select('p.nama')
                    ->from('suplemen_terdata s')
                    ->join('tweb_keluarga k', 'k.id = s.keluarga_id', 'left')
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
}
