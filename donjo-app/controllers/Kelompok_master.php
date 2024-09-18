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

use App\Models\KelompokMaster;

defined('BASEPATH') || exit('No direct script access allowed');

class Kelompok_master extends Admin_Controller
{
    public $modul_ini     = 'kependudukan';
    public $sub_modul_ini = 'kelompok';
    protected $tipe       = 'kelompok';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function clear(): void
    {
        redirect($this->controller);
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $controller = $this->controller;

            return datatables(KelompokMaster::tipe($this->tipe)->withCount('kelompok as jumlah'))
                ->addIndexColumn()
                ->addColumn('ceklist', static fn ($row): string => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>')
                ->addColumn('aksi', static function ($row) use ($controller): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("{$controller}/form/{$row->id}") . '" class="btn bg-orange btn-sm" title="Ubah Kategori"><i class="fa fa-edit"></i></a> ';
                    }
                    if (can('h') && $row->jumlah == 0) {
                        $aksi .= '<a href="#" data-href="' . site_url("{$controller}/delete/{$row->id}") . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return view('admin.kelompok_master.index');
    }

    public function form($id = 0)
    {
        isCan('u');
        if ($id) {
            $data['kelompok_master'] = KelompokMaster::tipe($this->tipe)->find($id) ?? show_404();
            $data['form_action']     = site_url("{$this->controller}/update/{$id}");
            $data['action']          = 'Ubah';
        } else {
            $data['kelompok_master'] = null;
            $data['form_action']     = site_url("{$this->controller}/insert");
            $data['action']          = 'Tambah';
        }

        return view('admin.kelompok_master.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        (new KelompokMaster($this->validate($this->input->post())))->save();

        redirect_with('success', 'Berhasil menambah data');
    }

    public function update($id = 0): void
    {
        isCan('u');

        KelompokMaster::findOrFail($id)->update($this->validate($this->input->post()));

        redirect_with('success', 'Berhasil mengubah data');
    }

    public function delete($id = 0): void
    {
        isCan('h');

        $this->delete_kelompok($id);

        redirect_with('success', 'Berhasil Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete_kelompok($id);
        }

        redirect_with('success', 'Berhasil Hapus Data');
    }

    protected function delete_kelompok($id = '')
    {
        $result = KelompokMaster::tipe($this->tipe)
            ->doesntHave('kelompok')
            ->find($id);

        if (! $result) {
            redirect_with('error', "Tidak dapat ditemukan / Tidak dapat dihapus karena masih terdapat data {$this->tipe}");
        }

        $result->delete();
    }

    protected function validate($request = []): array
    {
        return [
            'config_id' => identitas('id'),
            'kelompok'  => nama_terbatas($request['kelompok']),
            'deskripsi' => htmlentities($request['deskripsi']),
            'tipe'      => $this->tipe,
        ];
    }
}
