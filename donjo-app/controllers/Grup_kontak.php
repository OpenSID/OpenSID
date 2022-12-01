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

use App\Models\AnggotaGrup;
use App\Models\DaftarKontak;
use App\Models\GrupKontak;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Grup_kontak extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini          = 10;
        $this->sub_modul_ini      = 40;
        $this->header['kategori'] = 'hubung warga';
    }

    public function index()
    {
        return view('admin.grup_kontak.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables(GrupKontak::withCount('anggota'))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id_grup . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('grup_kontak.form', $row->id_grup) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('grup_kontak.delete', $row->id_grup) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    $aksi .= '<a href="' . route('grup_kontak.anggota', $row->id_grup) . '" class="btn bg-purple btn-sm"  title="Data Anggota"><i class="fa fa fa-list"></i></a> ';

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $action     = 'Ubah';
            $formAction = route('grup_kontak.update', $id);
            $grupKontak = GrupKontak::find($id) ?? show_404();
        } else {
            $action     = 'Tambah';
            $formAction = route('grup_kontak.insert');
            $grupKontak = null;
        }

        return view('admin.grup_kontak.form', compact('action', 'formAction', 'grupKontak'));
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');

        if (GrupKontak::insert(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = GrupKontak::find($id) ?? show_404();

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null)
    {
        $this->redirect_hak_akses('h');

        if (GrupKontak::destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    // Hanya filter inputan
    protected static function validate($request = [])
    {
        return [
            'nama_grup'  => nama_terbatas($request['nama_grup']),
            'keterangan' => htmlentities($request['keterangan']),
        ];
    }

    // Anggota Grup
    public function anggota($id = null)
    {
        $grupKontak = GrupKontak::find($id) ?? show_404();

        return view('admin.grup_kontak.anggota.index', compact('grupKontak'));
    }

    public function anggotaDatatables($id_grup = null)
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(AnggotaGrup::where('id_grup', $id_grup)->dataAnggota())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id_grup_kontak . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    if (can('h')) {
                        return '<a href="#" data-href="' . route('grup_kontak.anggotadelete', $row->id_grup_kontak) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }
                })
                ->addColumn('kontak', static function ($row) {
                    return (null === $row->id_kontak) ? '<span class="label label-success">Penduduk</span>' : '<span class="label label-info">Eksternal</span>';
                })
                ->rawColumns(['ceklist', 'aksi', 'kontak'])
                ->make();
        }

        return show_404();
    }

    public function anggotaForm($id_grup = null)
    {
        $this->redirect_hak_akses('u');

        $action     = 'Tambah';
        $formAction = route('grup_kontak.anggotainsert');
        $grupKontak = GrupKontak::find($id_grup) ?? show_404();

        return view('admin.grup_kontak.anggota.form', compact('action', 'formAction', 'grupKontak'));
    }

    public function anggotaInsert()
    {
        $this->redirect_hak_akses('u');

        if (AnggotaGrup::insert(static::anggotaValidate($this->request))) {
            set_session('success', 'Berhasil Tambah Data');
        } else {
            set_session('error', 'Gagal Tambah Data');
        }

        redirect("grup_kontak/anggota/{$this->request['id_grup']}");
    }

    public function anggotaDelete($id = null)
    {
        $this->redirect_hak_akses('h');

        if (AnggotaGrup::destroy($this->request['id_cb'] ?? $id)) {
            set_session('success', 'Berhasil Hapus Data');
        } else {
            set_session('error', 'Gagal Hapus Data');
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    // Hanya filter inputan
    protected static function anggotaValidate($request = [])
    {
        $penduduk = [];
        if ($request['id_penduduk']) {
            foreach ($request['id_penduduk'] as $key => $value) {
                $penduduk[$key]['id_grup']     = bilangan($request['id_grup']);
                $penduduk[$key]['id_kontak']   = null;
                $penduduk[$key]['id_penduduk'] = bilangan($value);
            }
        }

        $kontak = [];
        if ($request['id_kontak']) {
            foreach ($request['id_kontak'] as $key => $value) {
                $kontak[$key]['id_grup']     = bilangan($request['id_grup']);
                $kontak[$key]['id_kontak']   = bilangan($value);
                $kontak[$key]['id_penduduk'] = null;
            }
        }

        return array_merge($penduduk, $kontak);
    }

    public function penduduk($id_grup = null)
    {
        if ($this->input->is_ajax_request()) {
            $id_penduduk = AnggotaGrup::where('id_grup', $id_grup)
                ->whereNotNull('id_penduduk')
                ->pluck('id_penduduk');

            $datatables = Penduduk::select(['id', 'nama', 'telepon', 'email', 'telegram', 'hubung_warga'])
                ->whereNotIn('id', $id_penduduk)
                ->where(static function ($query) {
                    return $query->whereNotNull('telepon')
                        ->orWhereNotNull('email')
                        ->orWhereNotNull('telegram');
                })
                ->status();

            return datatables($datatables)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_penduduk[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['ceklist'])
                ->make();
        }

        return show_404();
    }

    public function kontak($id_grup = null)
    {
        if ($this->input->is_ajax_request()) {
            $id_kontak = AnggotaGrup::where('id_grup', $id_grup)
                ->whereNotNull('id_kontak')
                ->pluck('id_kontak');

            $datatables = DaftarKontak::where(static function ($query) {
                return $query->whereNotNull('telepon')
                    ->orWhereNotNull('email')
                    ->orWhereNotNull('telegram');
            })
                ->whereNotIn('id_kontak', $id_kontak);

            return datatables($datatables)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_kontak[]" value="' . $row->id_kontak . '"/>';
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['ceklist'])
                ->make();
        }

        return show_404();
    }
}
