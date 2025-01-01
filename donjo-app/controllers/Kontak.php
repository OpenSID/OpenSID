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

use App\Models\DaftarKontak;

// TODO:: Hapus bagian ini karena tidak digunakan, menggunakan controller DaftarKontak
class Kontak extends Admin_Controller
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
        return view('admin.kontak.index');
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
                        $aksi .= '<a href="' . ci_route('kontak.form', $row->id_kontak) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('kontak.delete', $row->id_kontak) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = ci_route('kontak.update', $id);
            $kontak      = DaftarKontak::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('kontak.insert');
            $kontak      = null;
        }

        return view('admin.kontak.form', ['action' => $action, 'form_action' => $form_action, 'kontak' => $kontak]);
    }

    public function insert(): void
    {
        isCan('u');

        if (DaftarKontak::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $data = DaftarKontak::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = ''): void
    {
        isCan('h');

        if (DaftarKontak::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll(): void
    {
        isCan('h');

        if (DaftarKontak::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    // Hanya filter inputan
    protected static function validate($request = []): array
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
