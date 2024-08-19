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

use App\Models\KlasifikasiSurat;
use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

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
        $this->redirect_hak_akses('u');

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
        $this->redirect_hak_akses('u');
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
        $this->redirect_hak_akses('u');
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
        $this->redirect_hak_akses('h', 'klasifikasi');
        KlasifikasiSurat::where('id', (int) $id)->delete();
        redirect_with('success', 'Klasifikasi surat berhasil dihapus');
    }

    public function delete_all(): void
    {
        $this->redirect_hak_akses('h', 'klasifikasi');
        KlasifikasiSurat::whereIn('id', $this->request['id_cb'])->delete();

        redirect_with('success', 'Klasifikasi surat berhasil dihapus');
    }

    public function lock($id = ''): void
    {
        $this->redirect_hak_akses('u');
        KlasifikasiSurat::where('id', (int) $id)->update(['enabled' => 0]);
        redirect_with('success', 'Klasifikasi surat berhasil dinonaktifkan');
    }

    public function unlock($id = ''): void
    {
        $this->redirect_hak_akses('u');
        KlasifikasiSurat::where('id', (int) $id)->update(['enabled' => 1]);
        redirect_with('success', 'Klasifikasi surat berhasil diaktifkan');
    }

    public function ekspor(): void
    {
        //Nama File
        $writer   = WriterEntityFactory::createXLSXWriter();
        $fileName = namafile('klasifikasi_surat_' . date('d-m-Y')) . '.xlsx';
        $writer->openToBrowser($fileName);

        // Sheet Program
        $writer->getCurrentSheet()->setName('klasifikasi');
        $writer->addRow(WriterEntityFactory::createRowFromArray(['kode', 'nama', 'uraian']));

        foreach (KlasifikasiSurat::select(['kode', 'nama', 'uraian'])->get()->toArray() as $row) {
            $rowFromValues = WriterEntityFactory::createRowFromArray($row);
            $writer->addRow($rowFromValues);
        }

        $writer->close();
    }

    public function impor()
    {
        $this->redirect_hak_akses('u');
        $data['form_action'] = ci_route('klasifikasi.proses_impor');

        return view('admin.klasifikasi.import', $data);
    }

    public function proses_impor(): void
    {
        $this->redirect_hak_akses('u');

        $this->load->library('MY_Upload', null, 'upload');
        $this->upload->initialize([
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'xls|xlsx|xlsm',
            'file_name'     => namafile('Impor Klasifikasi Surat'),
        ]);

        if ($this->upload->do_upload('klasifikasi')) {
            $upload = $this->upload->data();
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($upload['full_path']);
            $configId = identitas('id');

            try {
                foreach ($reader->getSheetIterator() as $sheet) {
                    // Sheet klasifikasi
                    if ($sheet->getName() == 'klasifikasi') {
                        $dataUpdate = [];

                        foreach ($sheet->getRowIterator() as $index => $row) {
                            if ($index <= 1) {
                                continue;
                            }
                            $cells        = $row->getCells();
                            $dataUpdate[] = [
                                'kode'      => (string) $cells[0],
                                'nama'      => (string) $cells[1],
                                'uraian'    => (string) $cells[2],
                                'config_id' => $configId,
                            ];
                        }
                        KlasifikasiSurat::upsert($dataUpdate, ['kode', 'config_id']);
                    }
                }

                $reader->close();
                redirect_with('success', 'Klasifikasi surat berhasil diimport');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                $reader->close();
                redirect_with('error', 'Gagal import klasifikasi surat');
            }
        }
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
