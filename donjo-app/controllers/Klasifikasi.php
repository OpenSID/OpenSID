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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Exports\KlasifikasiSuratExport;
use App\Imports\KlasifikasiSuratImports;
use App\Models\KlasifikasiSurat;

class Klasifikasi extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'klasifikasi-surat';

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
            $enable = $this->input->get('enable');

            return datatables()->of(KlasifikasiSurat::filter($enable))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('klasifikasi.form', $row->id) . '" class="btn btn-warning btn-sm" title="Ubah" style="margin-right:4px;"><i class="fa fa-edit"></i></a>';
                        if ($row->enabled == '1') {
                            $aksi .= '<a href="' . ci_route('klasifikasi/lock', $row->id) . '" class="btn bg-navy btn-sm" title="Non Aktifkan" style="margin-right:4px;"><i class="fa fa-unlock">&nbsp;</i></a>';
                        } else {
                            $aksi .= '<a href="' . ci_route('klasifikasi/unlock', $row->id) . '" class="btn bg-navy btn-sm" title="Aktifkan" style="margin-right:4px;"><i class="fa fa-lock"></i></a>';
                        }

                        if (can('h')) {
                            $aksi .= '<a href="#" data-href="' . ci_route('klasifikasi/delete', $row->id) . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                        }
                    }

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
        $data = static::validated($this->request);

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
        $data = static::validated($this->request);

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
        KlasifikasiSurat::where('id', (int) $id)->update(['enabled' => 0]);
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
        $data['form_action'] = ci_route('klasifikasi.proses_impor');

        return view('admin.klasifikasi.import', $data);
    }

    public function proses_impor(): void
    {
        isCan('u');

        $this->load->library('MY_Upload', null, 'upload');
        $this->upload->initialize([
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'xls|xlsx|xlsm',
            'file_name'     => namafile('Impor Klasifikasi Surat'),
        ]);

        if ($this->upload->do_upload('klasifikasi')) {
            $upload = $this->upload->data();

            $result = (new KlasifikasiSuratImports($upload['full_path']))->import();
            if (! $result) {
                redirect_with('error', 'Klasifikasi surat gagal diimport');
            }
        }

        redirect_with('success', 'Klasifikasi surat berhasil diimport');
    }

    protected static function validated($data): array
    {
        return [
            'kode'   => alfanumerik_titik($data['kode']),
            'nama'   => alfa_spasi($data['nama']),
            'uraian' => strip_tags($data['uraian']),
        ];
    }
}
