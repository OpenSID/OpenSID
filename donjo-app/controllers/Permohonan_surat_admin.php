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

use App\Models\Config;
use App\Models\FormatSurat;
use App\Models\Pamong;
use App\Models\PermohonanSurat;

defined('BASEPATH') || exit('No direct script access allowed');

class Permohonan_surat_admin extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['permohonan_surat_model', 'penduduk_model', 'surat_model', 'keluarga_model', 'mailbox_model', 'surat_master_model']);
        $this->modul_ini     = 4;
        $this->sub_modul_ini = 98;
    }

    public function index()
    {
        return view('admin.permohonan_surat.index', [
            'list_status_permohonan' => $this->referensi_model->list_ref_flip(STATUS_PERMOHONAN),
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables(PermohonanSurat::query())
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        if ($row->status == 0) {
                            $aksi .= '<a class="btn btn-social bg-navy btn-flat btn-sm btn-proses" title="Surat Belum Lengkap" style="width: 170px"><i class="fa fa-info-circle"></i>Belum Lengkap</a> ';
                        } elseif ($row->status == 1) {
                            $aksi .= '<a href="' . route('permohonan_surat_admin/periksa/', $row->id) . '" class="btn btn-social btn-info btn-flat btn-sm pesan-hover" title="Klik untuk memeriksa" style="width: 170px"><i class="fa fa-spinner"></i>Sedang Diperiksa</a> ';
                        } elseif ($row->status == 2) {
                            $aksi .= '<a class="btn btn-social bg-purple btn-flat btn-sm btn-proses" title="Surat Menunggu Tandatangan" style="width: 170px"><i class="fa fa-edit"></i>Menunggu Tandatangan</a> ';
                        } elseif ($row->status == 3) {
                            $aksi .= '<a href="' . route("permohonan_surat_admin/proses/{$row->id}/4") . '" class="btn btn-social bg-orange btn-flat btn-sm pesan-hover" title="Klik jika telah diambil" style="width: 170px"><i class="fa fa-thumbs-o-up"></i>Siap Diambil</a> ';
                        } elseif ($row->status == 4) {
                            $aksi .= '<a class="btn btn-social btn-success btn-flat btn-sm btn-proses" title="Surat Sudah Diambil" style="width: 170px"><i class="fa fa-check"></i>Sudah Diambil</a> ';
                        } else {
                            $aksi .= '<a class="btn btn-social btn-danger btn-flat btn-sm btn-proses" title="Surat Dibatalkan" style="width: 170px"><i class="fa fa-times"></i>Dibatalkan</a> ';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('no_antrian', static function ($row) {
                    return get_antrian($row->no_antrian);
                })
                ->editColumn('created_at', static function ($row) {
                    return tgl_indo2($row->created_at);
                })
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function periksa($id = '')
    {
        // Cek hanya status = 1 (sedang diperiksa) yg boleh di proses
        $periksa = $this->permohonan_surat_model->get_permohonan(['id' => $id, 'status' => 1]);

        if (! $id || ! $periksa) {
            redirect('permohonan_surat_admin');
        }

        $surat = FormatSurat::find($periksa['id_surat']);
        $url   = $surat['url_surat'];

        $data['periksa']      = $periksa;
        $data['url']          = $url;
        $data['list_dokumen'] = $this->penduduk_model->list_dokumen($periksa['id_pemohon']);
        $data['individu']     = $this->surat_model->get_penduduk($periksa['id_pemohon']);
        $data['anggota']      = $this->keluarga_model->list_anggota($data['individu']['id_kk']);
        $this->get_data_untuk_form($url, $data);
        $data['surat_url']         = rtrim($_SERVER['REQUEST_URI'], '/clear');
        $data['isian_form']        = json_encode($this->ambil_isi_form($periksa['isian_form']));
        $data['syarat_permohonan'] = $this->permohonan_surat_model->get_syarat_permohonan($id);
        $data['form_action']       = site_url("surat/periksa_doc/{$id}/{$url}");
        $data['form_surat']        = 'surat/form_surat.php';

        $data_form = $this->surat_model->get_data_form($url);
        if (is_file($data_form)) {
            include $data_form;
        }

        if (in_array($data['surat']['jenis'], [3, 4])) {
            $data['list_dokumen'] = empty($_POST['nik']) ? null : $this->penduduk_model->list_dokumen($data['individu']['id']);
            $data['form_action']  = route("surat/pratinjau/{$url}/{$id}");
            $data['kode_isian']   = json_decode($data['surat']['kode_isian']);
            $data['form_surat']   = 'surat/form_surat_tinymce.php';
        }

        $this->render('mandiri/periksa_surat', $data);
    }

    public function proses($id = '', $status = '')
    {
        $this->permohonan_surat_model->proses($id, $status);

        redirect('permohonan_surat_admin');
    }

    // TODO:: Duplikasi dengan kode yang ada di donjo-app/controllers/Surat.php
    private function get_data_untuk_form($url, &$data)
    {
        $config = Config::first();

        $data['config']             = $config;
        $data['lokasi']             = $config;
        $data['surat']              = FormatSurat::where('url_surat', $url)->first();
        $data['surat_terakhir']     = $this->surat_model->get_last_nosurat_log($url);
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
        $data['penduduk']           = $this->surat_model->list_penduduk();
        $data['perempuan']          = $this->surat_model->list_penduduk_perempuan();
        $data['pamong']             = $this->surat_model->list_pamong();

        $kades = Pamong::kepalaDesa()->first(); // Kepala Desa
        if ($kades) {
            $data['atas_nama'][''] = $kades->pamong_jabatan . ' ' . $config->nama_desa;

            $sekdes = Pamong::ttd('a.n')->first(); // Sekretaris Desa
            if ($sekdes) {
                $data['atas_nama']['a.n'] = 'a.n ' . $kades->pamong_jabatan . ' ' . $config->nama_desa;

                $pamong = Pamong::ttd('u.b')->exists(); // Sekretaris Desa
                if ($pamong) {
                    $data['atas_nama']['u.b'] = 'u.b ' . $sekdes->pamong_jabatan . ' ' . $config->nama_desa;
                }
            }
        } else {
            session_error(', ' . setting('sebutan_kepala_desa') . ' belum ditentukan.');
            redirect('pengurus');
        }
    }

    private function ambil_isi_form($isian_form)
    {
        $isian_form = json_decode($isian_form, true);
        $hapus      = ['url_surat', 'url_remote', 'nik', 'id_surat', 'nomor', 'pilih_atas_nama', 'pamong', 'pamong_nip', 'jabatan', 'pamong_id'];

        foreach ($hapus as $kolom) {
            unset($isian_form[$kolom]);
        }

        return $isian_form;
    }

    public function konfirmasi($id_permohonan = 0, $tipe = 0)
    {
        $data['form_action'] = site_url("permohonan_surat_admin/kirim_pesan/{$id_permohonan}/{$tipe}");

        $this->load->view('surat/form/konfirmasi_permohonan', $data);
    }

    public function kirim_pesan($id_permohonan = 0, $tipe = 0)
    {
        $periksa = $this->permohonan_surat_model->get_permohonan(['id' => $id_permohonan, 'status' => 1]);
        $pemohon = $this->surat_model->get_penduduk($periksa['id_pemohon']);
        $post    = $this->input->post();
        $judul   = ($tipe == 0) ? 'Perlu Dilengkapi' : 'Dibatalkan';
        $data    = [
            'subjek'     => 'Permohonan Surat ' . $surat['nama'] . ' ' . $judul,
            'komentar'   => $post['pesan'],
            'owner'      => $pemohon['nama'], // TODO : Gunakan id_pend
            'email'      => $pemohon['nik'], // TODO : Gunakan id_pend
            'permohonan' => $id_permohonan, // Menyimpan id_permohonan untuk link
            'tipe'       => 2,
            'status'     => 2,
        ];

        $this->mailbox_model->insert($data);
        $this->proses($id_permohonan, $tipe);

        redirect('permohonan_surat_admin');
    }

    public function tampilkan($id_dokumen, $id_pend = 0)
    {
        $this->load->model('Web_dokumen_model');
        $berkas = $this->web_dokumen_model->get_nama_berkas($id_dokumen, $id_pend);

        if (! $id_dokumen || ! $id_pend || ! $berkas || ! file_exists(LOKASI_DOKUMEN . $berkas)) {
            $data['link_berkas'] = null;
        } else {
            $data = [
                'link_berkas' => site_url("dokumen/tampilkan_berkas/{$id_dokumen}/{$id_pend}"),
                'tipe'        => get_extension($berkas),
                'link_unduh'  => site_url("dokumen/unduh_berkas/{$id_dokumen}/{$id_pend}"),
            ];
        }
        $this->load->view('global/tampilkan', $data);
    }
}
