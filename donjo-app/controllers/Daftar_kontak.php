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

use App\Models\DaftarKontak;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Daftar_kontak extends Admin_Controller
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
        return view('admin.daftar_kontak.index', [
            'navigasi' => 'Eksternal',
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(DaftarKontak::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id_kontak . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('daftar_kontak.form', $row->id_kontak) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('daftar_kontak.delete', $row->id_kontak) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function penduduk()
    {
        return view('admin.daftar_kontak.penduduk', [
            'navigasi' => 'Penduduk',
        ]);
    }

    public function datatablesPenduduk()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(Penduduk::hubungWarga())
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    if (can('u')) {
                        return '<a href="' . ci_route('daftar_kontak.form_penduduk', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');

        $navigasi = 'Eksternal';

        if ($id) {
            $action       = 'Ubah';
            $formAction   = ci_route('daftar_kontak.update', $id);
            $daftarKontak = DaftarKontak::findOrFail($id);
        } else {
            $action       = 'Tambah';
            $formAction   = ci_route('daftar_kontak.insert');
            $daftarKontak = null;
        }

        return view('admin.daftar_kontak.form', ['navigasi' => $navigasi, 'action' => $action, 'formAction' => $formAction, 'daftarKontak' => $daftarKontak]);
    }

    public function form_penduduk($id = null)
    {
        isCan('u');

        $navigasi     = 'Penduduk';
        $action       = 'Ubah';
        $formAction   = ci_route('daftar_kontak.update_penduduk', $id);
        $daftarKontak = Penduduk::findOrFail($id);

        return view('admin.daftar_kontak.form', ['navigasi' => $navigasi, 'action' => $action, 'formAction' => $formAction, 'daftarKontak' => $daftarKontak]);
    }

    public function insert(): void
    {
        isCan('u');

        if (DaftarKontak::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = DaftarKontak::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function update_penduduk($id = null): void
    {
        isCan('u');

        $data = Penduduk::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'daftar_kontak/penduduk');
        }
        redirect_with('error', 'Gagal Ubah Data', 'daftar_kontak/penduduk');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (DaftarKontak::destroy($this->request['id_cb'] ?? $id) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    // Hanya filter inputan
    protected static function validate(array $request = []): array
    {
        return [
            'nama'         => nama_terbatas($request['nama']),
            'hubung_warga' => htmlentities((string) $request['hubung_warga']),
            'telepon'      => bilangan($request['telepon']),
            'email'        => htmlentities((string) $request['email']),
            'telegram'     => bilangan($request['telegram']),
            'keterangan'   => htmlentities((string) $request['keterangan']),
        ];
    }
}
