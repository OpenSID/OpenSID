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

class Inventaris_tanah_model extends MY_Model
{
    protected $table        = 'inventaris_tanah';
    protected $table_mutasi = 'mutasi_inventaris_tanah';
    protected $mutasi_key   = 'id_inventaris_tanah';

    public function count_reg()
    {
        $this->config_id();

        return $this->db
            ->select('count(id) AS count')
            ->from($this->table)
            ->get()
            ->row();
    }

    public function list_inventaris_kd_register()
    {
        $this->config_id();

        return $this->db->select("{$this->table}.register")
            ->from($this->table)
            ->get()
            ->result();
    }

    public function list_inventaris()
    {
        $this->config_id('u');

        return $this->db->select('u.*, m.id as mutasi')
            ->from("{$this->table} u")
            ->join("{$this->table_mutasi} m", "m.{$this->mutasi_key} = u.id", 'left')
            ->where('u.visible', 1)
            ->get()
            ->result();
    }

    public function list_mutasi_inventaris()
    {
        $this->config_id($this->table_mutasi);
        $this->scope_select();

        return $this->db
            ->from($this->table_mutasi)
            ->join($this->table, "{$this->table}.id = {$this->table_mutasi}.{$this->mutasi_key}", 'left')
            ->where("{$this->table_mutasi}.visible", 1)
            ->get()
            ->result();
    }

    public function sum_inventaris()
    {
        $this->config_id();

        return $this->db->select_sum('harga')
            ->from($this->table)
            ->where("{$this->table}.visible", 1)
            ->where("{$this->table}.status", 0)
            ->get()
            ->row()->harga;
    }

    public function sum_print($tahun)
    {
        $this->config_id();
        $this->scope_filter($tahun);

        $this->db->select_sum('harga');
        $this->db->where("{$this->table}.visible", 1);
        $this->db->where("{$this->table}.status", 0);

        return $this->db->get($this->table)->row()->harga;
    }

    public function add($data)
    {
        $data['config_id'] = identitas('id');

        $this->db->insert($this->table, array_filter($data));
        $id = $this->db->insert_id();

        return $this->config_id()->get_where($this->table, ['id' => $id])->row();
    }

    public function add_mutasi($data)
    {
        $data['config_id'] = identitas('id');

        $this->db->insert($this->table_mutasi, array_filter($data));
        $id            = $this->db->insert_id();
        $status_ivntrs = ($data['status_mutasi'] === 'Hapus') ? 1 : 0;  // status 1 artinya barang yang dihapus dari asset

        $this->config_id()->update($this->table, ['status' => $status_ivntrs], ['id' => $data[$this->mutasi_key]]);

        return $this->config_id()->get_where($this->table_mutasi, ['id' => $id])->row();
    }

    public function view($id)
    {
        $this->config_id();

        return $this->db->select('*')
            ->from($this->table)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function view_mutasi($id)
    {
        $this->config_id($this->table_mutasi);
        $this->scope_select();

        return $this->db
            ->from($this->table_mutasi)
            ->join($this->table, "{$this->table}.id = {$this->table_mutasi}.{$this->mutasi_key}", 'left')
            ->where("{$this->table_mutasi}.id", $id)
            ->get()
            ->row();
    }

    public function edit_mutasi($id)
    {
        return $this->view_mutasi($id);
    }

    public function delete($id)
    {
        return $this->config_id()->update($this->table, ['visible' => 0], ['id' => $id]);
    }

    public function delete_mutasi($id)
    {
        return $this->config_id()->update($this->table_mutasi, ['visible' => 0], ['id' => $id]);
    }

    public function update($id, $data)
    {
        $id = $this->input->post('id');

        return $this->config_id()->update($this->table, $data, ['id' => $id]);
    }

    public function update_mutasi($id, $data)
    {
        $id = $this->input->post('id');

        return $this->config_id()->update($this->table_mutasi, $data, ['id' => $id]);
    }

    public function cetak($tahun)
    {
        $this->config_id();
        $this->scope_filter($tahun);
        $this->scope_order();

        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where("{$this->table}.visible", 1);
        $this->db->where("{$this->table}.status", 0);

        return $this->db->get()->result();
    }

    protected function scope_select()
    {
        $this->db
            ->select("{$this->table_mutasi}.id as id")
            ->select("{$this->table_mutasi}.*")
            ->select("{$this->table}.nama_barang")
            ->select("{$this->table}.kode_barang")
            ->select("{$this->table}.tahun_pengadaan")
            ->select("{$this->table}.register");
    }

    protected function scope_filter($tahun)
    {
        if ($tahun != 1) {
            $this->db->where('tahun_pengadaan', $tahun);
        }
    }

    protected function scope_order()
    {
        $this->db->order_by('tahun_pengadaan', 'asc');
    }
}
