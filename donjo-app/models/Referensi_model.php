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

defined('BASEPATH') || exit('No direct script access allowed');

// Model ini digunakan untuk data referensi statis yg tidak disimpan pd database atau sebagai referensi global

// TODO: dihapus setelah modul covid, keluarga_model.php dan penduduk_model.php dihapus
class Referensi_model extends MY_Model
{
    public function list_nama($tabel)
    {
        $data = $this->list_data($tabel);
        $list = [];

        foreach ($data as $value) {
            $list[$value['id']] = $value['nama'];
        }

        return $list;
    }

    public function list_data($tabel, $kecuali = '', $termasuk = null)
    {
        if ($kecuali) {
            $this->db->where("id NOT IN ({$kecuali})");
        }

        if ($termasuk) {
            $this->db->where("id IN ({$termasuk})");
        }

        return $this->db->select('*')->order_by('id')->get($tabel)->result_array();
    }

    public function list_ktp_el()
    {
        return array_flip(unserialize(KTP_EL));
    }

    public function list_status_rekam()
    {
        $data = $this->db->select('status_rekam, LOWER(nama) as nama')
            ->get('tweb_status_ktp')->result_array();

        return array_combine(array_column($data, 'status_rekam'), array_column($data, 'nama'));
    }

    public function list_by_id($tabel, $id = 'id')
    {
        $data = $this->config_id()
            ->order_by($id)
            ->get($tabel)
            ->result_array();

        return array_combine(array_column($data, $id), $data);
    }

    public function list_ref($stat = STAT_PENDUDUK)
    {
        return unserialize($stat);
    }

    public function list_ref_flip($s_array)
    {
        return array_flip(unserialize($s_array));
    }

    public function impor_list_data($tabel, $tambahan = [], $kecuali = '', $termasuk = null)
    {
        $data = $this->list_data($tabel, $kecuali, $termasuk);
        $data = array_flip(array_combine(array_column($data, 'id'), array_column($data, 'nama')));

        return array_change_key_case(array_merge($data, $tambahan));
    }

    public function jenis_peraturan_desa()
    {
        $dafault = $this->list_ref(JENIS_PERATURAN_DESA);

        return collect($dafault)->transform(static fn ($item) => str_replace(['Desa', 'desa'], ucwords(setting('sebutan_desa')), $item))->unique()->values();
    }
}
