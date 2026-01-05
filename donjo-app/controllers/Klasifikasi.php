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

use App\Exports\KlasifikasiSuratExport;
use App\Imports\KlasifikasiSuratImports;
use App\Models\KlasifikasiSurat;
use Illuminate\Support\Facades\View;

class Klasifikasi extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'klasifikasi-surat';

    protected static function validate($data): array
    {
        return [
            'kode'   => alfanumerik_titik($data['kode']),
            'nama'   => alfa_spasi($data['nama']),
            'uraian' => strip_tags($data['uraian']),
        ];
    }

    public function index()
    {
        $data = [
            'modul_ini'     => $this->modul_ini,
            'sub_modul_ini' => $this->sub_modul_ini,
        ];

        return view('admin.klasifikasi.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {

            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                        $aksi .= View::make('admin.layouts.components.buttons.edit', [
                            'url' => 'klasifikasi/form/' . $row->id,
                        ])->render();

                    $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                        'url'    => ci_route('klasifikasi/lock', $row->id),
                        'active' => $row->enabled,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route('klasifikasi.delete', $row->id),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->addColumn('checkbox', static function ($row): string {
                    $checkbox = '';
                    if (can('u')) {
                        $checkbox .= '<input type="checkbox" name="id_cb[]" value="' . $row->id . '" />';
                    }

                    return $checkbox;
                })
                ->rawColumns(['aksi', 'checkbox'])
                ->make();
        }
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $data['data']        = KlasifikasiSurat::where('id', (int) $id)->first();
            $data['form_action'] = ci_route('klasifikasi.update', $id);
        } else {
            $data['data']        = null;
            $data['form_action'] = ci_route('klasifikasi.insert', $id);
        }

        return view('admin.klasifikasi.form', $data);
    }

    public function insert(): void
    {
        isCan('u');
        $data = static::validate($this->request);

        try {
            KlasifikasiSurat::create($data);
            session_success();
        } catch (Exception $e) {
            log_message('error', $e);
            redirect_with('error', $e->getMessage());
        }

        redirect_with('success', 'Klasifikasi surat berhasil ditambahkan');
    }

    public function update($id = ''): void
    {
        isCan('u');
        $data = static::validate($this->request);

        try {
            KlasifikasiSurat::where('id', (int) $id)->update($data);
            session_success();
        } catch (Exception $e) {
            log_message('error', $e);
            redirect_with('error', $e->getMessage());
        }

        redirect_with('success', 'Klasifikasi surat berhasil diperbarui');
    }

    public function delete($id = ''): void
    {
        isCan('h');
        KlasifikasiSurat::where('id', (int) $id)->delete();
        redirect_with('success', 'Klasifikasi surat berhasil dihapus');
    }

    public function delete_all(): void
    {
        isCan('h');
        KlasifikasiSurat::whereIn('id', $this->request['id_cb'])->delete();

        redirect_with('success', 'Klasifikasi surat berhasil dihapus');
    }

    public function lock($id = ''): void
    {
        isCan('u');
        KlasifikasiSurat::gantiStatus($id, 'enabled');
        redirect_with('success', 'Klasifikasi surat berhasil dinonaktifkan');
    }

    public function unlock($id = ''): void
    {
        isCan('u');
        KlasifikasiSurat::where('id', (int) $id)->update(['enabled' => 1]);
        redirect_with('success', 'Klasifikasi surat berhasil diaktifkan');
    }

    public function ekspor()
    {
        return (new KlasifikasiSuratExport())->download();
    }

    public function impor()
    {
        isCan('u');
        $data['form_action']       = ci_route('klasifikasi.proses_impor');
        $data['format_impor']      = ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-impor-klasifikasi-surat.xlsx'));
        $data['klasifikasi_surat'] = ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'klasifikasi-surat.xlsx'));

        return view('admin.klasifikasi.import', $data);
    }

    public function proses_impor(): void
    {
        isCan('u');

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'xls|xlsx|xlsm',
            'file_name'     => namafile('Impor Klasifikasi Surat'),
        ]);

        if ($this->upload->do_upload('klasifikasi')) {
            $upload = $this->upload->data();

            $result = (new KlasifikasiSuratImports($upload['full_path']))->import();
            if (! $result) {
                redirect_with('error', 'Klasifikasi surat gagal diimpor');
            }
        }

        redirect_with('success', 'Klasifikasi surat berhasil diimpor');
    }

    public function cetak()
    {
        $paramDatatable = json_decode($this->input->post('params'), 1);
        $_GET           = $paramDatatable;
        $query          = $this->sumberData();
        if ($paramDatatable['start']) {
            $query->skip($paramDatatable['start']);
        }

        $data         = $this->modal_penandatangan();
        $data['aksi'] = 'cetak';
        $data['main'] = $query->take($paramDatatable['length'])->get();

        $data['tgl_cetak']   = $this->input->post('tgl_cetak');
        $data['privasi_nik'] = $this->input->post('privasi_nik') ?? null;
        $data['file']        = 'Klasifikasi Surat';
        $data['isi']         = 'admin.klasifikasi.cetak';
        $data['letak_ttd']   = ['2', '2', '9'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    private function sumberData()
    {
        $enable = $this->input->get('enable');

        return KlasifikasiSurat::filter($enable);
    }
}
