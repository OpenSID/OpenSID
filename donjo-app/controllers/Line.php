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

use App\Enums\AktifEnum;
use App\Models\Line as LineModel;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Line extends Admin_Controller
{
    public $modul_ini     = 'pemetaan';
    public $sub_modul_ini = 'pengaturan-peta';
    private int $tip      = 2;
    private int $parent   = 1;
    private int $tipe     = 0;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data = ['tip' => $this->tip, 'tipe' => $this->input->get('tipe') ?? $this->tipe,  'parent' => $this->input->get('parent') ?? $this->parent, 'parent_jenis' => ''];
        if ($data['tipe'] == '2') {
            $data['parent_jenis'] = LineModel::find($data['parent'])->nama ?? '';
        }

        view('admin.peta.line.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status');
            $parent = $this->input->get('parent') ?? $this->parent;
            $tipe   = $this->input->get('tipe') ?? $this->tipe;

            return datatables()->of(LineModel::status($status)->whereParrent($parent)->whereTipe($tipe))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if ($row->tipe == LineModel::ROOT) {
                        $aksi .= View::make('admin.layouts.components.buttons.rincian', [
                            'url'   => ci_route('line.index') . '?parent=' . $row->id . '&tipe=' . LineModel::CHILD,
                            'judul' => 'Rincian ' . $row->nama,
                        ])->render();
                    }

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => 'line/form/' . implode('/', [$row->parrent, $row->id]),
                    ])->render();

                    if (can('u')) {
                        $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                            'url'    => site_url("line/lock/{$row->parrent}/{$row->id}"),
                            'active' => $row->enabled,
                        ])->render();
                    }

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route('line.delete', implode('/', [$row->parrent, $row->id])),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => '<span class="label label-' . ($row->enabled ? 'success' : 'danger') . '">' . AktifEnum::valueOf($row->enabled) . '</span>')
                ->editColumn('color', static fn ($row): string => '<hr style="vertical-align: middle; margin: 0; border-bottom: ' . $row->tebal . 'px ' . $row->jenis . '  ' . $row->color . '">')
                ->rawColumns(['aksi', 'ceklist', 'color', 'enabled'])
                ->make();
        }

        return show_404();
    }

    public function form($parent = 1, $id = '')
    {
        isCan('u');
        $this->parent = $parent;

        $data['aksi']        = 'Tambah';
        $data['line']        = null;
        $data['form_action'] = ci_route('line.insert', [$this->parent, $this->input->get('tipe')]);

        if ($id) {
            $data['aksi']        = 'Ubah';
            $data['line']        = LineModel::find($id)->toArray();
            $data['form_action'] = ci_route('line.update', implode('/', [$this->parent, $id]));
        }

        $data['tip'] = $this->tip;

        return view('admin.peta.line.form', $data);
    }

    public function insert(int $parent, $tipe): void
    {
        isCan('u');
        $dataInsert            = $this->validasi($this->input->post());
        $dataInsert['parrent'] = $parent;
        $dataInsert['tipe']    = $tipe;

        try {
            LineModel::create($dataInsert);
            redirect_with('success', __('notification.created.success'), ci_route('line.index') . '?parent=' . $parent . '&tipe=' . $tipe);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', __('notification.created.error'), ci_route('line.index') . '?parent=' . $parent . '&tipe=' . $tipe);
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');
        $dataUpdate            = $this->validasi($this->input->post());
        $dataUpdate['parrent'] = $parent;
        $tipe                  = $this->tipe($id);

        try {
            LineModel::where(['id' => $id, 'parrent' => $parent])->update($dataUpdate);
            redirect_with('success', __('notification.updated.success'), ci_route('line.index') . '?parent=' . $parent . '&tipe=' . $tipe);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', __('notification.updated.error'), ci_route('line.index') . '?parent=' . $parent . '&tipe=' . $tipe);
        }
    }

    public function delete($parent, $id = null): void
    {
        $tipe = $this->tipe($id);
        isCan('h');

        if ($this->hasChild($this->request['id_cb'] ?? $id)) {
            redirect_with('error', __('notification.deleted.error') . '. Silakan hapus subdata terlebih dahulu.', ci_route('line.index') . '?parent=' . $parent . '&tipe=' . $tipe);
        }

        try {
            LineModel::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', __('notification.deleted.success'), ci_route('line.index') . '?parent=' . $parent . '&tipe=' . $tipe);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', __('notification.deleted.error'), ci_route('line.index') . '?parent=' . $parent . '&tipe=' . $tipe);
        }
    }

    public function lock($parent, $id)
    {
        isCan('u');

        try {
            $status  = LineModel::gantiStatus($id, 'enabled');
            $success = (bool) $status;

            return json([
                'success' => $success,
                'message' => $success ? __('notification.status.success') : __('notification.status.error'),
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return json([
                'success' => false,
                'message' => __('notification.status.error'),
            ]);
        }
    }

    private function hasChild($id): bool
    {
        if (is_array($id)) {
            return LineModel::whereIn('parrent', $id)->exists();
        }

        return LineModel::where('parrent', $id)->exists();
    }

    private function validasi(array $post): array
    {
        return [
            'nama'    => nomor_surat_keputusan($post['nama']),
            'jenis'   => nomor_surat_keputusan($post['jenis']),
            'tebal'   => bilangan($post['tebal']),
            'color'   => warna($post['color']),
            'enabled' => $post['enabled'] ?? AktifEnum::TIDAK_AKTIF,
        ];
    }

    private function tipe($id): int
    {
        return LineModel::whereId($id)->doesntHave('parent')->exists() ? LineModel::ROOT : LineModel::CHILD;
    }
}
