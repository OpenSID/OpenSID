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

use App\Models\KeuanganManualRinci;
use App\Models\KeuanganManualTemplate;

defined('BASEPATH') || exit('No direct script access allowed');

class Keuangan_manual_model extends MY_Model
{
    private string $tabel = 'keuangan_manual_rinci';

    public function list_tahun_anggaran_manual()
    {
        $data = $this->config_id()
            ->select('Tahun')
            ->order_by('Tahun DESC')
            ->group_by('Tahun')
            ->get($this->tabel)
            ->result_array();

        return array_column($data, 'Tahun');
    }

    public function list_apbdes($tahun = null)
    {
        if (! empty($tahun)) {
            $this->db->where('Tahun', $tahun);
        }

        return $this->config_id()->get($this->tabel)->result();
    }

    public function list_data_keuangan()
    {
        $filter = [
            'Kd_Akun' => $this->session->set_jenis ?? '4.PENDAPATAN',
            'Tahun'   => $this->session->set_tahun ?? null,
        ];

        return $this->config_id()->where($filter)->get($this->tabel)->result();
    }

    public function simpan_anggaran($data = [])
    {
        $data['config_id'] = $this->config_id;
        $output            = $this->db->insert($this->tabel, $data);

        status_sukses($output);

        return $output;
    }

    public function get_anggaran($id)
    {
        $hsl = $this->config_id()
            ->where('id', $id)
            ->get($this->tabel);

        if ($hsl->num_rows() > 0) {
            foreach ($hsl->result() as $data) {
                $hasil = [
                    'id'              => $data->id,
                    'Tahun'           => $data->Tahun,
                    'Kd_Akun'         => $data->Kd_Akun,
                    'Kd_Keg'          => $data->Kd_Keg,
                    'Kd_Rincian'      => $data->Kd_Rincian,
                    'Nilai_Anggaran'  => $data->Nilai_Anggaran,
                    'Nilai_Realisasi' => $data->Nilai_Realisasi,
                ];
            }
        }

        return $hasil;
    }

    public function update_anggaran($id = null, $data = [])
    {
        $output = $this->config_id()
            ->where('id', $id)
            ->update($this->tabel, $data);

        status_sukses($output);

        return $output;
    }

    public function list_rek_pendapatan()
    {
        return $this->db
            ->select('*')
            ->where("Jenis LIKE '4.%'")
            ->order_by('Jenis', 'asc')
            ->get('keuangan_manual_ref_rek3')
            ->result_array();
    }

    public function list_rek_belanja()
    {
        return $this->db
            ->order_by('Kd_Bid', 'asc')
            ->get('keuangan_manual_ref_bidang')
            ->result_array();
    }

    public function list_rek_biaya()
    {
        return $this->db
            ->where("Jenis LIKE '6.%'")
            ->order_by('Jenis', 'asc')
            ->get('keuangan_manual_ref_rek3')
            ->result_array();
    }

    public function list_akun()
    {
        return $this->db
            ->where("Akun NOT LIKE '1.%'")
            ->where("Akun NOT LIKE '2.%'")
            ->where("Akun NOT LIKE '3.%'")
            ->where("Akun NOT LIKE '7.%'")
            ->order_by('Akun', 'asc')
            ->get('keuangan_manual_ref_rek1')
            ->result_array();
    }

    public function delete_input($id = ''): void
    {
        $this->config_id()
            ->where('id', $id)
            ->delete($this->tabel);

        if ($this->db->affected_rows() > 0) {
            session_success();
        } else {
            session_error('Gagal menghapus data');
        }
    }

    public function delete_all(): void
    {
        $id_cb = $this->input->post('id_cb');
        // Cek apakah ada data yang dicentang atau dipilih
        if (null !== $id_cb) {
            foreach ($id_cb as $id) {
                $this->delete_input($id);
            }
        } else {
            session_error('Tidak ada data yang dipilih');
        }
    }

    public function get_anggaran_tpl()
    {
        $hsl = $this->db->get('keuangan_manual_rinci_tpl');

        if ($hsl->num_rows() > 0) {
            foreach ($hsl->result() as $data) {
                $hasil = [
                    'id'              => $data->id,
                    'Tahun'           => $data->Tahun,
                    'Kd_Akun'         => $data->Kd_Akun,
                    'Kd_Keg'          => $data->Kd_Keg,
                    'Kd_Rincian'      => $data->Kd_Rincian,
                    'Nilai_Anggaran'  => $data->Nilai_Anggaran,
                    'Nilai_Realisasi' => $data->Nilai_Realisasi,
                ];
            }
        }

        return $hasil;
    }

    public function salin_anggaran_tpl($thn_apbdes)
    {
        $config_id = $this->config_id;

        if (KeuanganManualRinci::where('tahun', $thn_apbdes)->exists()) {
            return false;
        }

        $result_set = KeuanganManualTemplate::get(['Kd_Akun', 'Kd_Keg', 'Kd_Rincian', 'Nilai_Anggaran', 'Nilai_Realisasi'])
            ->map(static function ($item) use ($config_id, $thn_apbdes) {
                $item->config_id = $config_id;
                $item->Tahun     = $thn_apbdes;

                return $item;
            })->toArray();

        if (KeuanganManualRinci::insert($result_set)) {
            session_success();
        } else {
            session_error('Gagal salin data');
        }

        return $result_set;
    }
}
