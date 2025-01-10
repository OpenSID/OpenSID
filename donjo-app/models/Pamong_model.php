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

use App\Enums\StatusEnum;
use App\Models\Pamong;
use Carbon\Carbon;
use Modules\Kehadiran\Models\Kehadiran;

defined('BASEPATH') || exit('No direct script access allowed');

class Pamong_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . '/models/Urut_model.php';
        $this->load->model(['referensi_model']);
    }

    public function list_data($offset = 0, $limit = 500)
    {
        $this->db->select(
            'u.*, rj.nama AS jabatan, rj.id AS ref_jabatan_id, p.nama, p.nik, p.tag_id_card, p.tempatlahir, p.tanggallahir,
            (case when p.sex is not null then p.sex else u.pamong_sex end) as id_sex,
            (case when p.foto is not null then p.foto else u.foto end) as foto,
            (case when p.nama is not null then p.nama else u.pamong_nama end) as nama,
            x.nama AS sex, b.nama AS pendidikan_kk, g.nama AS agama, x2.nama AS pamong_sex, b2.nama AS pamong_pendidikan, g2.nama AS pamong_agama,
            !EXISTS (SELECT s.id_pamong FROM log_surat as s where s.id_pamong = u.pamong_id  ) as deletable'
        );

        $this->list_data_sql();

        $kades  = kades()->id ?: 0;
        $sekdes = sekdes()->id ?: 0;

        $this->db
            ->order_by(sprintf('
                case
                    when u.jabatan_id=%s then 1
                    when u.jabatan_id=%s then 2
                    else 3
                end
            ', $kades, $sekdes), '', false)
            ->order_by('u.urut')
            ->limit($limit, $offset);

        $data = $this->db->get()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            if (empty($data[$i]['id_pend'])) {
                // Dari luar desa
                $data[$i]['nik']           = $data[$i]['pamong_nik'];
                $data[$i]['tag_id_card']   = $data[$i]['pamong_tag_id_card'];
                $data[$i]['tempatlahir']   = empty($data[$i]['pamong_tempatlahir']) ? '-' : $data[$i]['pamong_tempatlahir'];
                $data[$i]['tanggallahir']  = $data[$i]['pamong_tanggallahir'];
                $data[$i]['sex']           = $data[$i]['pamong_sex'];
                $data[$i]['pendidikan_kk'] = $data[$i]['pamong_pendidikan'];
                $data[$i]['agama']         = $data[$i]['pamong_agama'];
                if (empty($data[$i]['pamong_nosk'])) {
                    $data[$i]['pamong_nosk'] = '-';
                }
                if (empty($data[$i]['pamong_nohenti'])) {
                    $data[$i]['pamong_nohenti'] = '-';
                }
            } elseif (empty($data[$i]['tempatlahir'])) {
                $data[$i]['tempatlahir'] = '-';
            }

            $data[$i]['nama'] = gelar($data[$i]['gelar_depan'], $data[$i]['nama'], $data[$i]['gelar_belakang']);
            $data[$i]['no']   = $j + 1;
            $j++;
        }

        return $data;
    }

    private function list_data_sql(): void
    {
        $this->config_id('u')
            ->from('tweb_desa_pamong u')
            ->join('tweb_penduduk p', 'u.id_pend = p.id', 'LEFT')
            ->join('tweb_penduduk_pendidikan_kk b', 'p.pendidikan_kk_id = b.id', 'LEFT')
            ->join('tweb_penduduk_sex x', 'p.sex = x.id', 'LEFT')
            ->join('tweb_penduduk_agama g', 'p.agama_id = g.id', 'LEFT')
            ->join('tweb_penduduk_pendidikan_kk b2', 'u.pamong_pendidikan = b2.id', 'LEFT')
            ->join('tweb_penduduk_sex x2', 'u.pamong_sex = x2.id', 'LEFT')
            ->join('tweb_penduduk_agama g2', 'u.pamong_agama = g2.id', 'LEFT')
            ->join('ref_jabatan rj', 'rj.id = u.jabatan_id', 'left');
        $this->search_sql();
        $this->filter_sql();
    }

    private function search_sql(): void
    {
        if ($this->session->has_userdata('cari')) {
            $cari = $this->session->cari;
            $this->db
                ->group_start()
                ->like('p.nama', $cari)
                ->or_like('u.pamong_nama', $cari)
                ->or_like('u.pamong_niap', $cari)
                ->or_like('u.pamong_nip', $cari)
                ->or_like('u.pamong_nik', $cari)
                ->or_like('p.nik', $cari)
                ->group_end();
        }
    }

    private function filter_sql(): void
    {
        if ($this->session->has_userdata('status')) {
            $this->db->where('u.pamong_status', $this->session->status);
        }
    }

    public function get_data($id = 0)
    {
        $pejabat = setting('sebutan_pj_kepala_desa');

        $data = $this->config_id('u')
            ->select('u.*, rj.id AS ref_jabatan_id,
				(case when p.nama is not null then p.nama else u.pamong_nama end) as nama,
				(case when p.foto is not null then p.foto else u.foto end) as foto,
				(case when p.sex is not null then p.sex else u.pamong_sex end) as id_sex,
                (case when u.status_pejabat = 1 THEN CONCAT("' . $pejabat . ' ", rj.nama) else rj.nama end) as pamong_jabatan,
                (case when u.status_pejabat = 1 THEN CONCAT("' . $pejabat . ' ", rj.nama) else rj.nama end) as jabatan')
            ->from('tweb_desa_pamong u')
            ->join('tweb_penduduk p', 'u.id_pend = p.id', 'left')
            ->join('ref_jabatan rj', 'rj.id = u.jabatan_id', 'left')
            ->where('pamong_id', $id)
            ->get()
            ->row_array();

        if ($data) {
            $data['pamong_niap_nip'] = (! empty($data['pamong_nip']) && $data['pamong_nip'] != '-') ? $data['pamong_nip'] : $data['pamong_niap'];
            if (! empty($data['pamong_nip']) && $data['pamong_nip'] != '-') {
                $data['sebutan_pamong_niap_nip'] = 'NIP: ';
            } elseif (! empty($data['pamong_niap']) && $data['pamong_niap'] != '-') {
                $data['sebutan_pamong_niap_nip'] = setting('sebutan_nip_desa') . ': ';
            } else {
                $data['sebutan_pamong_niap_nip'] = '';
            }

            $data['nama'] = gelar($data['gelar_depan'], $data['nama'], $data['gelar_belakang']);
        }

        return $data;
    }

    protected function foto($post)
    {
        if ($post['id_pend']) {
            // Penduduk Dalam Desa
            $id    = $post['id_pend'];
            $field = 'id';
            $tabel = 'tweb_penduduk';
            $foto  = time() . '-' . $id . '-' . random_int(10000, 999999);
        } else {
            // Penduduk Luar Desa
            $id    = $post['id'];
            $field = 'pamong_id';
            $tabel = 'tweb_desa_pamong';
            $foto  = 'pamong_' . time() . '-' . $id . '-' . random_int(10000, 999999);
        }
        $dimensi = $post['lebar'] . 'x' . $post['tinggi'];
        if ($foto = upload_foto_penduduk($foto, $dimensi)) {
            $this->config_id()->where($field, $id)->update($tabel, ['foto' => $foto]);
        }
    }

    // Ambil data untuk widget aparatur desa
    public function list_aparatur_desa()
    {
        $data_query = Pamong::aktif()->urut()->get()->toArray();

        $result = collect($data_query)->map(static function (array $item): array {
            $kehadiran = Kehadiran::where('pamong_id', $item['pamong_id'])
                ->where('tanggal', Carbon::now()->format('Y-m-d'))
                ->orderBy('id', 'DESC')->first();

            $nama = $item['pamong_nama'];
            $sex  = $item['id_pend'] ? $item['penduduk']['sex'] : $item['pamong_sex'];

            return [
                'pamong_id'        => $item['pamong_id'],
                'jabatan'          => $item['status_pejabat'] == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') . ' ' . $item['jabatan']['nama'] : $item['jabatan']['nama'],
                'pamong_niap'      => $item['pamong_niap'],
                'gelar_depan'      => $item['gelar_depan'],
                'gelar_belakang'   => $item['gelar_belakang'],
                'kehadiran'        => $item['kehadiran'],
                'media_sosial'     => json_encode($item['media_sosial']),
                'foto'             => AmbilFoto($item['foto_staff'], '', ($item['pamong_sex'] ?? $item['penduduk->sex'])),
                'id_sex'           => $sex,
                'nama'             => $nama,
                'status_kehadiran' => $kehadiran ? $kehadiran->status_kehadiran : null,
                'tanggal'          => $kehadiran ? $kehadiran->tanggal : null,
            ];
        })->toArray();

        return ['daftar_perangkat' => $result];
    }

    public function list_bagan()
    {
        // atasan => bawahan. Contoh:
        // data['struktur'] = [
        //  ['14' => '20'],
        //  ['14' => '26'],
        //  ['20' => '24']
        // ;
        $atasan = $this->config_id()
            ->select('atasan, pamong_id')
            ->where('atasan IS NOT NULL')
            ->where('pamong_status', 1)
            ->get('tweb_desa_pamong')
            ->result_array();
        $data['struktur'] = [];

        foreach ($atasan as $pamong) {
            $data['struktur'][] = [$pamong['atasan'] => $pamong['pamong_id']];
        }

        $data_query = $this->config_id('p')
            ->select('p.pamong_id, rj.nama AS jabatan, p.gelar_depan, p.gelar_belakang, p.bagan_tingkat, p.bagan_offset, p.bagan_layout, p.bagan_warna')
            ->select('(CASE WHEN id_pend IS NOT NULL THEN ph.foto ELSE p.foto END) as foto')
            ->select('(CASE WHEN id_pend IS NOT NULL THEN ph.nama ELSE p.pamong_nama END) as nama')
            ->select('(CASE WHEN id_pend IS NOT NULL THEN ph.sex ELSE p.pamong_sex END) as jenis_kelamin')
            ->from('tweb_desa_pamong p')
            ->join('penduduk_hidup ph', 'ph.id = p.id_pend', 'left')
            ->join('ref_jabatan rj', 'rj.id = p.jabatan_id', 'left')
            ->where('pamong_status', 1)
            ->get()
            ->result_array();

        $data['nodes'] = collect($data_query)->map(static function (array $item): array {
            $item['nama'] = gelar($item['gelar_depan'], $item['nama'], $item['gelar_belakang']);

            return $item;
        })
            ->toArray();

        return $data;
    }
}
