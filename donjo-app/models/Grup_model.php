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

class Grup_model extends MY_Model
{
    public const KECUALI = [1, 2, 3, 4];

    protected $table = 'user_grup';

    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', $this->table);
    }

    private function search_sql()
    {
        if ($cari = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('g.nama', $cari)
                ->group_end();
        }
    }

    private function filter_sql()
    {
        if ($filter = $this->session->jenis) {
            $this->db->where('jenis', $filter);
        }
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql()
    {
        $this->db
            ->from('user_grup g');
        $this->search_sql();
        $this->filter_sql();
    }

    public function paging($page_number = 1, $o = 0)
    {
        $this->list_data_sql();
        $jml_data = $this->db
            ->get()
            ->num_rows();

        return $this->paginasi($page_number, $jml_data);
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->list_data_sql();
        //Ordering
        switch ($o) {
            case 1: $order = 'g.nama ASC';
                break;

            case 2: $order = 'g.nama DESC';
                break;

            default:$order = 'g.nama ASC';
        }
        $data = $this->db
            ->select('g.*')
            ->select('(select COUNT(id) from user where id_grup = g.id) as jml_pengguna')
            ->select('(case when jenis = 1 then 0 else 1 end) as boleh_hapus')
            ->order_by($order)
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $offset + $i + 1;
            if ($data[$i]['jml_pengguna'] > 0) {
                $data[$i]['boleh_hapus'] = 0;
            }
        }

        return $data;
    }

    /**
     * Insert data baru ke tabel surat_keluar
     *
     * @return void
     */
    public function insert()
    {
        // Ambil semua data dari var. global $_POST
        $data = [
            'nama'       => $this->input->post('nama'),
            'jenis'      => 2, // grup tambahan
            'created_by' => $this->session->user,
            'updated_by' => $this->session->user,
        ];
        $outp = $this->db->insert($this->table, $data);
        $outp = $outp && $this->simpan_akses($this->db->insert_id());

        status_sukses($outp); //Tampilkan Pesan
    }

    private function simpan_akses($id)
    {
        // Simpan data hak akses per modul; hapus dan ganti semua
        $outp  = $this->db->where('id_grup', $id)->delete('grup_akses');
        $modul = $this->input->post('modul');
        $data  = [];

        for ($i = 0; $i < count($modul['id']); $i++) {
            $id_modul = $modul['id'][$i];
            $akses    = [
                'id_grup'  => $id,
                'id_modul' => $id_modul,
            ];
            $akses_baca     = $modul['akses_baca'][$id_modul] ? 1 : 0;
            $akses_ubah     = $modul['akses_ubah'][$id_modul] ? 2 : 0;
            $akses_hapus    = $modul['akses_hapus'][$id_modul] ? 4 : 0;
            $akses['akses'] = $akses_baca + $akses_ubah + $akses_hapus;
            $data[]         = $akses;
        }
        $outp = $this->db->insert_batch('grup_akses', $data);
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $outp;
    }

    /**
     * Update data di tabel grup
     *
     * @param int $id ID grup
     *
     * @return void
     */
    public function update($id)
    {
        $data = [
            'nama'       => $this->input->post('nama'),
            'updated_by' => $this->session->user,
        ];
        $outp = $this->db
            ->where('id', $id)
            ->where_not_in('id', static::KECUALI)
            ->update($this->table, $data);
        $outp = $outp && $this->simpan_akses($id);

        status_sukses($outp);
    }

    public function get_grup($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    /**
     * Hapus record grup
     *
     * @param string $id    ID grup
     * @param mixed  $semua
     *
     * @return void
     */
    public function delete($id, $semua = false)
    {
        if (! $semua) {
            session_error_clear();
        }

        $outp = $this->db
            ->where('id', $id)
            ->where_not_in('id', static::KECUALI)
            ->delete($this->table);

        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp);
    }

    public function delete_all()
    {
        session_error_clear();

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, true);
        }
    }

    public function list_id_grup()
    {
        $list = $this->db->select('id')->get('user_grup')->result_array();

        return array_column($list, 'id');
    }

    public function list_jenis_grup()
    {
        return [
            ['id' => 1, 'nama' => 'System'],
            ['id' => 2, 'nama' => 'Tambahan'],
        ];
    }

    public function grup_akses($id_grup)
    {
        return $this->db
            ->select('m.*')
            ->select('if(a.akses & 1 = 1, 1, 0) as akses_baca')
            ->select('if(a.akses & 2 = 2, 1, 0) as akses_ubah')
            ->select('if(a.akses & 4 = 4, 1, 0) as akses_hapus')
            ->select('if(a.akses > 0, 1, 0) as ada_akses')
            ->from('setting_modul m')
            ->join('grup_akses a', "a.id_modul = m.id and a.id_grup = {$id_grup}", 'left')
            ->where('m.parent', 0)
            ->order_by('m.urut')
            ->get()
            ->result_array();
    }

    private function get_submodul($grup, $modul)
    {
        return $this->db
            ->select('sub.id, sub.modul, sub.url')
            ->select('if(a.akses & 1 = 1, 1, 0) as akses_baca')
            ->select('if(a.akses & 2 = 2, 1, 0) as akses_ubah')
            ->select('if(a.akses & 4 = 4, 1, 0) as akses_hapus')
            ->select('if(a.akses > 0, 1, 0) as ada_akses')
            ->from('setting_modul p')
            ->join('setting_modul sub', 'sub.parent = p.id')
            ->join('grup_akses a', "sub.id = a.id_modul and a.id_grup = {$grup}", 'left')
            ->where('p.id', $modul)
            ->order_by('sub.urut')
            ->get()->result_array();
    }

    public function akses_submodul($grup)
    {
        $parent = $this->db
            ->select('id')
            ->where('parent', 0)
            ->get('setting_modul')->result_array();
        $parent = array_column($parent, 'id');

        $data = [];

        foreach ($parent as $modul) {
            $data[$modul] = $this->get_submodul($grup, $modul);
            // Juga ambil sub-submodul. Asumsi hanya ada sampai dua tingkat submodul saja
            $subparent = array_column($data[$modul], 'id');

            foreach ($subparent as $submodul) {
                $sub_sub = $this->get_submodul($grup, $submodul);
                if (! empty($sub_sub)) {
                    $data[$modul] = array_merge($data[$modul], $sub_sub);
                }
            }
        }

        return array_filter($data);
    }

    private function get_hak_akses($grup)
    {
        $hak_akses = $this->db
            ->select('if (m.url <> "", m.url, concat("Menu ", m.modul)) as url, a.akses')
            ->select('if(a.akses & 1 = 1 or m.parent = 0, 1, 0) as "b"')
            ->select('if(a.akses & 2 = 2, 1, 0) as "u"')
            ->select('if(a.akses & 4 = 4, 1, 0) as "h"')
            ->from('grup_akses a')
            ->join('setting_modul m', 'm.id = a.id_modul')
            ->where('id_grup', $grup)
            ->order_by('akses DESC')
            ->get()->result_array();

        // Hilangkan kolom modul
        $akses_saja = $hak_akses;
        delete_col($akses_saja, 0);

        // Buat array dengan key modul
        return array_combine(array_column($hak_akses, 'url'), $akses_saja);
    }

    /*
      Memilih modul awal yg dapat diakses
      Digunakan menentukan modul awal di donjo-app/controllers/Main.php
    */
    public function modul_awal($grup)
    {
        if (! $this->session->hak_akses_url) {
            $hak_akses                    = $this->get_hak_akses($grup);
            $this->session->hak_akses_url = $hak_akses;
        }
        $modul = array_keys($this->session->hak_akses_url);

        return $this->db
            ->select('url')
            ->where('aktif', 1)
            ->where_in('url', $modul)
            ->limit(1)
            ->get('setting_modul')
            ->row()->url;
    }

    /*
      Cek hak akses url modul untuk mengaktifkan menu navigasi utama.
      Dipanggil dari Modul_model.php
    */
    public function ada_akses_url($grup, $url_modul = '', $akses = '')
    {
        if (! $this->session->hak_akses_url) {
            $hak_akses                    = $this->get_hak_akses($grup);
            $this->session->hak_akses_url = $hak_akses;
        }

        return $this->session->hak_akses_url[$url_modul][$akses];
    }

    /*
      Cek hak akses untuk mengakses controller.
      Dipanggil dari Admin_Controller
    */
    public function ada_akses($grup, $controller, $akses)
    {
        if (! $this->session->hak_akses) {
            $hak_akses = $this->get_hak_akses($grup);

            // Simpan akses controller di session, karena hak akses adalah menurut controller dan
            // belum berdasarkan masing2 aksi. Gunakan hak akses tertinggi per controller.
            // $hak_akses sudah terurut hak akses tertinggi duluan
            $akses_controller = [];

            foreach ($hak_akses as $url => $akses_url) {
                $controller_url = explode('/', $url)[0];
                if ($akses_controller[$controller_url]) {
                    continue;
                }
                $akses_controller[$controller_url] = $akses_url;
            }

            $this->session->hak_akses = $akses_controller;
        }

        return $this->session->hak_akses[$controller][$akses];
    }
}
