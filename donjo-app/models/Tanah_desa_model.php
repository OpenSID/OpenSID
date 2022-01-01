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

class Tanah_desa_model extends CI_Model
{
    public const ORDER_ABLE = [
        2 => 'nama_pemilik_asal',
    ];

    protected $table = 'tanah_desa';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_data(string $search = '')
    {
        $builder = $this->db
            ->select('td.id,
					td.nama_pemilik_asal,
					p.nama,
					td.luas,
					td.mutasi,
					td.keterangan')
            ->from("{$this->table} td")
            ->join('tweb_penduduk p', 'td.id_penduduk = p.id', 'left')
            ->where('td.visible', 1);

        if (empty($search)) {
            $search = $builder;
        } else {
            $search = $builder
                ->group_start()
                ->like('td.nama_pemilik_asal', $search)
                ->or_like('p.nama', $search)
                ->group_end();
        }

        return $search;
    }

    public function view_tanah_desa_by_id($id)
    {
        $this->db
            ->select('td.*, p.nama, p.nik as nik_penduduk')
            ->from("{$this->table} td")
            ->join('tweb_penduduk p', 'td.id_penduduk = p.id', 'left')
            ->where('td.id', $id);

        return $this->db
            ->get()
            ->row();
    }

    public function add_tanah_desa()
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
            'id_penduduk'          => $data['id_penduduk'],
            'nik'                  => $data['nik'],
            'jenis_pemilik'        => $data['jenis_pemilik'],
            'nama_pemilik_asal'    => $data['nama_pemilik_asal'],
            'luas'                 => $data['luas'],
            'hak_milik'            => $data['hak_milik'],
            'hak_guna_bangunan'    => $data['hak_guna_bangunan'],
            'hak_pakai'            => $data['hak_pakai'],
            'hak_guna_usaha'       => $data['hak_guna_usaha'],
            'hak_pengelolaan'      => $data['hak_pengelolaan'],
            'hak_milik_adat'       => $data['hak_milik_adat'],
            'hak_verponding'       => $data['hak_verponding'],
            'tanah_negara'         => $data['tanah_negara'],
            'perumahan'            => $data['perumahan'],
            'perdagangan_jasa'     => $data['perdagangan_jasa'],
            'perkantoran'          => $data['perkantoran'],
            'industri'             => $data['industri'],
            'fasilitas_umum'       => $data['fasilitas_umum'],
            'sawah'                => $data['sawah'],
            'tegalan'              => $data['tegalan'],
            'perkebunan'           => $data['perkebunan'],
            'peternakan_perikanan' => $data['peternakan_perikanan'],
            'hutan_belukar'        => $data['hutan_belukar'],
            'hutan_lebat_lindung'  => $data['hutan_lebat_lindung'],
            'tanah_kosong'         => $data['tanah_kosong'],
            'lain'                 => $data['lain'],
            'mutasi'               => $data['mutasi'],
            'keterangan'           => $data['keterangan'],
            'created_by'           => $this->session->user,
            'updated_by'           => $this->session->user,
            'visible'              => $data['visible'],
        ];

