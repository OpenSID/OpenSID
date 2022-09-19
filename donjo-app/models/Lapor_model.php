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

class Lapor_model extends CI_Model
{
    // Dipakai di penduduk, surat master dan surat fmandiri
    public function get_surat_ref_all()
    {
        $this->db->select('*')
            ->from('ref_syarat_surat');
        $query = $this->db->get();

        return $query->result_array();
    }

    // Dipakai di surat master
    public function get_current_surat_ref($id)
    {
        $this->db->select('*')
            ->from('tweb_surat_format')
            ->join('syarat_surat', 'tweb_surat_format.id = syarat_surat.surat_format_id')
            ->join('ref_syarat_surat', 'ref_syarat_surat.ref_syarat_id = syarat_surat.ref_syarat_id')
            ->where('syarat_surat.surat_format_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }

    // Dipakai di surat master
    public function update_syarat_surat($surat_format_id, $syarat_surat, $mandiri = 0)
    {
        if (empty($surat_format_id)) {
            return false;
        }

        $this->hapus_syarat($surat_format_id);

        if ($mandiri == 1) {
            // Tambahkan syarat baru yg dipilih
            foreach ($syarat_surat as $syarat) {
                $data   = ['ref_syarat_id' => $syarat, 'surat_format_id' => $surat_format_id];
                $result = $this->db->insert('syarat_surat', $data);
            }
        }
    }

    // Dipakai di surat master
    private function hapus_syarat($id = 0)
    {
        // Hapus semua syarat surat berdasarkan surat_format_id
        $this->db
            ->where('surat_format_id', $id)
            ->delete('syarat_surat');
    }
}
