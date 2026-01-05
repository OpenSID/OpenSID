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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

require_once FCPATH . 'Modules/Anjungan/Http/Controllers/BackEnd/AnjunganBaseController.php';

use App\Enums\StatusEnum;
use App\Models\Artikel;
use App\Models\Bantuan;
use App\Models\Kategori;
use App\Models\Kelompok;
use App\Models\Suplemen;
use App\Traits\Upload;
use Modules\Anjungan\Models\AnjunganMenu as Menu;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class AnjunganMenuController extends AnjunganBaseController
{
    use Upload;

    public $moduleName      = 'Anjungan';
    public $modul_ini       = 'anjungan';
    public $sub_modul_ini   = 'anjungan-menu';
    public $aliasController = 'anjungan_menu';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('anjungan::backend.menu.index');
    }

    public function datatables()
    {
        if (request()->ajax()) {
            $order = $this->input->get('order') ?? false;

            return datatables()->of(Menu::when(! $order, static fn ($q) => $q->orderBy('urut')))
                ->addColumn('drag-handle', static fn (): string => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('anjungan_menu.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->status == StatusEnum::YA) {
                            $aksi .= '<a href="' . ci_route('anjungan_menu.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('anjungan_menu.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('anjungan_menu.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['drag-handle', 'ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');
        $tipe_link = unserialize(LINK_TIPE);

        $data['link_tipe']                  = $tipe_link;
        $data['artikel_statis']             = Artikel::statis()->get();
        $data['kategori_artikel']           = Kategori::where('enabled', 1)->get();
        $data['statistik_penduduk']         = unserialize(STAT_PENDUDUK);
        $data['statistik_keluarga']         = unserialize(STAT_KELUARGA);
        $data['statistik_kategori_bantuan'] = unserialize(STAT_BANTUAN);
        $data['statistik_program_bantuan']  = Bantuan::get();
        $data['kelompok']                   = Kelompok::tipe('kelompok')->get();
        $data['lembaga']                    = Kelompok::tipe('lembaga')->get();
        $data['suplemen']                   = Suplemen::get();
        $data['statis_lainnya']             = unserialize(STAT_LAINNYA);
        $data['artikel_keuangan']           = Artikel::keuangan()->get();

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = ci_route('anjungan_menu.update', $id);
            $data['menu']        = Menu::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('anjungan_menu.insert');
            $data['menu']        = null;
        }

        return view('anjungan::backend.menu.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (Menu::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = Menu::findOrFail($id);

        if ($data->update(static::validate($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (Menu::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function lock($id = 0): void
    {
        isCan('u');

        if (Menu::gantiStatus($id, 'status')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    public function tukar()
    {
        isCan('u');

        $menu = $this->input->post('data');
        Menu::setNewOrder($menu);

        return json(['status' => 1]);
    }

    protected function validate(array $request = [], $id = null): array
    {
        $urut = $id ? Menu::find($id)->urut : Menu::max('urut') + 1;

        $data = [
            'nama'      => htmlentities($request['nama']),
            'link'      => $request['link'],
            'link_tipe' => $request['link_tipe'],
            'urut'      => $urut,
            'status'    => 1,
        ];

        if ($this->request['icon']) {
            $data['icon'] = $this->upload(
                file: 'icon',
                config: [
                    'upload_path'   => LOKASI_ICON_MENU_ANJUNGAN,
                    'allowed_types' => 'gif|jpg|jpeg|png|webp',
                    'overwrite'     => true,
                    'max_size'      => max_upload() * 1024,
                ],
                callback: static function ($uploadData) {
                    $extension = strtolower(pathinfo($uploadData['full_path'], PATHINFO_EXTENSION));
                    $webpName  = $uploadData['raw_name'];

                    if ($extension === 'gif') {
                        return "{$webpName}.gif";
                    }
                    Image::load($uploadData['full_path'])
                        ->format(Manipulations::FORMAT_WEBP)
                        ->width(100)
                        ->height(100)
                        ->save("{$uploadData['file_path']}{$webpName}.webp");

                    unlink($uploadData['full_path']);

                    return $webpName . '.webp';
                }
            );
        }

        return $data;
    }
}
