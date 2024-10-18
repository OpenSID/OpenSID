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

use App\Models\Penduduk;
use App\Models\TanahDesa;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_tanah_desa extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-umum';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['selected_nav'] = 'tanah';
        $data['subtitle']     = 'Buku Tanah di ' . ucwords(setting('sebutan_desa'));
        $data['main_content'] = 'admin.bumindes.pembangunan.tanah_di_desa.index';

        return view('admin.bumindes.umum.main', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    $aksi .= '<a href="' . ci_route('bumindes_tanah_desa.form') . '/' . $row->id . '/' . 1 . '" class="btn btn-info btn-sm"  title="Lihat Data"><i class="fa fa-eye"></i></a> ';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('bumindes_tanah_desa.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('bumindes_tanah_desa.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->filterColumn('nama_pemilik_asal', static function ($query, $keyword): void {
                    $query->whereRaw('nama_pemilik_asal like ?', ["%{$keyword}%"])
                        ->orwhereHas('penduduk', static fn ($q) => $q->whereRaw('nama like ?', ["%{$keyword}%"]));
                })
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        return TanahDesa::with('penduduk')->visible();
    }

    public function form($id = '', $view = false)
    {
        isCan('u');

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = ci_route('bumindes_tanah_desa.update', $id);
            $data['main']        = TanahDesa::findOrFail($id);
            $data['view_mark']   = $view ? 1 : 0;
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('bumindes_tanah_desa.create');
            $data['main']        = null;
            $data['view_mark']   = null;
        }

        $data['penduduk'] = Penduduk::get();

        return view('admin.bumindes.pembangunan.tanah_di_desa.form', $data);
    }

    public function create(): void
    {
        isCan('u');

        if (TanahDesa::create($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $update = TanahDesa::findOrFail($id);

        $data = $this->validate($this->request, $id);

        if ($update->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');

        if (TanahDesa::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    private function validate($data, $id = 0)
    {
        if (preg_match("/[^a-zA-Z '\\.,\\-]/", $data['pemilik_asal'])) {
            redirect_with('error', 'Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip');
        }
        if (empty($data['penduduk'])) {
            $this->periksa_nik($data, $id);
        }

        //  steril data
        $data['id_penduduk']          = empty($data['penduduk']) ? null : $data['penduduk'];
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

        return $data;
    }

    private function periksa_nik($data, $id): void
    {
        if (empty($data['penduduk']) && ! isset($data['nik'])) {
            redirect_with('error', 'NIK Kosong');
        }

        if ($error_nik = $this->nik_error($data['nik'], 'NIK')) {
            redirect_with('error', $error_nik);
        }

        // NIK 0 (yaitu NIK tidak diketahui) boleh duplikat
        if ($data['nik'] == 0) {
            redirect_with('error', 'NIK tidak boleh 0');
        }
        // cek nik penduduk luar tidak boleh sama dengan penduduk desa
        if ($id != 0) {
            return;
        }
        if (! Penduduk::whereNik($data['nik'])->exists()) {
            return;
        }
        redirect_with('error', "NIK {$data['nik']} sudah digunakan");
    }

    private function nik_error($nilai, string $judul)
    {
        if (empty($nilai)) {
            return false;
        }
        if (! ctype_digit($nilai)) {
            return $judul . ' hanya berisi angka';
        }
        if (strlen($nilai) != 16) {
            return $judul . ' panjangnya harus 16 digit';
        }
        if ($nilai == '0') {
            return false;
        }
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('bumindes_tanah_desa.cetak', $aksi);

        return view('admin.bumindes.pembangunan.tanah_di_desa.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $query             = $this->sumberData();
        $data              = $this->modal_penandatangan();
        $data['aksi']      = $aksi;
        $data['main']      = $query->get();
        $data['config']    = $this->header['desa'];
        $data['bulan']     = date('m');
        $data['tahun']     = date('Y');
        $data['tgl_cetak'] = $this->input->post('tgl_cetak');
        $data['isi']       = 'admin.bumindes.pembangunan.tanah_di_desa.cetak';
        $data['letak_ttd'] = ['1', '1', '23'];

        return view('admin.layouts.components.format_cetak', $data);
    }
}
