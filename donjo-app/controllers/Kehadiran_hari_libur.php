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

use App\Models\HariLibur;

defined('BASEPATH') || exit('No direct script access allowed');

class Kehadiran_hari_libur extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini          = 337;
        $this->sub_modul_ini      = 340;
        $this->header['kategori'] = 'kehadiran';
    }

    public function index()
    {
        return view('admin.hari_libur.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(HariLibur::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('kehadiran_hari_libur.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('kehadiran_hari_libur.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('tanggal', static function ($row) {
                    return tgl_indo($row->tanggal);
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = route('kehadiran_hari_libur.update', $id);
            // TODO: Gunakan findOrFail
            $kehadiran_hari_libur          = HariLibur::find($id) ?? show_404();
            $kehadiran_hari_libur->tanggal = date('d-m-Y', strtotime($kehadiran_hari_libur->tanggal));
        } else {
            $action               = 'Tambah';
            $form_action          = route('kehadiran_hari_libur.create');
            $kehadiran_hari_libur = null;
        }

        return view('admin.hari_libur.form', compact('action', 'form_action', 'kehadiran_hari_libur'));
    }

    public function create()
    {
        $this->redirect_hak_akses('u');

        if (HariLibur::insert($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');

        // TODO: Gunakan findOrFail
        $update = HariLibur::find($id) ?? show_404();

        if ($update->update($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null)
    {
        $this->redirect_hak_akses('h');

        if (HariLibur::destroy($id ?? $this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    private function validate($request = [])
    {
        if (HariLibur::where('tanggal', date('Y-m-d', strtotime($request['tanggal'])))->exists()) {
            redirect_with('error', 'Tanggal terkait sudah ditambahkan pada hari libur');
        }

        return [
            'tanggal'    => date('Y-m-d', strtotime($request['tanggal'])),
            'keterangan' => strip_tags($request['keterangan']),
        ];
    }

    public function import()
    {
        $this->redirect_hak_akses('u');

        $kalender = file_get_contents('https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json');
        $tanggal  = json_decode($kalender, true);

        $batch = collect($tanggal)->map(static function ($item, $key) {
            return [
                'tanggal'    => date_format(date_create($key), 'Y-m-d'),
                'keterangan' => $item['deskripsi'],
            ];
        })->filter(static function ($value, $key) {
            return $value['tanggal'] > date('Y') . '-01-01';
        })->slice(0, -2);

        HariLibur::upsert($batch->values()->toArray(), ['tanggal'], ['keterangan']);

        redirect_with('success', 'Berhasil Tambah Data');
    }
}
