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

use App\Enums\JenisPeraturan;
use App\Enums\StatusEnum;
use App\Models\Dokumen;
use App\Models\DokumenHidup;
use App\Models\RefDokumen;

defined('BASEPATH') || exit('No direct script access allowed');

class Dokumen_sekretariat extends Admin_Controller
{
    public $modul_ini           = 'buku-administrasi-desa';
    public $sub_modul_ini       = 'administrasi-umum';
    private array $list_session = ['filter', 'cari', 'jenis_peraturan', 'tahun'];
    private array $_set_page    = ['50', '100', '200'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        $this->load->model('web_dokumen_model');
    }

    public function index($kat = 2, $p = 1, $o = 0): void
    {
        if ($this->input->post('per_page') !== null) {
            $this->session->per_page = $this->input->post('per_page');
        }
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}/{$p}/{$o}");
    }

    // Mulai Perdes
    public function perdes($kat = 2): void
    {
        $this->peraturan_desa($kat);
    }
    // End Perdes

    // Produk Hukum Desa
    public function peraturan_desa($kat = 2): void
    {
        $data['func']            = "index/{$kat}";
        $data['kat']             = $kat;
        $data['controller']      = $this->controller;
        $data['kat_nama']        = RefDokumen::find($kat)?->nama ?? RefDokumen::first()?->nama;
        $data['main']            = DokumenHidup::PeraturanDesa($kat)->get();
        $data['list_tahun']      = DokumenHidup::GetTahun($kat);
        $data['submenu']         = RefDokumen::get();
        $data['jenis_peraturan'] = JenisPeraturan::all();

        $data['sub_kategori']      = $_SESSION['sub_kategori'];
        $_SESSION['menu_kategori'] = true;

        foreach ($data['submenu'] as $s) {
            if ($kat == $s['id']) {
                $_SESSION['submenu']       = $s['id'];
                $_SESSION['sub_kategori']  = $s['kategori'];
                $_SESSION['kode_kategori'] = $s['id'];
            }
        }
        $data['main_content'] = 'admin.dokumen.buku_kades.table_buku_umum';
        $data['subtitle']     = ($kat == '3') ? 'Buku Peraturan di ' . ucwords(setting('sebutan_desa')) : 'Buku Keputusan ' . ucwords(setting('sebutan_kepala_desa'));
        $data['selected_nav'] = ($kat == '3') ? 'peraturan' : 'keputusan';
        $data['active']       = request('active');
        view('admin.bumindes.umum.main', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $kategori = $this->input->get('kategori');
            $tahun    = $this->input->get('tahun');
            $data     = DokumenHidup::peraturanDesa($kategori, $tahun);

            return datatables()->of($data)
                ->orderColumn('attr->tgl_kep_kades', static function ($query, $order) {
                    $query->orderBy('attr->tgl_kep_kades', $order);
                })
                ->orderColumn('attr->tgl_ditetapkan', static function ($query, $order) {
                    $query->orderBy('attr->tgl_ditetapkan', $order);
                })
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($kategori): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('buku-umum.dokumen_sekretariat.form', ['kat' => $kategori, 'id' => $row->id]) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if ($row->satuan != null) {
                        $aksi .= '<a href="' . site_url("dokumen_sekretariat/berkas/{$row->id}/{$row->kategori}/0") . '" class="btn bg-purple btn-sm" title="Unduh"><i class="fa fa-download"></i></a> ';
                    } else {
                        $aksi .= '<a class="btn bg-purple btn-sm" disabled title="Unduh"><i class="fa fa-download"></i></a> ';
                    }

                    if (can('u')) {
                        if ($row->enabled == StatusEnum::YA) {
                            $aksi .= '<a href="' . route('buku-umum.dokumen_sekretariat.lock', ['kat' => $kategori, 'id' => $row->id]) . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . route('buku-umum.dokumen_sekretariat.lock', ['kat' => $kategori, 'id' => $row->id]) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('buku-umum.dokumen_sekretariat.delete', ['kat' => $kategori, 'id' => $row->id]) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi . ('<a href="' . route('buku-umum.dokumen_sekretariat.berkas', ['id_dokumen' => $row->id, 'kat' => $kategori, 'tipe' => 1]) . '" target="_blank" class="btn btn-info btn-sm" title="Lihat Dokumen"><i class="fa fa-eye"></i></a>');
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == StatusEnum::YA ? 'Ya' : 'Tidak')
                ->editColumn('additional', static function ($row): array {
                    $attr = json_decode($row->attr, true);
                    if ($row->kategori == 1) {
                        $data['kategori_info_publik'] = $attr['no_kep_kades'] . ' / ' . $attr['tgl_kep_kades'];
                        $data['tahun']                = $attr['tahun'];
                    } elseif ($row->kategori == 2) {
                        $data['tgl_keputusan']  = $attr['no_kep_kades'] . ' / ' . $attr['tgl_kep_kades'];
                        $data['uraian_singkat'] = $attr['uraian'];
                    } elseif ($row->kategori == 3) {
                        $data['jenis_peraturan'] = $attr['jenis_peraturan'];
                        $data['tgl_ditetapkan']  = strip_kosong($attr['no_ditetapkan']) . ' / ' . $attr['tgl_ditetapkan'];
                        $data['uraian_singkat']  = $attr['uraian'];
                    }

                    return $data;
                })
                ->rawColumns(['ceklist', 'aksi', 'additional'])
                ->make();
        }

        return show_404();
    }

    public function form($kat = 2, $id = ''): void
    {
        isCan('u');
        $data['kat']        = $kat;
        $data['controller'] = $this->controller;

        if ($kat == 3) {
            $data['jenis_peraturan'] = JenisPeraturan::all();
        }

        if ($id) {
            $data['dokumen']     = DokumenHidup::GetDokumen($id);
            $data['form_action'] = site_url("dokumen_sekretariat/update/{$kat}/{$id}");
        } else {
            $data['dokumen']     = null;
            $data['form_action'] = site_url('dokumen_sekretariat/insert');
        }
        $data['kat_nama'] = RefDokumen::findOrFail($kat)->nama;

        if ($kat == 2 || $data['dokumen']['kategori'] == 2) {
            $data['isi'] = 'admin.layouts.components.kades._sk_kades';
        } elseif ($kat == 3 || $data['dokumen']['kategori'] == 3) {
            $data['isi'] = 'admin.layouts.components.kades._perdes';
        } else {
            $data['isi'] = 'admin.layouts.components.kades._informasi_publik';
        }

        $this->_set_tab($kat);

        view('admin.dokumen.buku_kades.form', $data);
    }

    public function search(): void
    {
        $cari = $this->input->post('cari');
        $kat  = $this->input->post('kategori');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        redirect("dokumen_sekretariat/index/{$kat}");
    }

    public function filter($filter = 'filter'): void
    {
        $this->session->{$filter} = $this->input->post($filter);
        $kat                      = $this->input->post('kategori');
        redirect("dokumen_sekretariat/index/{$kat}");
    }

    public function insert(): void
    {
        isCan('u');

        try {
            $data = $this->validasi($this->request);

            if ($this->input->post('satuan')) {
                $data['satuan'] = $result = $this->upload_dokumen();
            }

            if ($result === false && $data['tipe'] == 1) {
                redirect_with('error', 'Data gagal disimpan', route('buku-umum.dokumen_sekretariat.perdes', $this->input->post('kategori')));
            }

            Dokumen::create($data);

            redirect_with('success', 'Data berhasil disimpan', route('buku-umum.dokumen_sekretariat.perdes', $this->input->post('kategori')));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal disimpan', route('buku-umum.dokumen_sekretariat.perdes', $this->input->post('kategori')));
        }
    }

    public function update($kat, $id = ''): void
    {
        isCan('u');

        $redirect = $this->input->post('link_redirect');

        if (empty($redirect)) {
            $redirect = route('buku-umum.dokumen_sekretariat.perdes', $this->input->post('kategori'));
        }

        try {
            $data    = $this->validasi($this->request);
            $dokumen = Dokumen::findOrFail($id);

            if ($this->input->post('satuan')) {
                $data['satuan'] = $this->upload_dokumen();
            }

            $dokumen->update($data);

            redirect_with('success', 'Data berhasil disimpan', $redirect);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal disimpan', $redirect);
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
        $data['nama']                 = nama_peraturan_desa($post['nama']);
        $data['kategori']             = (int) $post['kategori'] ?: 1;
        $data['kategori_info_publik'] = (int) $post['kategori_info_publik'] ?: null;
        $data['id_syarat']            = (int) $post['id_syarat'] ?: null;
        $data['id_pend']              = (int) $post['id_pend'] ?: 0;
        $data['tipe']                 = (int) $post['tipe'];
        $data['url']                  = $this->security->xss_clean($post['url']) ?: null;

        if ($data['tipe'] == 1) {
            $data['url'] = null;
        }

        switch ($data['kategori']) {
            case 1: //Informsi Publik
                $data['tahun'] = $post['tahun'];
                break;

            case 2: //SK Kades
                $data['tahun']                 = date('Y', strtotime((string) $post['attr']['tgl_kep_kades']));
                $data['kategori_info_publik']  = '3';
                $data['attr']['tgl_kep_kades'] = $post['attr']['tgl_kep_kades'];
                $data['attr']['uraian']        = $this->security->xss_clean($post['attr']['uraian']);
                $data['attr']['no_kep_kades']  = nomor_surat_keputusan($post['attr']['no_kep_kades']);
                $data['attr']['no_lapor']      = nomor_surat_keputusan($post['attr']['no_lapor']);
                $data['attr']['tgl_lapor']     = $post['attr']['tgl_lapor'];
                $data['attr']['keterangan']    = $this->security->xss_clean($post['attr']['keterangan']);
                break;

            case 3: //Perdes
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
                break;

            default:
                $data['tahun'] = date('Y');
                break;
        }

        return $data;
    }

    public function delete($kat = 1, $id = ''): void
    {
        isCan('h');

        try {
            Dokumen::destroy($id);
            redirect_with('success', 'Data berhasil dihapus', route('buku-umum.dokumen_sekretariat.perdes', $kat));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal dihapus', route('buku-umum.dokumen_sekretariat.perdes', $kat));
        }
    }

    public function delete_all($kat = ''): void
    {
        isCan('h');

        try {
            Dokumen::destroy($this->request['id_cb']);
            redirect_with('success', 'Data berhasil dihapus', route('buku-umum.dokumen_sekretariat.perdes', $kat));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal dihapus', route('buku-umum.dokumen_sekretariat.perdes', $kat));
        }
    }

    public function lock($kat = null, $id = ''): void
    {
        isCan('u');
        if (Dokumen::gantiStatus($id, 'enabled')) {
            redirect_with('success', 'Berhasil Ubah Status', route('buku-umum.dokumen_sekretariat.perdes', $kat));
        }
        redirect_with('error', 'Gagal Ubah Status', route('buku-umum.dokumen_sekretariat.perdes', $kat));
    }

    // $aksi = cetak/unduh
    public function dialog_cetak($kat = 0, $aksi = 'cetak')
    {
        $data['tahun_laporan']   = DokumenHidup::getTahun($kat);
        $data['aksi']            = $aksi;
        $data['kat']             = $kat;
        $data['jenis_peraturan'] = JenisPeraturan::all();
        $data['form_action']     = route('buku-umum.dokumen_sekretariat.daftar', ['kat' => $kat, 'aksi' => $aksi]);

        return view('admin.layouts.components.kades.dialog_cetak', $data);
    }

    public function cetak($kat = 1): void
    {
        $data     = $this->data_cetak($kat);
        $template = $data['template'];
        $this->load->view("dokumen/{$template}", $data);
    }

    public function daftar($id = 0, $aksi = '')
    {
        if ($id > 0) {
            $data            = $this->data_cetak($id);
            $data['sasaran'] = unserialize(SASARAN);
            $data['aksi']    = $aksi;

            //pengaturan data untuk format cetak/ unduh
            $data['isi']       = $data['template'];
            $data['letak_ttd'] = ['1', '1', '3'];

            return view('admin.layouts.components.format_cetak', $data);
        }

        return show_404();
    }

    private function data_cetak($kat)
    {
        $this->load->model('pamong_model');
        // Agar tidak terlalu banyak mengubah kode, karena menggunakan view global
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);

        $post = $this->input->post();

        $query = datatables(DokumenHidup::dataCetak($kat, $post['tahun'], $post['jenis_peraturan']))
            ->orderColumn('attr->tgl_kep_kades', static function ($query, $order) {
                $query->orderBy('attr->tgl_kep_kades', $order);
            })
            ->orderColumn('attr->tgl_ditetapkan', static function ($query, $order) {
                $query->orderBy('attr->tgl_ditetapkan', $order);
            })
            ->prepareQuery();

        $data['main']     = $query->results();
        $data['input']    = $post;
        $data['kat']      = $kat;
        $data['tahun']    = $post['tahun'];
        $data['desa']     = $this->header['desa'];
        $data['kategori'] = $kat == 1 ? 'Informasi Publik' : RefDokumen::get()[$kat];
        if ($kat == 2) {
            $data['file']     = 'Data SK Kepala Desa';
            $data['template'] = 'admin.layouts.components.kades.cetak.sk_kades_print';
        } elseif ($kat == 3) {
            $data['file']     = 'Data Peraturan ' . setting('sebutan_desa');
            $data['template'] = 'admin.layouts.components.kades.cetak.perdes_print';
        } else {
            $data['file']     = 'Laporan Dokumen';
            $data['template'] = 'admin.layouts.components.kades.cetak.dokumen_print';
        }

        return $data;
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int $id_dokumen Id berkas pada koloam dokumen.id
     * @param int $kat
     * @param int $tipe
     * @param int $popup
     */
    public function berkas($id_dokumen = 0, $kat = 1, $tipe = 0, $popup = 0): void
    {
        // Ambil nama berkas dari database
        $data = DokumenHidup::GetDokumen($id_dokumen);

        if ($data['url'] != null) {
            redirect($data['url']);
        }

        ambilBerkas($data['satuan'], $this->controller . '/peraturan_desa/' . $kat, null, LOKASI_DOKUMEN, $tipe == 1, $popup);
    }

    private function _set_tab($kat): void
    {
        $this->tab_ini = match ($kat) {
            '3'     => 60,
            default => 59,
        };
    }
}
