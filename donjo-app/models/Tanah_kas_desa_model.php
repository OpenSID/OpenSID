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

class Tanah_kas_desa_model extends CI_Model
{
    public const ORDER_ABLE = [
        2 => 'nama_pemilik_asal',
        6 => 'tanggal_perolehan',
    ];

    protected $table = 'tanah_kas_desa';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_data(string $search = '')
    {
        $builder = $this->db
            ->select('tkd.id,
					atk.nama,
					tkd.letter_c,
					p.kode,
					tkd.lokasi,
					tkd.luas,
					tkd.tanggal_perolehan,
					tkd.mutasi,
					tkd.keterangan')
            ->from("{$this->table} tkd")
            ->join('ref_asal_tanah_kas atk', 'tkd.nama_pemilik_asal = atk.id')
            ->join('ref_persil_kelas p', 'tkd.kelas = p.id')
            ->where('tkd.visible', 1);

        if (empty($search)) {
            $search = $builder;
        } else {
            $search = $builder
                ->group_start()
                ->like('tkd.nama_pemilik_asal', $search)
                ->or_like('tkd.letter_c', $search)
                ->group_end();
        }

        return $search;
    }

    public function view_tanah_kas_desa_by_id($id)
    {
        $this->db
            ->select('*')
            ->from("{$this->table} tkd")
            ->where('tkd.id', $id);

        return $this->db
            ->get()
            ->row();
    }

    public function add_tanah_kas_desa()
    {
        unset($this->session->validation_error, $this->session->success);

        $this->session->error_msg = '';

        $data           = $this->input->post();
        $error_validasi = $this->validasi_data($data);

        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $this->session->error_msg .= ': ' . $error . '\n';
            }
            $this->session->post    = $this->input->post();
            $this->session->success = -1;

            return;
        }

        $result = [
            'nama_pemilik_asal'    => $data['nama_pemilik_asal'],
            'letter_c'             => $data['letter_c'],
            'kelas'                => $data['kelas'],
            'luas'                 => $data['luas'],
            'asli_milik_desa'      => $data['asli_milik_desa'],
            'pemerintah'           => $data['pemerintah'],
            'provinsi'             => $data['provinsi'],
            'kabupaten_kota'       => $data['kabupaten_kota'],
            'lain_lain'            => $data['lain_lain'],
            'sawah'                => $data['sawah'],
            'tegal'                => $data['tegal'],
            'kebun'                => $data['kebun'],
            'tambak_kolam'         => $data['tambak_kolam'],
            'tanah_kering_darat'   => $data['tanah_kering_darat'],
            'ada_patok'            => $data['ada_patok'],
            'tidak_ada_patok'      => $data['tidak_ada_patok'],
            'ada_papan_nama'       => $data['ada_papan_nama'],
            'tidak_ada_papan_nama' => $data['tidak_ada_papan_nama'],
            'tanggal_perolehan'    => $data['tanggal_perolehan'],
            'lokasi'               => $data['lokasi'],
            'peruntukan'           => $data['peruntukan'],
            'mutasi'               => $data['mutasi'],
            'keterangan'           => $data['keterangan'],
            'created_by'           => $this->session->user,
            'updated_by'           => $this->session->user,
            'visible'              => $data['visible'],
        ];

