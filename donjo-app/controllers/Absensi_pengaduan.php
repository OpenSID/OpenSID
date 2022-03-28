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

use App\Models\AbsensiPengaduan;

defined('BASEPATH') || exit('No direct script access allowed');

class Absensi_pengaduan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 337;
        $this->sub_modul_ini = 342;
    }

    public function index()
    {
        return view('admin.pengaduan.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(AbsensiPengaduan::with(['pamong.penduduk', 'mandiri.penduduk']))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    if (can('u')) {
                        return '<a href="' . route('absensi_pengaduan.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }
                })
                ->rawColumns(['aksi'])
                ->editColumn('waktu', static function ($row) {
                    return tgl_indo2($row['waktu']);
                })
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');

        $action      = 'Ubah';
        $form_action = route('absensi_pengaduan.update', $id);
        // TODO: Gunakan findOrFail
        $absensi_pengaduan = AbsensiPengaduan::find($id) ?? show_404();

        return view('admin.pengaduan.form', compact('action', 'form_action', 'absensi_pengaduan'));
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');

        // TODO: Gunakan findOrFail
        $update = AbsensiPengaduan::find($id) ?? show_404();

        if ($update->update($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    private function validate($request = [])
    {
        return [
            'keterangan' => strip_tags($request['keterangan']),
        ];
    }
}
