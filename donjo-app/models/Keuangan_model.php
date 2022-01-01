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

class Keuangan_model extends CI_model
{
    private $zip_file = '';
    private $id_keuangan_master;
    private $tabel_berdesa = [
        'keuangan_ref_bank_desa',
        'keuangan_ta_anggaran',
        'keuangan_ta_anggaran_log',
        'keuangan_ta_anggaran_rinci',
        'keuangan_ta_bidang',
        'keuangan_ta_jurnal_umum',
        'keuangan_ta_jurnal_umum_rinci',
        'keuangan_ta_kegiatan',
        'keuangan_ta_mutasi',
        'keuangan_ta_pajak',
        'keuangan_ta_pajak_rinci',
        'keuangan_ta_pencairan',
        'keuangan_ta_perangkat',
        'keuangan_ta_rab',
        'keuangan_ta_rab_rinci',
        'keuangan_ta_rab_sub',
        'keuangan_ta_rpjm_bidang',
        'keuangan_ta_rpjm_kegiatan',
        'keuangan_ta_rpjm_misi',
        'keuangan_ta_rpjm_pagu_indikatif',
        'keuangan_ta_rpjm_pagu_tahunan',
        'keuangan_ta_rpjm_sasaran',
        'keuangan_ta_rpjm_tujuan',
        'keuangan_ta_rpjm_visi',
        'keuangan_ta_saldo_awal',
        'keuangan_ta_spj',
        'keuangan_ta_spjpot',
        'keuangan_ta_spj_bukti',
        'keuangan_ta_spj_rinci',
        'keuangan_ta_spj_sisa',
        'keuangan_ta_spp',
        'keuangan_ta_sppbukti',
        'keuangan_ta_spppot',
        'keuangan_ta_spp_rinci',
        'keuangan_ta_sts',
        'keuangan_ta_sts_rinci',
        'keuangan_ta_tbp',
        'keuangan_ta_tbp_rinci',
        'keuangan_ta_triwulan',
        'keuangan_ta_triwulan_rinci',
    ];
    private $data_siskeudes = [
        'keuangan_ref_bank_desa'          => 'Ref_Bank_Desa.csv',
        'keuangan_ref_bel_operasional'    => 'Ref_Bel_Operasional.csv',
        'keuangan_ref_bidang'             => 'Ref_Bidang.csv',
        'keuangan_ref_bunga'              => 'Ref_Bunga.csv',
        'keuangan_ref_desa'               => 'Ref_Desa.csv',
        'keuangan_ref_kecamatan'          => 'Ref_Kecamatan.csv',
        'keuangan_ref_kegiatan'           => 'Ref_Kegiatan.csv',
        'keuangan_ref_korolari'           => 'Ref_Korolari.csv',
        'keuangan_ref_neraca_close'       => 'Ref_NeracaClose.csv',
        'keuangan_ref_perangkat'          => 'Ref_Perangkat.csv',
        'keuangan_ref_potongan'           => 'Ref_Potongan.csv',
        'keuangan_ref_rek1'               => 'Ref_Rek1.csv',
        'keuangan_ref_rek2'               => 'Ref_Rek2.csv',
        'keuangan_ref_rek3'               => 'Ref_Rek3.csv',
        'keuangan_ref_rek4'               => 'Ref_Rek4.csv',
        'keuangan_ref_sbu'                => 'Ref_SBU.csv',
        'keuangan_ref_sumber'             => 'Ref_Sumber.csv',
        'keuangan_ta_anggaran'            => 'Ta_Anggaran.csv',
        'keuangan_ta_anggaran_log'        => 'Ta_AnggaranLog.csv',
        'keuangan_ta_anggaran_rinci'      => 'Ta_AnggaranRinci.csv',
        'keuangan_ta_bidang'              => 'Ta_Bidang.csv',
        'keuangan_ta_jurnal_umum'         => 'Ta_JurnalUmum.csv',
        'keuangan_ta_jurnal_umum_rinci'   => 'Ta_JurnalUmumRinci.csv',
        'keuangan_ta_kegiatan'            => 'Ta_Kegiatan.csv',
        'keuangan_ta_mutasi'              => 'Ta_Mutasi.csv',
        'keuangan_ta_pajak'               => 'Ta_Pajak.csv',
        'keuangan_ta_pajak_rinci'         => 'Ta_PajakRinci.csv',
        'keuangan_ta_pemda'               => 'Ta_Pemda.csv',
        'keuangan_ta_pencairan'           => 'Ta_Pencairan.csv',
        'keuangan_ta_perangkat'           => 'Ta_Perangkat.csv',
        'keuangan_ta_rab'                 => 'Ta_RAB.csv',
        'keuangan_ta_rab_rinci'           => 'Ta_RABRinci.csv',
        'keuangan_ta_rab_sub'             => 'Ta_RABSub.csv',
        'keuangan_ta_rpjm_bidang'         => 'Ta_RPJM_Bidang.csv',
        'keuangan_ta_rpjm_kegiatan'       => 'Ta_RPJM_Kegiatan.csv',
        'keuangan_ta_rpjm_misi'           => 'Ta_RPJM_Misi.csv',
        'keuangan_ta_rpjm_pagu_indikatif' => 'Ta_RPJM_Pagu_Indikatif.csv',
        'keuangan_ta_rpjm_pagu_tahunan'   => 'Ta_RPJM_Pagu_Tahunan.csv',
        'keuangan_ta_rpjm_sasaran'        => 'Ta_RPJM_Sasaran.csv',
        'keuangan_ta_rpjm_tujuan'         => 'Ta_RPJM_Tujuan.csv',
        'keuangan_ta_rpjm_visi'           => 'Ta_RPJM_Visi.csv',
        'keuangan_ta_saldo_awal'          => 'Ta_SaldoAwal.csv',
        'keuangan_ta_spj'                 => 'Ta_SPJ.csv',
        'keuangan_ta_spjpot'              => 'Ta_SPJPot.csv',
        'keuangan_ta_spj_bukti'           => 'Ta_SPJBukti.csv',
        'keuangan_ta_spj_rinci'           => 'Ta_SPJRinci.csv',
        'keuangan_ta_spj_sisa'            => 'Ta_SPJSisa.csv',
        'keuangan_ta_spp'                 => 'Ta_SPP.csv',
        'keuangan_ta_sppbukti'            => 'Ta_SPPBukti.csv',
        'keuangan_ta_spppot'              => 'Ta_SPPPot.csv',
        'keuangan_ta_spp_rinci'           => 'Ta_SPPRinci.csv',
        'keuangan_ta_sts'                 => 'Ta_STS.csv',
        'keuangan_ta_sts_rinci'           => 'Ta_STSRinci.csv',
        'keuangan_ta_tbp'                 => 'Ta_TBP.csv',
        'keuangan_ta_tbp_rinci'           => 'Ta_TBPRinci.csv',
        'keuangan_ta_triwulan'            => 'Ta_Triwulan.csv',
        'keuangan_ta_triwulan_rinci'      => 'Ta_TriwulanArsip.csv',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('donjolib');
        $this->load->helper('pict_helper');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_KEUANGAN_ZIP,
            'allowed_types' => 'zip',
            'max_size'      => max_upload() * 1024,
        ];
    }

    // $file = nama file yg akan diproses
    private function extract_file($file)
    {
        $data  = get_csv($this->zip_file, $file);
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            if (empty($data[$i]) || ! array_filter($data[$i])) {
                unset($data[$i]);
            } else {
                $data[$i]['id_keuangan_master'] = $this->id_keuangan_master;
            }
        }

        return $data;
    }

    private function get_versi_database()
    {
        $csv_versi = get_csv($this->zip_file, 'Ref_Version.csv');
        if ($csv_versi) {
            return $csv_versi[0]['Versi'];
        }

        return false;
    }

    private function get_tahun_anggaran()
    {
        $csv_anggaran = get_csv($this->zip_file, 'Ta_RAB.csv');
        if ($csv_anggaran) {
            return $csv_anggaran[0]['Tahun'];
        }

        return false;
    }

    private function get_keuangan_master()
    {
        $this->zip_file = $_FILES['keuangan']['tmp_name'];
        if (! empty($this->id_keuangan_master)) {
            return;
        }

        $data_master = [
            'versi_database' => $this->get_versi_database(),
            'tahun_anggaran' => $this->get_tahun_anggaran(),
            'tanggal_impor'  => date('Y-m-d'),
            'aktif'          => 1,
        ];
        $this->db->insert('keuangan_master', $data_master);
        $this->id_keuangan_master = $this->db->insert_id();
    }

    public function extract()
    {
        $_SESSION['success'] = 1;
        $this->get_keuangan_master();

        foreach ($this->data_siskeudes as $tabel_opensid => $file_siskeudes) {
            $data_tabel_siskeudes = $this->extract_file($file_siskeudes);
            if (! empty($data_tabel_siskeudes)) {
                if (! $this->db->insert_batch($tabel_opensid, $data_tabel_siskeudes)) {
                    $_SESSION['success'] = -1;
                }
            }
        }
    }

    public function extract_update()
    {
        $this->id_keuangan_master = (int) str_replace('"', '', $_POST['id_keuangan_master']);
        $tables                   = array_keys($this->data_siskeudes);
        $this->db->where('id_keuangan_master', $this->id_keuangan_master);
        $this->db->delete($tables);
        $this->extract();
        $this->db->where('id', $this->id_keuangan_master)
            ->update('keuangan_master', ['tanggal_impor' => date('Y-m-d')]);
    }

    private function cek_file_valid()
    {
        return $this->get_versi_database() && $this->get_tahun_anggaran();
    }

    public function cek_keuangan_master($file)
    {
        $this->upload->initialize($this->uploadConfig);
        $this->zip_file = $_FILES['keuangan']['tmp_name'];
        if (! $this->cek_file_valid()) {
            return -1;
        }
        $this->db->where('versi_database', $this->get_versi_database());
        $this->db->where('tahun_anggaran', $this->get_tahun_anggaran());
        $result = $this->db->get('keuangan_master')->row();

        return $result;
    }

    public function list_data()
    {
        $data = $this->db->select('*')->order_by('tanggal_impor')->get('keuangan_master')->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no']         = $i + 1;
            $data[$i]['desa_ganda'] = $this->cek_desa($data[$i]['id']);
        }

        return $data;
    }

    public function tahun_anggaran()
    {
        $data = $this->db->select('*')->get('keuangan_master')->row();

        return $data->tahun_anggaran;
    }

    // Daftar tahun anggaran tersimpan, diurut tahun terkini di atas
    public function list_tahun_anggaran()
    {
        $data = $this->db->select('tahun_anggaran')
            ->order_by('tahun_anggaran DESC')
            ->get('keuangan_master')->result_array();

        return array_column($data, 'tahun_anggaran');
    }

    public function data_id_keuangan_master()
    {
        $data = $this->db->select('*')->order_by('tanggal_impor')->get('keuangan_master')->row();

        return $data->id;
    }

    public function data_tahun_keuangan_master()
    {
        $data = $this->db->select('*')->order_by('tanggal_impor')->get('keuangan_master')->row();

        return $data->tahun_anggaran;
    }

    public function artikel_statis_keuangan()
    {
        $this->db->select('id, judul');
        $this->db->where([
            'id_kategori' => 1001,
        ]);
        $results = $this->db->get('artikel')->result_array();
        $link    = [];

        foreach ($results as $result) {
            $link['artikel/' . $result['id']] = $result['judul'];
        }

        return $link;
    }

    public function delete($id = '')
    {
        return $this->db->where('id', $id)->delete('keuangan_master');
    }

    public function cek_desa($id_master)
    {
        return $this->db->select('a.Kd_Desa, d.Nama_Desa')
            ->where('a.id_keuangan_master', $id_master)
            ->distinct()
            ->from('keuangan_ta_rab a')
            ->join('keuangan_ref_desa d', 'a.Kd_Desa = d.Kd_Desa and a.id_keuangan_master = d.id_keuangan_master', 'left')
            ->get()
            ->result_array();
    }

    // Hapus data yg bukan untuk $desa
    public function bersihkan_desa($id_master, $desa)
    {
        foreach ($this->tabel_berdesa as $tabel) {
            $this->db->where('Kd_Desa <>', $desa)
                ->where('id_keuangan_master', $id_master)->delete($tabel);
        }
    }
}
