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

use App\Models\FormatSurat;
use App\Models\LampiranSurat;

defined('BASEPATH') || exit('No direct script access allowed');

class Lampiran extends Admin_Controller
{
    public $modul_ini           = 'layanan-surat';
    public $sub_modul_ini       = 'lampiran';
    public $kategori_pengaturan = 'pengaturan-surat';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            return datatables(LampiranSurat::jenis($this->input->get('jenis')))
                ->addColumn('ceklist', static fn ($row): string => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>')
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("lampiran/form/{$row->id}") . '" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h') && ($row->jenis == LampiranSurat::LAMPIRAN_DESA)) {
                        $aksi .= '<a href="#" data-href="' . site_url("lampiran/delete/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('jenis', static fn ($row) => SebutanDesa(LampiranSurat::JENIS_LAMPIRAN[$row->jenis]) ?? '')
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return view('admin.pengaturan_surat.lampiran.index', [
            'jenis' => LampiranSurat::JENIS_LAMPIRAN,
        ]);
    }

    public function form($id = null)
    {
        isCan('u');
        $this->set_hak_akses_rfm();

        $lampiran = $id ? LampiranSurat::findOrFail($id) : null;
        $margin   = LampiranSurat::MARGINS;
        if ($lampiran && ! empty($lampiran->margin)) {
            $margin = json_decode($lampiran->margin, null);
        }

        $data['action']               = $id ? 'Ubah' : 'Tambah';
        $data['formAction']           = $id ? ci_route('lampiran.update', $id) : ci_route('lampiran.insert');
        $data['lampiranSurat']        = $lampiran;
        $data['margins']              = $margin;
        $data['margin_global']        = $lampiran->margin_global ?? 1;
        $data['orientations']         = FormatSurat::ORIENTATAIONS;
        $data['sizes']                = FormatSurat::SIZES;
        $data['default_orientations'] = FormatSurat::DEFAULT_ORIENTATAIONS;
        $data['default_sizes']        = FormatSurat::DEFAULT_SIZES;

        return view('admin.pengaturan_surat.lampiran.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (LampiranSurat::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'lampiran');
        }

        redirect_with('error', 'Gagal Tambah Data', 'lampiran');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = LampiranSurat::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'lampiran');
        }

        redirect_with('error', 'Gagal Ubah Data', 'lampiran');
    }

    public function delete($id = null): void
    {
        isCan('h');
        if (! is_array($id)) {
            $id = [$id];
        }
        if (LampiranSurat::jenis(LampiranSurat::LAMPIRAN_DESA)->whereIn('id', $id)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data', 'lampiran');
        }

        redirect_with('error', 'Gagal Hapus Data', 'lampiran');
    }

    public function delete_all()
    {
        return $this->delete($this->input->post('id_cb'));
    }

    private function validate($request = [])
    {
        return [
            'config_id'     => identitas('id'),
            'nama'          => $nama = judul($request['nama']),
            'slug'          => url_title($nama, '-', true),
            'jenis'         => LampiranSurat::LAMPIRAN_DESA,
            'template_desa' => $request['template_desa'],
            'status'        => (int) $request['status'],
            'margin_global' => $request['margin_global'],
            'margin'        => json_encode($request['margin'], JSON_THROW_ON_ERROR),
            'ukuran'        => $request['ukuran'],
            'orientasi'     => $request['orientasi'],
        ];
    }

    public function impor()
    {
        isCan('u');

        $this->load->library('upload');

        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'json';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = 'template_lampiran.json';

        $this->upload->initialize($config);

        if ($this->upload->do_upload('userfile')) {
            $list_data = $this->formatImport(file_get_contents($this->upload->data()['full_path']));
            if ($list_data) {
                return view('admin.pengaturan_surat.lampiran.impor_select', ['data' => $list_data]);
            }
        }

        redirect_with('error', 'Gagal Impor Data<br/>' . $this->upload->display_errors());
    }

    private function formatImport($list_data = null)
    {
        return collect(json_decode($list_data, true))
            ->map(static fn ($item): array => [
                'slug'          => $item['slug'],
                'nama'          => $item['nama'],
                'jenis'         => (int) $item['jenis'],
                'template'      => $item['template'],
                'template_desa' => $item['template_desa'],
                'status'        => (int) $item['status'],
                'created_by'    => auth()->id,
                'updated_by'    => auth()->id,
            ])
            ->toArray();
    }

    public function impor_store(): void
    {
        isCan('u');

        $data = $this->request['id_cb'];

        if (null === $data) {
            redirect_with('error', 'Tidak ada surat yang dipilih.');
        }

        $this->prosesImport($data);

        redirect_with('success', 'Berhasil Impor Data');
    }

    private function prosesImport($list_data = null)
    {
        if ($list_data) {
            foreach ($list_data as $item) {
                $value = json_decode($item, true);
                LampiranSurat::updateOrCreate(['config_id' => identitas('id'), 'slug' => $value['slug']], $value);
            }

            return true;
        }

        return false;
    }

    public function ekspor(): void
    {
        isCan('u');

        $id = $this->request['id_cb'];

        if (null === $id) {
            redirect_with('error', 'Tidak ada lampiran yang dipilih.');
        }

        $ekspor = LampiranSurat::whereIn('id', $id)->get();

        if ($ekspor->count() === 0) {
            redirect_with('error', 'Tidak ada lampiran yang ditemukan dari pilihan anda.');
        }

        $file_name = namafile('Template Lampiran') . '.json';
        $ekspor    = $ekspor->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at')->toArray())->toArray();

        $this->output
            ->set_header("Content-Disposition: attachment; filename={$file_name}")
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($ekspor, JSON_PRETTY_PRINT));
    }
}
