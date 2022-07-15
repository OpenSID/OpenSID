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

class Urut_model extends CI_Model
{
    private $tabel;
    private $kolom_id;

    public function __construct($tabel, $kolom_id = 'id')
    {
        parent::__construct();
        $this->tabel    = $tabel;
        $this->kolom_id = $kolom_id;
    }

    /**
     * Cari nomor urut terbesar untuk subset data
     *
     * @param		array		syarat kolom data yang akan diperiksa
     * @param mixed $subset
     *
     * @return int nomor urut maksimum untuk subset
     */
    public function urut_max($subset = ['1' => '1'])
    {
        return $this->db->select_max('urut')
            ->where($subset)
            ->get($this->tabel)
            ->row()->urut;
    }

    private function urut_semua($subset = ['1' => '1'])
    {
        $urut_duplikat = $this->db->select('urut, COUNT(*) c')
            ->where($subset)
            ->group_by('urut')
            ->having('c > 1')
            ->get($this->tabel)->result_array();
        $belum_diurut = $this->db
            ->where($subset)
            ->where('urut IS NULL')
            ->limit(1)
            ->get($this->tabel)->row_array();
        $daftar = [];
        if ($urut_duplikat || $belum_diurut) {
            $daftar = $this->db->select($this->kolom_id)
                ->where($subset)
                ->order_by('urut')
                ->get($this->tabel)->result_array();
        }

        for ($i = 0; $i < count($daftar); $i++) {
            $this->db->where($this->kolom_id, $daftar[$i][$this->kolom_id]);
            $data['urut'] = $i + 1;
            $this->db->update($this->tabel, $data);
        }
    }

    /**
     * @param $id Id data yg akan digeser
     * @param $arah Arah untuk menukar dengan unsur lain: 1) turun, 2) naik
     * @param mixed $subset
     *
     * @return int Nomer urut unsur lain yang ditukar
     */
    public function urut($id, $arah, $subset = ['1' => '1'])
    {
        $this->urut_semua($subset);
        $unsur1 = $this->db->where($this->kolom_id, $id)
            ->get($this->tabel)
            ->row_array();

        $daftar = $this->db->select("{$this->kolom_id}, urut")
            ->where($subset)
            ->order_by('urut')
            ->get($this->tabel)
            ->result_array();

        return $this->urut_daftar($id, $arah, $daftar, $unsur1);
    }

    private function urut_daftar($id, $arah, $daftar, $unsur1)
    {
        for ($i = 0; $i < count($daftar); $i++) {
            if ($daftar[$i][$this->kolom_id] == $id) {
                break;
            }
        }

        if ($arah == 1) {
            if ($i >= count($daftar) - 1) {
                return;
            }
            $unsur2 = $daftar[$i + 1];
        }
        if ($arah == 2) {
            if ($i <= 0) {
                return;
            }
            $unsur2 = $daftar[$i - 1];
        }

        // Tukar urutan
        $this->db->where($this->kolom_id, $unsur2[$this->kolom_id])->
            update($this->tabel, ['urut' => $unsur1['urut']]);
        $this->db->where($this->kolom_id, $unsur1[$this->kolom_id])->
            update($this->tabel, ['urut' => $unsur2['urut']]);

        return (int) $unsur2['urut'];
    }
}