        $hasil = $this->db->insert($this->table, $result);
        status_sukses($hasil);
    }

    public function delete_tanah_desa($id)
    {
        $hasil = $this->db->update($this->table, ['visible' => 0], ['id' => $id]);
        status_sukses($hasil);
    }

    public function update_tanah_desa()
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
            'id_penduduk'          => $data['id_penduduk'],
            'nik'                  => $data['nik'],
            'jenis_pemilik'        => $data['jenis_pemilik'],
            'nama_pemilik_asal'    => $data['nama_pemilik_asal'],
            'luas'                 => $data['luas'],
            'hak_milik'            => $data['hak_milik'],
            'hak_guna_bangunan'    => $data['hak_guna_bangunan'],
            'hak_pakai'            => $data['hak_pakai'],
            'hak_guna_usaha'       => $data['hak_guna_usaha'],
            'hak_pengelolaan'      => $data['hak_pengelolaan'],
            'hak_milik_adat'       => $data['hak_milik_adat'],
            'hak_verponding'       => $data['hak_verponding'],
            'tanah_negara'         => $data['tanah_negara'],
            'perumahan'            => $data['perumahan'],
            'perdagangan_jasa'     => $data['perdagangan_jasa'],
            'perkantoran'          => $data['perkantoran'],
            'industri'             => $data['industri'],
            'fasilitas_umum'       => $data['fasilitas_umum'],
            'sawah'                => $data['sawah'],
            'tegalan'              => $data['tegalan'],
            'perkebunan'           => $data['perkebunan'],
            'peternakan_perikanan' => $data['peternakan_perikanan'],
            'hutan_belukar'        => $data['hutan_belukar'],
            'hutan_lebat_lindung'  => $data['hutan_lebat_lindung'],
            'tanah_kosong'         => $data['tanah_kosong'],
            'lain'                 => $data['lain'],
            'mutasi'               => $data['mutasi'],
            'keterangan'           => $data['keterangan'],
            'updated_at'           => date('Y-m-d H:i:s'),
            'updated_by'           => $this->session->user,
            'visible'              => $data['visible'],
        ];

        $id = $data['id'];

        $hasil = $this->db->update($this->table, $result, ['id' => $id]);
        status_sukses($hasil);
    }

    private function periksa_nik(&$valid, $data, $id)
    {
        if (empty($data['penduduk']) && ! isset($data['nik'])) {
            $valid[] = 'NIK Kosong';

            return;
        }

        if ($error_nik = $this->nik_error($data['nik'], 'NIK')) {
            $valid[] = $error_nik;

            return;
        }

        // NIK 0 (yaitu NIK tidak diketahui) boleh duplikat
        if ($data['nik'] == 0) {
            return;
        }

        // add
        if ($id == 0) {
            if ($this->nik_warga_luar_checking($data['nik']) || $this->nik_warga_luar_join_checking($data['nik'])) {
                $valid[] = "NIK {$data['nik']} sudah digunakan";
            }

            return;
        }

        // update
        $nik_old_check = $this->nik_warga_luar_old_checking($data['nik'], $id);
        if (! $nik_old_check) {
            if ($this->nik_warga_luar_checking($data['nik']) || $this->nik_warga_luar_join_checking($data['nik'])) {
                $valid[] = "NIK {$data['nik']} sudah digunakan";
            }
        }
    }

    private function validasi_data(&$data, $id = 0)
    {
        $valid = [];

        if (preg_match("/[^a-zA-Z '\\.,\\-]/", $data['pemilik_asal'])) {
            $valid[] = 'Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
        }

        $this->periksa_nik($valid, $data, $id);

        //  steril data
        $data['id_penduduk']          = empty($data['penduduk']) ? 0 : $data['penduduk'];
        $data['nik']                  = empty(bilangan($data['nik'])) ? 0 : bilangan($data['nik']);
        $data['jenis_pemilik']        = bilangan($data['jenis_pemilik']);
        $data['nama_pemilik_asal']    = nama(strtoupper($data['pemilik_asal']));
        $data['luas']                 = bilangan($data['luas']);
        $data['hak_milik']            = bilangan($data['hak_milik']);
        $data['hak_guna_bangunan']    = bilangan($data['hak_guna_bangunan']);
        $data['hak_pakai']            = bilangan($data['hak_pakai']);
        $data['hak_guna_usaha']       = bilangan($data['hak_guna_usaha']);
        $data['hak_pengelolaan']      = bilangan($data['hak_pengelolaan']);
        $data['hak_milik_adat']       = bilangan($data['hak_milik_adat']);
        $data['hak_verponding']       = bilangan($data['hak_verponding']);
        $data['tanah_negara']         = bilangan($data['tanah_negara']);
        $data['perumahan']            = bilangan($data['perumahan']);
        $data['perdagangan_jasa']     = bilangan($data['perdagangan_jasa']);
        $data['perkantoran']          = bilangan($data['perkantoran']);
        $data['industri']             = bilangan($data['industri']);
        $data['fasilitas_umum']       = bilangan($data['fasilitas_umum']);
        $data['sawah']                = bilangan($data['sawah']);
        $data['tegalan']              = bilangan($data['tegalan']);
        $data['perkebunan']           = bilangan($data['perkebunan']);
        $data['peternakan_perikanan'] = bilangan($data['peternakan_perikanan']);
        $data['hutan_belukar']        = bilangan($data['hutan_belukar']);
        $data['hutan_lebat_lindung']  = bilangan($data['hutan_lebat_lindung']);
        $data['tanah_kosong']         = bilangan($data['tanah_kosong']);
        $data['lain']                 = bilangan($data['lain_lain']);
        $data['mutasi']               = strip_tags($data['mutasi']);
        $data['keterangan']           = strip_tags($data['keterangan']);
        $data['visible']              = 1;

        if (! empty($valid)) {
            $this->session->validation_error = true;
        }

        return $valid;
    }

    private function nik_warga_luar_old_checking($nik, $id)
    {
        $this->db
            ->select('td.nik')
            ->from("{$this->table} td")
            ->where((['td.visible' => 1, 'td.id' => $id]))
            ->limit(1);
        $data = $this->db
            ->get()
            ->row();

        return $nik == $data->nik;
    }

    private function nik_warga_luar_checking($nik)
    {
        $this->db
            ->select('td.nik')
            ->from("{$this->table} td")
            ->where((['td.visible' => 1, 'td.nik' => $nik]))
            ->limit(1);

        return $this->db
            ->get()
            ->row();
    }

    public function nik_warga_luar_join_checking($nik)
    {
        $this->db
            ->select('p.nik')
            ->from("{$this->table} td")
            ->join('tweb_penduduk p', 'td.id_penduduk = p.id')
            ->where((['td.visible' => 1, 'p.nik' => $nik]));

        return $this->db
            ->get()
            ->result_array();
    }

    private function nik_error($nilai, $judul)
    {
        if (empty($nilai)) {
            return false;
        }
        if (! ctype_digit($nilai)) {
            return $judul . ' hanya berisi angka';
        }
        if (strlen($nilai) != 16 && $nilai != '0') {
            return $judul . ' panjangnya harus 16 atau bernilai 0';
        }

        return false;
    }

    public function cetak_tanah_desa()
    {
        $this->db
            ->select('td.*, p.nama')
            ->from("{$this->table} td")
            ->join('tweb_penduduk p', 'td.id_penduduk = p.id', 'left')
            ->where('td.visible', 1);

        return $this->db
            ->get()
            ->result_array();
    }

    public function list_penduduk()
    {
        return $this->db
            ->select('p.id, p.nama, p.nik')
            ->from('tweb_penduduk p')
            ->order_by('p.nama', 'ASC')
            ->get()
            ->result_array();
    }
}
