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

use App\Models\AnggotaGrup;
use App\Models\DaftarKontak;
use App\Models\GrupKontak;
use App\Models\Penduduk;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Grup_kontak extends Admin_Controller
{
    public $modul_ini           = 'hubung-warga';
    public $sub_modul_ini       = 'daftar-kontak';
    public $kategori_pengaturan = 'hubung warga';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
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
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('grup_kontak.form', $row->id_grup) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('grup_kontak.delete', $row->id_grup) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi . ('<a href="' . ci_route('grup_kontak.anggota', $row->id_grup) . '" class="btn bg-purple btn-sm"  title="Data Anggota"><i class="fa fa fa-list"></i></a> ');
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $action     = 'Ubah';
            $formAction = ci_route('grup_kontak.update', $id);
            $grupKontak = GrupKontak::findOrFail($id);
        } else {
            $action     = 'Tambah';
            $formAction = ci_route('grup_kontak.insert');
            $grupKontak = null;
        }

        return view('admin.grup_kontak.form', ['action' => $action, 'formAction' => $formAction, 'grupKontak' => $grupKontak]);
    }

    public function insert(): void
    {
        isCan('u');

        if (GrupKontak::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = GrupKontak::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

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
            'keterangan' => htmlentities((string) $request['keterangan']),
        ];
    }

    // Anggota Grup
    public function anggota($id = null)
    {
        $grupKontak = GrupKontak::findOrFail($id);

        return view('admin.grup_kontak.anggota.index', ['grupKontak' => $grupKontak]);
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
                        return '<a href="#" data-href="' . ci_route('grup_kontak.anggotadelete', $row->id_grup_kontak) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }
                })
                ->addColumn('kontak', static fn ($row): string => (null === $row->id_kontak) ? '<span class="label label-success">Penduduk</span>' : '<span class="label label-info">Eksternal</span>')
                ->rawColumns(['ceklist', 'aksi', 'kontak'])
                ->make();
        }

        return show_404();
    }

    public function anggotaForm($id_grup = null)
    {
        isCan('u');

        $action     = 'Tambah';
        $formAction = ci_route('grup_kontak.anggotainsert');
        $grupKontak = GrupKontak::find($id_grup) ?? show_404();

        return view('admin.grup_kontak.anggota.form', ['action' => $action, 'formAction' => $formAction, 'grupKontak' => $grupKontak]);
    }

    public function anggotaInsert(): void
    {
        isCan('u');

        if (AnggotaGrup::insert(static::anggotaValidate($this->request))) {
            set_session('success', 'Berhasil Tambah Data');
        } else {
            set_session('error', 'Gagal Tambah Data');
        }

        redirect("grup_kontak/anggota/{$this->request['id_grup']}");
    }

    public function anggotaDelete($id = null): void
    {
        isCan('h');

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
                $penduduk[$key]['config_id']   = identitas('id');
                $penduduk[$key]['id_grup']     = (int) bilangan($request['id_grup']);
                $penduduk[$key]['id_kontak']   = null;
                $penduduk[$key]['id_penduduk'] = (int) bilangan($value);
                $penduduk[$key]['created_at']  = Carbon::now();
                $penduduk[$key]['updated_at']  = Carbon::now();
            }
        }

        $kontak = [];
        if ($request['id_kontak']) {
            foreach ($request['id_kontak'] as $key => $value) {
                $kontak[$key]['config_id']   = identitas('id');
                $kontak[$key]['id_grup']     = (int) bilangan($request['id_grup']);
                $kontak[$key]['id_kontak']   = (int) bilangan($value);
                $kontak[$key]['id_penduduk'] = null;
                $kontak[$key]['created_at']  = Carbon::now();
                $kontak[$key]['updated_at']  = Carbon::now();
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
                ->where(static fn ($query) => $query->whereNotNull('telepon')
                    ->orWhereNotNull('email')
                    ->orWhereNotNull('telegram'))
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

            $datatables = DaftarKontak::where(static fn ($query) => $query->whereNotNull('telepon')
                ->orWhereNotNull('email')
                ->orWhereNotNull('telegram'))
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
