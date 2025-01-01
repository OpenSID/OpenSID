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

use App\Models\SyaratSurat;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_mohon extends Admin_Controller
{
    public $modul_ini     = 'layanan-surat';
    public $sub_modul_ini = 'daftar-persyaratan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.syaratan_surat.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $query = SyaratSurat::formatSuratExist();

            return datatables()->of($query)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->ref_syarat_id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    if (can('u')) {
                        $aksi = '<a href="' . ci_route('surat_mohon.form', $row->ref_syarat_id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('u') && $row->jumlah_format_surat == '0') {
                        $aksi .= '<a href="#" data-href="' . ci_route('surat_mohon.delete', $row->ref_syarat_id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
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
            $form_action = ci_route('surat_mohon.update', $id);

            $ref_syarat_surat = SyaratSurat::findOrFail($id);
        } else {
            $action           = 'Tambah';
            $form_action      = ci_route('surat_mohon.insert');
            $ref_syarat_surat = null;
        }

        return view('admin.syaratan_surat.form', ['action' => $action, 'form_action' => $form_action, 'ref_syarat_surat' => $ref_syarat_surat]);
    }

    public function insert(): void
    {
        isCan('u');

        if (SyaratSurat::create(static::validate())) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $data = SyaratSurat::findOrFail($id);

        if ($data->update(static::validate())) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = ''): void
    {
        isCan('h');

        if (SyaratSurat::deleteFormatSuratExist($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll(): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            if (! SyaratSurat::deleteFormatSuratExist($id)) {
                redirect_with('error', 'Gagal Hapus Data');
            }
        }

        redirect_with('success', 'Berhasil Hapus Data');
    }

    protected function validate()
    {
        return $this->validated(request(), [
            'ref_syarat_nama' => 'required|min:3|max:255',
        ]);
    }
}
