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

use App\Libraries\TinyMCE;
use App\Models\Dokumen;
use App\Models\DokumenHidup;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\PesanMandiri;

defined('BASEPATH') || exit('No direct script access allowed');

class Permohonan_surat_admin extends Admin_Controller
{
    public $modul_ini     = 'layanan-surat';
    public $sub_modul_ini = 'permohonan-surat';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.permohonan_surat.index', [
            'list_status_permohonan' => PermohonanSurat::STATUS_PERMOHONAN,
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables(PermohonanSurat::status((string) $this->input->get('status')))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        if ($row->status == PermohonanSurat::BELUM_LENGKAP) {
                            $aksi .= '<a class="btn btn-social bg-navy btn-sm btn-proses" title="Surat Belum Lengkap" style="width: 170px"><i class="fa fa-info-circle"></i> ' . PermohonanSurat::STATUS_PERMOHONAN[PermohonanSurat::BELUM_LENGKAP] . '</a> ';
                        } elseif ($row->status == PermohonanSurat::SEDANG_DIPERIKSA) {
                            $aksi .= '<a href="' . ci_route('permohonan_surat_admin.periksa', $row->id) . '" class="btn btn-social btn-info btn-sm pesan-hover" title="Klik untuk memeriksa" style="width: 170px"><i class="fa fa-spinner"></i>' . PermohonanSurat::STATUS_PERMOHONAN[PermohonanSurat::SEDANG_DIPERIKSA] . '</a> ';
                        } elseif ($row->status == PermohonanSurat::MENUNGGU_TANDA_TANGAN) {
                            if (in_array($row->surat->jenis, FormatSurat::TINYMCE) && (setting('verifikasi_sekdes') || setting('verifikasi_kades'))) {
                                $aksi .= '<a class="btn btn-social bg-purple btn-sm btn-proses" title="Surat Menunggu Tandatangan" style="width: 170px"><i class="fa fa-edit"></i>' . PermohonanSurat::STATUS_PERMOHONAN[PermohonanSurat::MENUNGGU_TANDA_TANGAN] . '</a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route("permohonan_surat_admin.proses.{$row->id}.3") . '" class="btn btn-social bg-purple btn-sm" title="Surat Menunggu Tandatangan" style="width: 170px"><i class="fa fa-edit"></i>' . PermohonanSurat::STATUS_PERMOHONAN[PermohonanSurat::MENUNGGU_TANDA_TANGAN] . '</a> ';
                            }
                        } elseif ($row->status == PermohonanSurat::SIAP_DIAMBIL) {
                            $aksi .= '<a href="' . ci_route("permohonan_surat_admin.proses.{$row->id}.4") . '" class="btn btn-social bg-orange btn-sm pesan-hover" title="Klik jika telah diambil" style="width: 170px"><i class="fa fa-thumbs-o-up"></i>' . PermohonanSurat::STATUS_PERMOHONAN[PermohonanSurat::SIAP_DIAMBIL] . '</a> ';
                        } elseif ($row->status == PermohonanSurat::SUDAH_DIAMBIL) {
                            $aksi .= '<a class="btn btn-social btn-success btn-sm btn-proses" title="Surat Sudah Diambil" style="width: 170px"><i class="fa fa-check"></i>' . PermohonanSurat::STATUS_PERMOHONAN[PermohonanSurat::SUDAH_DIAMBIL] . '</a> ';
                        } else {
                            $aksi .= '<a class="btn btn-social btn-danger btn-sm btn-proses" title="Surat Dibatalkan" style="width: 170px"><i class="fa fa-times"></i>' . PermohonanSurat::STATUS_PERMOHONAN[PermohonanSurat::DIBATALKAN] . '</a> ';

                            if (can('h') && ci_auth()->id == super_admin()) {
                                $aksi .= '<a href="#" data-href="' . ci_route('permohonan_surat_admin.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                            }
                        }
                    }

                    return $aksi;
                })
                ->editColumn('no_antrian', static fn ($row) => get_antrian($row->no_antrian))
                ->editColumn('created_at', static fn ($row) => tgl_indo2($row->created_at))
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function periksa($id = ''): void
    {
        // Cek hanya status = 1 (sedang diperiksa) yg boleh di proses
        $periksa = PermohonanSurat::whereStatus(PermohonanSurat::SEDANG_DIPERIKSA)->find($id);

        if (! $id || ! $periksa) {
            show_404();
        }
        $url = $periksa->surat->url_surat;

        $penduduk = Penduduk::find($periksa->id_pemohon);
        $individu = $penduduk->formIndividu();

        $data['periksa']  = $periksa;
        $data['surat']    = $periksa->surat;
        $data['url']      = $url;
        $data['individu'] = $individu;
        $this->get_data_untuk_form($url, $data);
        $data['isian_form']        = json_encode($this->ambil_isi_form($periksa->isian_form), JSON_THROW_ON_ERROR);
        $data['surat_url']         = rtrim((string) $_SERVER['REQUEST_URI'], '/clear');
        $data['syarat_permohonan'] = $periksa->mapSyaratSurat();
        $data['list_dokumen']      = empty($_POST['nik']) ? null : DokumenHidup::whereIdPend($periksa->id_pemohon)->get()->toArray();
        $data['form_action']       = ci_route("surat.pratinjau.{$url}.{$id}");

        $pesan   = 'Permohonan Surat - ' . $periksa->surat->nama . ' - sedang dalam proses oleh operator';
        $judul   = 'Permohonan Surat - ' . $periksa->surat->nama . ' - sedang dalam proses';
        $payload = '/layanan';
        // kirim notifikasi fcm
        $this->kirim_notifikasi_penduduk($periksa->id_pemohon, $pesan, $judul, $payload);

        view('admin.permohonan_surat.periksa_surat', $data);
    }

    public function proses($id = '', $status = 0): void
    {
        $permohonan = PermohonanSurat::find($id);
        $permohonan->update(['status' => $status]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    private function get_data_untuk_form($url, array &$data): void
    {
        // Panggil 1 penduduk berdasarkan datanya sendiri
        $data['penduduk'] = [$data['periksa']['penduduk']];

        $data['surat_terakhir']     = LogSurat::lastNomerSurat($url);
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = FormatSurat::format_penomoran_surat($data);

        $tinymce           = new TinyMCE();
        $penandatangan     = $tinymce->formPenandatangan();
        $data['pamong']    = $penandatangan['penandatangan'];
        $data['atas_nama'] = $penandatangan['atas_nama'];
    }

    /**
     * @return mixed[]
     */
    private function ambil_isi_form(array $isian_form): array
    {
        $hapus = ['url_surat', 'url_remote', 'nik', 'id_surat', 'nomor', 'pilih_atas_nama', 'pamong', 'pamong_nip', 'jabatan', 'pamong_id'];

        foreach ($hapus as $kolom) {
            unset($isian_form[$kolom]);
        }

        return $isian_form;
    }

    public function konfirmasi($id_permohonan = 0, $tipe = 0): void
    {
        $data['form_action'] = ci_route("permohonan_surat_admin.kirim_pesan.{$id_permohonan}.{$tipe}");

        view('admin.permohonan_surat.konfirmasi_permohonan', $data);
    }

    public function kirim_pesan($id_permohonan = 0, $tipe = 0): void
    {
        $tipe ??= 0;
        $periksa = PermohonanSurat::with(['surat'])->where(['id' => $id_permohonan, 'status' => PermohonanSurat::SEDANG_DIPERIKSA])->first()->toArray();
        $pemohon = Penduduk::find($periksa['id_pemohon'])->toArray();
        $post    = $this->input->post();
        $judul   = ($tipe == 0) ? 'Perlu Dilengkapi' : 'Dibatalkan';
        $data    = [
            'owner'       => $pemohon['nama'], // TODO : Gunakan id_pend
            'penduduk_id' => $periksa['id_pemohon'],
            'subjek'      => 'Permohonan Surat ' . $periksa['surat']['nama'] . ' ' . $judul,
            'komentar'    => $post['pesan'],
            'status'      => 2,
            'tipe'        => 2,
        ];

        PesanMandiri::create($data);
        $this->proses($id_permohonan, $tipe);

        $this->kirim_notifikasi_penduduk($id_permohonan, $data['komentar'], $data['subjek'], '/layanan');

        redirect('permohonan_surat_admin');
    }

    public function delete($id = ''): void
    {
        isCan('h');

        $delete = PermohonanSurat::where('status', PermohonanSurat::DIBATALKAN)->find($id) ?? show_404();

        if ($delete->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function tampilkan($id_dokumen, $id_pend = 0): void
    {
        $berkasObj = Dokumen::aktif()->whereId($id_dokumen)->first();
        $berkas    = $berkasObj ? $berkasObj->satuan : null;

        if (! $id_dokumen || ! $id_pend || ! $berkas || ! file_exists(LOKASI_DOKUMEN . $berkas)) {
            $data['link_berkas'] = null;
        } else {
            $data = [
                'link_berkas' => site_url("{$this->controller}/tampilkan_berkas/{$id_dokumen}/{$id_pend}"),
                'tipe'        => get_extension($berkas),
                'link_unduh'  => site_url("{$this->controller}/unduh_berkas/{$id_dokumen}/{$id_pend}"),
            ];
        }
        view('admin.layouts.components.tampilkan', $data);
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int        $id_dokumen Id berkas pada koloam dokumen.id
     * @param mixed|null $id_pend
     */
    public function unduh_berkas($id_dokumen, $id_pend = null, mixed $tampil = false): void
    {
        // Ambil nama berkas dari database
        $data = Dokumen::find($id_dokumen);
        ambilBerkas($data->satuan ?? '', $this->controller, null, LOKASI_DOKUMEN, $tampil);
    }

    public function tampilkan_berkas($id_dokumen, $id_pend = null): void
    {
        $this->unduh_berkas($id_dokumen, $id_pend, $tampil = true);
    }
}
