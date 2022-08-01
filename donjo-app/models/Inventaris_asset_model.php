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

class Inventaris_asset_model extends CI_Model
{
    protected $table        = 'inventaris_asset';
    protected $table_mutasi = 'mutasi_inventaris_asset';
    protected $mutasi_key   = 'id_inventaris_asset';

    public function count_reg()
    {
        $this->db->select('count(id) AS count');
        $this->db->from($this->table);

        return $this->db->get()->row();
    }

    public function list_inventaris_kd_register()
    {
        $this->db->select($this->table . '.register');
        $this->db->from($this->table);

        return $this->db->get()->result();
    }

    public function list_inventaris()
    {
        $this->db
            ->select('u.*, m.id as mutasi')
            ->from("{$this->table} u")
            ->join("{$this->table_mutasi} m", "m.{$this->mutasi_key} = u.id", 'left')
            ->where('u.visible', 1);

        return $this->db->get()->result();
    }

    public function sum_inventaris()
    {
        $this->db->select_sum('harga');
        $this->db->where($this->table . '.visible', 1);
        $this->db->where($this->table . '.status', 0);
        $result = $this->db->get($this->table)->row();

        return $result->harga;
    }

    public function sum_print($tahun)
    {
        $this->db->select_sum('harga');
        $this->db->where($this->table . '.visible', 1);
        $this->db->where($this->table . '.status', 0);
        if ($tahun != 1) {
            $this->db->where($this->table . '.tahun_pengadaan', $tahun);
        }
        $result = $this->db->get($this->table)->row();

        return $result->harga;
    }

    public function list_mutasi_inventaris()
    {
        $this->db->select('mutasi_inventaris_asset.id as id,mutasi_inventaris_asset.*, inventaris_asset.nama_barang, inventaris_asset.kode_barang, inventaris_asset.tahun_pengadaan, inventaris_asset.register');
        $this->db->from($this->table_mutasi);
        $this->db->where($this->table_mutasi . '.visible', 1);
        // $this->db->where('status_mutasi', 'Hapus');
        $this->db->join($this->table, $this->table . '.id = ' . $this->table_mutasi . '.id_inventaris_asset', 'left');

        return $this->db->get()->result();
    }

    public function add($data)
    {
        $this->db->insert($this->table, array_filter($data));
        $id = $this->db->insert_id();

        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function add_mutasi($data)
    {
        $this->db->insert($this->table_mutasi, array_filter($data));
        $id            = $this->db->insert_id();
        $status_ivntrs = ($data['status_mutasi'] === 'Hapus') ? 1 : 0;  // status 1 = barang dihapus
        $this->db->update($this->table, ['status' => $status_ivntrs], ['id' => $data['id_inventaris_asset']]);

        return $this->db->get_where($this->table_mutasi, ['id' => $id])->row();
    }

    public function view($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($this->table . '.id', $id);

        return $this->db->get()->row();
    }

    public function view_mutasi($id)
    {
        $this->db->select('mutasi_inventaris_asset.id as id,mutasi_inventaris_asset.*, inventaris_asset.nama_barang, inventaris_asset.kode_barang, inventaris_asset.tahun_pengadaan, inventaris_asset.register');
        $this->db->from($this->table_mutasi);
        $this->db->where($this->table_mutasi . '.id', $id);
        $this->db->join($this->table, $this->table . '.id = ' . $this->table_mutasi . '.id_inventaris_asset', 'left');

        return $this->db->get()->row();
    }

    public function edit_mutasi($id)
    {
        $this->db->select('mutasi_inventaris_asset.id as id,mutasi_inventaris_asset.*, inventaris_asset.nama_barang, inventaris_asset.kode_barang, inventaris_asset.tahun_pengadaan, inventaris_asset.register');
        $this->db->from($this->table_mutasi);
        $this->db->where($this->table_mutasi . '.id', $id);
        $this->db->join($this->table, $this->table . '.id = ' . $this->table_mutasi . '.id_inventaris_asset', 'left');

        return $this->db->get()->row();
    }

    public function delete($id)
    {
        return $this->db->update($this->table, ['visible' => 0], ['id' => $id]);
    }

    public function delete_mutasi($id)
    {
        return $this->db->update($this->table_mutasi, ['visible' => 0], ['id' => $id]);
    }

    public function update($id, $data)
    {
        $id = $this->input->post('id');

        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function update_mutasi($id, $data)
    {
        $id = $this->input->post('id');

        return $this->db->update($this->table_mutasi, $data, ['id' => $id]);
    }

    public function cetak($tahun)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($this->table . '.status', 0);
        $this->db->where($this->table . '.visible', 1);
        if ($tahun != 1) {
            $this->db->where($this->table . '.tahun_pengadaan', $tahun);
        }
        $this->db->order_by($this->table . '.tahun_pengadaan', 'asc');

        return $this->db->get()->result();
    }
}