        $hasil = $this->db->insert($this->table, $result);
        status_sukses($hasil);
    }

    public function delete_tanah_kas_desa($id)
    {
        $hasil = $this->db->update($this->table, ['visible' => 0], ['id' => $id]);
        status_sukses($hasil);
    }

    public function update_tanah_kas_desa()
    {
        unset($this->session->validation_error, $this->session->success);

        $this->session->error_msg = '';

        $data           = $this->input->post();
        $error_validasi = $this->validasi_data($data, $data['id']);

        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $this->session->error_msg .= ': ' . $error . '\n';
            }
            $this->session->post    = $this->input->post();
            $this->session->success = -1;

            return;
        }

        $result = [
            'nama_pemilik_asal'    => $data['nama_pemilik_asal'],
            'letter_c'             => $data['letter_c'],
            'kelas'                => $data['kelas'],
            'luas'                 => $data['luas'],
            'asli_milik_desa'      => $data['asli_milik_desa'],
            'pemerintah'           => $data['pemerintah'],
            'provinsi'             => $data['provinsi'],
            'kabupaten_kota'       => $data['kabupaten_kota'],
            'lain_lain'            => $data['lain_lain'],
            'sawah'                => $data['sawah'],
            'tegal'                => $data['tegal'],
            'kebun'                => $data['kebun'],
            'tambak_kolam'         => $data['tambak_kolam'],
            'tanah_kering_darat'   => $data['tanah_kering_darat'],
            'ada_patok'            => $data['ada_patok'],
            'tidak_ada_patok'      => $data['tidak_ada_patok'],
            'ada_papan_nama'       => $data['ada_papan_nama'],
            'tidak_ada_papan_nama' => $data['tidak_ada_papan_nama'],
            'tanggal_perolehan'    => $data['tanggal_perolehan'],
            'lokasi'               => $data['lokasi'],
            'peruntukan'           => $data['peruntukan'],
            'mutasi'               => $data['mutasi'],
            'keterangan'           => $data['keterangan'],
            'updated_at'           => date('Y-m-d H:i:s'),
            'updated_by'           => $this->session->user,
            'visible'              => $data['visible'],
        ];

        $id    = $data['id'];
        $hasil = $this->db->update($this->table, $result, ['id' => $id]);
        status_sukses($hasil);
    }

    private function validasi_data(&$data, $id = 0)
    {
        $valid = [];

        // add
        if ($id == 0) {
            $check_letterc_persil = $this->check_letterc_persil($data['letter_c_persil']);
            if (count($check_letterc_persil) > 0) {
                $valid[] = "Letter C / Persil {$data['letter_c_persil']} sudah digunakan";
            }
        } else {
            // update
            $check_old_letterc_persil = $this->check_old_letterc_persil($data['letter_c_persil'], $id);
            if (! $check_old_letterc_persil) {
                $check_letterc_persil = $this->check_letterc_persil($data['letter_c_persil']);
                if (count($check_letterc_persil) > 0) {
                    $valid[] = "Letter C / Persil {$data['letter_c_persil']} sudah digunakan";
                }
            }
        }

        $data['nama_pemilik_asal']    = strip_tags($data['pemilik_asal']);
        $data['letter_c']             = bilangan($data['letter_c_persil']);
        $data['kelas']                = strip_tags($data['kelas']);
        $data['luas']                 = bilangan($data['luas']);
        $data['asli_milik_desa']      = bilangan($data['asli_milik_desa']);
        $data['pemerintah']           = bilangan($data['pemerintah']);
        $data['provinsi']             = bilangan($data['provinsi']);
        $data['kabupaten_kota']       = bilangan($data['kabupaten_kota']);
        $data['lain_lain']            = bilangan($data['lain_lain']);
        $data['sawah']                = bilangan($data['sawah']);
        $data['tegal']                = bilangan($data['tegal']);
        $data['kebun']                = bilangan($data['kebun']);
        $data['tambak_kolam']         = bilangan($data['tambak_kolam']);
        $data['tanah_kering_darat']   = bilangan($data['tanah_kering_darat']);
        $data['ada_patok']            = bilangan($data['ada_patok']);
        $data['tidak_ada_patok']      = bilangan($data['tidak_ada_patok']);
        $data['ada_papan_nama']       = bilangan($data['ada_papan_nama']);
        $data['tidak_ada_papan_nama'] = bilangan($data['tidak_ada_papan_nama']);
        $data['tanggal_perolehan']    = $data['tanggal_perolehan'];
        $data['lokasi']               = strip_tags($data['lokasi']);
        $data['peruntukan']           = strip_tags($data['peruntukan']);
        $data['mutasi']               = strip_tags($data['mutasi']);
        $data['keterangan']           = strip_tags($data['keterangan']);
        $data['created_by']           = $this->session->user;
        $data['updated_by']           = $this->session->user;
        $data['visible']              = 1;

        if (! empty($valid)) {
            $this->session->validation_error = true;
        }

        return $valid;
    }

    private function check_old_letterc_persil($letterC_persil, $id)
    {
        $this->db
            ->select('tkd.letter_c')
            ->from("{$this->table} tkd")
            ->where((['tkd.visible' => 1, 'tkd.id' => $id]))
            ->limit(1);
        $data = $this->db
            ->get()
            ->row();

        return $letterC_persil == $data->letter_c;
    }

    private function check_letterc_persil($letterC_persil)
    {
        $this->db
            ->select('tkd.letter_c')
            ->from("{$this->table} tkd")
            ->where((['tkd.visible' => 1, 'tkd.letter_c' => $letterC_persil]))
            ->limit(1);

        return $this->db
            ->get()
            ->row();
    }

    public function cetak_tanah_kas_desa()
    {
        $this->db
            ->select('tkd.*, atk.nama as asal, p.kode, ptk.nama as peruntukan_tanah')
            ->from("{$this->table} tkd")
            ->join('ref_asal_tanah_kas atk', 'tkd.nama_pemilik_asal = atk.id')
            ->join('ref_persil_kelas p', 'tkd.kelas = p.id')
            ->join('ref_peruntukan_tanah_kas ptk', 'tkd.peruntukan = ptk.id')
            ->where('tkd.visible', 1);

        return $this->db
            ->get()
            ->result_array();
    }

    public function list_letter_c()
    {
        $this->db
            ->select('c.id, c.nomor, c.nama_kepemilikan')
            ->from('cdesa c');

        return $this->db
            ->get()
            ->result_array();
    }

    public function list_asal_tanah_kas()
    {
        $this->db
            ->select('atk.*')
            ->from('ref_asal_tanah_kas atk');

        return $this->db
            ->get()
            ->result_array();
    }

    public function list_peruntukan_tanah_kas()
    {
        $this->db
            ->select('ptk.*')
            ->from('ref_peruntukan_tanah_kas ptk');

        return $this->db
            ->get()
            ->result_array();
    }
}
