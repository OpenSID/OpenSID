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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\JenisPeraturan;
use App\Enums\StatusEnum;
use App\Models\Dokumen;
use App\Models\DokumenHidup;

class Lembaran_desa extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-umum';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['jenis_peraturan'] = JenisPeraturan::all();
        $sebutan_desa            = ucwords((string) setting('sebutan_desa'));
        $data['main_content']    = 'admin.dokumen.lembaran_desa.index';
        $data['subtitle']        = "Buku Lembaran {$sebutan_desa} Dan Berita {$sebutan_desa}";
        $data['selected_nav']    = 'lembaran';
        $data['status']          = request('status');
        view('admin.bumindes.umum.main', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(DokumenHidup::PeraturanDesa(3))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('lembaran_desa.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('u')) {
                        if ($row->enabled == StatusEnum::YA) {
                            $aksi .= '<a href="' . ci_route('lembaran_desa.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('lembaran_desa.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if ($row->satuan != null) {
                        $aksi .= '<a href="' . ci_route('lembaran_desa.unduh_berkas', $row->id) . '" class="btn bg-purple btn-sm" title="Unduh"><i class="fa fa-download"></i></a> ';
                    } else {
                        $aksi .= '<a class="btn bg-purple btn-sm" disabled title="Unduh"><i class="fa fa-download"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == StatusEnum::YA ? 'Ya' : 'Tidak')
                ->editColumn('additional', static function ($row): array {
                    $attr                    = json_decode($row->attr, true);
                    $data['jenis_peraturan'] = $attr['jenis_peraturan'];
                    $data['tgl_ditetapkan']  = strip_kosong($attr['no_ditetapkan']) . ' / ' . $attr['tgl_ditetapkan'];
                    $data['uraian_singkat']  = $attr['uraian'];

                    return $data;
                })
                ->rawColumns(['aksi', 'additional'])
                ->make();
        }

        return show_404();
    }

    public function form($id = ''): void
    {
        isCan('u');
        $data['controller'] = $this->controller;

        if ($id) {
            $data['dokumen']     = DokumenHidup::GetDokumen($id);
            $data['form_action'] = site_url("lembaran_desa/update/{$id}");
        }

        $data['jenis_peraturan'] = JenisPeraturan::all();
        $data['kat_nama']        = 'Lembaran Desa';
        $data['isi']             = 'admin.layouts.components.kades._perdes';

        view('admin.dokumen.buku_kades.form', $data);
    }

    public function update($id = ''): void
    {
        isCan('u');

        try {
            $data    = $this->validasi($this->request);
            $dokumen = Dokumen::findOrFail($id);

            if ($this->input->post('satuan')) {
                $data['satuan'] = $this->upload_dokumen();
            }

            $dokumen->update($data);

            redirect_with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal disimpan');
        }
    }

    private function upload_dokumen()
    {
        $old_file                = $this->input->post('old_file', true);
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['file_name']     = namafile($this->input->post('nama', true));

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('satuan')) {
            session_error($this->upload->display_errors(null, null));

            return false;
        }

        if (empty($old_file)) {
            unlink(LOKASI_DOKUMEN . $old_file);
        }

        return $this->upload->data()['file_name'];
    }

    private function validasi(array $post): array
    {
        $data                         = [];
        $data['nama']                 = nomor_surat_keputusan($post['nama']);
        $data['kategori']             = (int) $post['kategori'] ?: 1;
        $data['kategori_info_publik'] = (int) $post['kategori_info_publik'] ?: null;
        $data['id_syarat']            = (int) $post['id_syarat'] ?: null;
        $data['id_pend']              = (int) $post['id_pend'] ?: 0;
        $data['tipe']                 = (int) $post['tipe'];
        $data['url']                  = $this->security->xss_clean($post['url']) ?: null;

        if ($data['tipe'] == 1) {
            $data['url'] = null;
        }

        $data['tahun']                     = date('Y', strtotime((string) $post['attr']['tgl_ditetapkan']));
        $data['kategori_info_publik']      = '3';
        $data['attr']['tgl_ditetapkan']    = $post['attr']['tgl_ditetapkan'];
        $data['attr']['tgl_lapor']         = $post['attr']['tgl_lapor'];
        $data['attr']['tgl_kesepakatan']   = $post['attr']['tgl_kesepakatan'];
        $data['attr']['uraian']            = $this->security->xss_clean($post['attr']['uraian']);
        $data['attr']['jenis_peraturan']   = htmlentities((string) $post['attr']['jenis_peraturan']);
        $data['attr']['no_ditetapkan']     = nomor_surat_keputusan($post['attr']['no_ditetapkan']);
        $data['attr']['no_lapor']          = nomor_surat_keputusan($post['attr']['no_lapor']);
        $data['attr']['no_lembaran_desa']  = nomor_surat_keputusan($post['attr']['no_lembaran_desa']);
        $data['attr']['no_berita_desa']    = nomor_surat_keputusan($post['attr']['no_berita_desa']);
        $data['attr']['tgl_lembaran_desa'] = $post['attr']['tgl_lembaran_desa'];
        $data['attr']['tgl_berita_desa']   = $post['attr']['tgl_berita_desa'];
        $data['attr']['keterangan']        = htmlentities((string) $post['attr']['keterangan']);

        return $data;
    }

    public function lock($id = ''): void
    {
        isCan('u');
        if (Dokumen::gantiStatus($id, 'enabled')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }
        redirect_with('error', 'Gagal Ubah Status');
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['list_tahun'] = DokumenHidup::GetTahun(3);
        $data['formAction'] = ci_route('lembaran_desa.cetak', $aksi);

        return view('admin.dokumen.lembaran_desa.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $data          = $this->modal_penandatangan();
        $data['aksi']  = $aksi;
        $laporan       = DokumenHidup::PeraturanDesa(3)->get();
        $data['tahun'] = $this->input->post('tahun');
        if ($data['tahun']) {
            $regex   = '"tgl_ditetapkan":"[[:digit:]]{2}-[[:digit:]]{2}-' . $data['tahun'];
            $laporan = DokumenHidup::PeraturanDesa(3)->whereRaw("attr REGEXP '" . $regex . "'")->get();
        }
        $data['main'] = $laporan->map(static function ($document) {
            $array = $document->toArray();
            if (isset($array['attr'])) {
                $array['attr'] = json_decode((string) $array['attr'], true);
            }

            return $array;
        })->toArray();

        $data['file']      = 'Lembaran Desa';
        $data['isi']       = 'admin.dokumen.lembaran_desa.cetak';
        $data['letak_ttd'] = ['1', '1', '2'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int $id_dokumen Id berkas pada koloam dokumen.id
     */
    public function unduh_berkas($id_dokumen = 0): void
    {
        // Ambil nama berkas dari database
        $data = DokumenHidup::GetDokumen($id_dokumen);
        ambilBerkas($data['satuan'], $this->controller, null, LOKASI_DOKUMEN);
    }
}
