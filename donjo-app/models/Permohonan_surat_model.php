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

use App\Models\Dokumen;
use App\Models\LogSurat;
use App\Models\PermohonanSurat;
use App\Models\SyaratSurat;

class Permohonan_surat_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['referensi_model', 'anjungan_model']);
    }

    public function insert($data)
    {
        $data['config_id'] = identitas('id');

        return $this->db
            ->insert('permohonan_surat', array_merge(
                ['no_antrian' => $this->generate_no_antrian()],
                $data
            ));
    }

    public function delete($id_permohonan): void
    {
        $outp = $this->config_id()->where('id', $id_permohonan)
            ->delete('permohonan_surat');
        if (! $outp) {
            $this->session->set_userdata('success', -1);
        }
    }

    public function update($id_permohonan, $data)
    {
        return $this->config_id()
            ->where('id', $id_permohonan)
            ->update('permohonan_surat', $data);
    }

    public function autocomplete()
    {
        $data = $this->config_id('n')->select('n.nik')
            ->from('permohonan_surat u')
            ->join('tweb_penduduk n', 'u.id_pemohon = n.id', 'left')
            ->get()->result_array();

        $outp = '';

        foreach ($data as $baris) {
            $outp .= ",'" . $baris['nik'] . "'";
        }
        $outp = substr($outp, 1);

        return '[' . $outp . ']';
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('n.nik', $cari)
                ->or_like('n.nama', $cari)
                ->or_like('u.no_antrian', str_replace('-', '', $cari))
                ->group_end();
        }
    }

    private function filter_sql(): void
    {
        $filter = $this->session->filter;
        if ($filter != '') {
            $this->db->where('u.status', $filter);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->db->select('COUNT(u.id) as jml');
        $this->list_data_sql();
        $jml_data = $this->db->get()->row()->jml;

        $this->load->library('paging');
        $cfg['page']      = $p;
        $cfg['per_page']  = $this->session->per_page;
        $cfg['num_links'] = 10;
        $cfg['num_rows']  = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql(): void
    {
        $this->config_id('u')->from('permohonan_surat u')
            ->join('tweb_penduduk n', 'u.id_pemohon = n.id', 'left')
            ->join('tweb_surat_format s', 'u.id_surat = s.id', 'left');

        $this->search_sql();
        $this->filter_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->db->order_by('u.status');

        //Ordering SQL
        switch ($o) {
            case 1: $this->db->order_by('u.created_at', 'asc');
                break;

            case 2: $this->db->order_by('u.created_at', 'desc');
                break;

            default: $this->db->order_by('(u.status = 0), ISNULL(u.no_antrian)');
        }

        //Main Query
        $this->list_data_sql();
        $data = $this->db->select([
            'u.*',
            'u.status as status_id',
            'n.nama AS nama',
            'n.nik AS nik',
            's.nama as jenis_surat',
        ])
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']     = $j + 1;
            $data[$i]['status'] = $this->referensi_model->list_ref_flip(STATUS_PERMOHONAN)[$data[$i]['status']];
            $j++;
        }

        return $data;
    }

    public function list_permohonan_perorangan($id_pemohon, $kat = null)
    {
        if ($kat == 1) {
            $this->db->where_not_in('u.status', [PermohonanSurat::SUDAH_DIAMBIL]);
        }

        $data = $this->config_id('u')
            ->select('u.*, u.status as status_id, n.nama AS nama, n.nik AS nik, s.nama as jenis_surat')
            ->where('id_pemohon', $id_pemohon)
            ->from('permohonan_surat u')
            ->join('tweb_penduduk n', 'u.id_pemohon = n.id', 'left')
            ->join('tweb_surat_format s', 'u.id_surat = s.id', 'left')
            ->order_by('u.status')
            ->order_by('updated_at', 'DESC')
            ->get()
            ->result_array();

        return collect($data)->map(static function ($item, $key): array {
            $item['no']     = $key + 1;
            $item['nomor']  = json_decode($item->isian_form, true)['nomor'];
            $item['status'] = PermohonanSurat::STATUS_PERMOHONAN[$item->status];
            $logSurat       = LogSurat::where('id_format_surat', $item->id_surat)->where('no_surat', $item->nomor)->first();
            $item['id_log'] = $logSurat ? $logSurat->id : null;
            $item['tte']    = $logSurat ? $logSurat->tte : null;

            return $item;
        })
            ->toArray();
    }

    public function get_permohonan($where = [])
    {
        return $this->config_id()
            ->get_where('permohonan_surat', $where)
            ->row_array();
    }

    public function list_data_status($id)
    {
        return $this->config_id()
            ->select('id, status')
            ->from('permohonan_surat')
            ->where('id', $id)
            ->get()
            ->row_array();
    }

    public function proses($id, $status, $id_pemohon = ''): void
    {
        if ($status == PermohonanSurat::BELUM_LENGKAP) {
            // Belum Lengkap
            $this->db->where('status', PermohonanSurat::SEDANG_DIPERIKSA);
        } elseif ($status == PermohonanSurat::DIBATALKAN) {
            // Batalkan hanya jika status = 0 (belum lengkap) atau 1 (sedang diproses)
            $this->db->where_in('status', [PermohonanSurat::BELUM_LENGKAP, PermohonanSurat::SEDANG_DIPERIKSA]);

            if ($id_pemohon) {
                $this->db->where('id_pemohon', $id_pemohon);
            }
        } else {
            // Lainnya
            $this->db->where('status', ($status - 1));
        }

        $outp = $this->config_id()
            ->where('id', $id)
            ->update('permohonan_surat', ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);

        status_sukses($outp);
    }

    public function ambil_isi_form($isian_form)
    {
        $isian_form = json_decode($isian_form, true);
        $hapus      = ['url_surat', 'url_remote', 'nik', 'id_surat', 'nomor', 'pilih_atas_nama', 'pamong', 'pamong_nip', 'jabatan', 'pamong_id'];

        foreach ($hapus as $kolom) {
            unset($isian_form[$kolom]);
        }

        return $isian_form;
    }

    public function get_syarat_permohonan($id)
    {
        $permohonan = PermohonanSurat::select(['syarat'])->findOrFail($id);

        return collect($permohonan->syarat)->map(static function ($item, $key): array {
            $syaratSurat        = SyaratSurat::select(['ref_syarat_nama'])->find($key);
            $dokumenKelengkapan = Dokumen::select(['nama'])->find($item);

            return [
                'ref_syarat_id'   => $key,
                'ref_syarat_nama' => $syaratSurat->ref_syarat_nama,
                'dok_id'          => $item,
                'dok_nama'        => ($item == '-1') ? 'Bawa bukti fisik ke Kantor Desa' : $dokumenKelengkapan->nama,
            ];
        })->values();
    }

    protected function generate_no_antrian()
    {
        if (null === $this->anjungan_model->cek_anjungan()) {
            return;
        }

        $nomor_terakhir = $this->config_id()
            ->select_max('no_antrian')
            ->from('permohonan_surat')
            ->where('CAST(created_at AS DATE) >= CURDATE()')
            ->get()
            ->row()
            ->no_antrian;

        return null === $nomor_terakhir
            ? date('dmy') . '001'
            : $nomor_terakhir + 1;
    }
}
