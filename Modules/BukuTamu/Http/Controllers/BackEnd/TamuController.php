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

defined('BASEPATH') || exit('No direct script access allowed');

require_once FCPATH . 'Modules/BukuTamu/Http/Controllers/BackEnd/AnjunganBaseController.php';

use App\Enums\JenisKelaminEnum;
use App\Enums\StatusEnum;
use App\Models\RefJabatan;
use Carbon\Carbon;
use Modules\BukuTamu\Models\KeperluanModel;
use Modules\BukuTamu\Models\KepuasanModel;
use Modules\BukuTamu\Models\TamuModel;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

class TamuController extends AnjunganBaseController
{
    public $moduleName          = 'BukuTamu';
    public $modul_ini           = 'buku-tamu';
    public $sub_modul_ini       = 'data-tamu';
    public $kategori_pengaturan = 'buku-tamu';
    public $aliasController     = 'buku_tamu';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        if (request()->ajax()) {
            $filters = [
                'tanggal' => request()->get('tanggal'),
            ];

            return datatables()->of(TamuModel::query()
                ->with('jk')
                ->filters($filters))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('buku_tamu.edit', $row->id) . '" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('buku_tamu.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('tampil_foto', static fn ($row): string => '<a data-fancybox="buku-tamu" href="' . $row->url_foto . '"><img src="' . $row->url_foto . '" class="penduduk_kecil text-center" alt="' . $row->nama . '"></a>')
                ->editColumn('created_at', static fn ($row): string => Carbon::parse($row->created_at)->dayName . ' / ' . tgl_indo($row->created_at))
                ->rawColumns(['ceklist', 'tampil_foto', 'aksi'])
                ->make();
        }

        return view('bukutamu::backend.tamu.index');
    }

    public function edit($id = null)
    {
        isCan('u');

        $data['action']      = 'Ubah';
        $data['form_action'] = ci_route('buku_tamu.update', $id);
        $data['buku_tamu']   = TamuModel::findOrFail($id);
        $data['bertemu']     = RefJabatan::pluck('nama', 'id');
        $data['keperluan']   = KeperluanModel::whereStatus(StatusEnum::YA)->pluck('keperluan', 'id');

        return view('bukutamu::backend.tamu.form', $data);
    }

    public function update($id = null): void
    {
        isCan('u');

        $dataTamu = TamuModel::findOrFail($id);

        if ($dataTamu->update($this->validate())) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    private function validate(): array
    {
        return [
            'nama'          => htmlentities((string) request('nama')),
            'telepon'       => htmlentities((string) request('telepon')),
            'instansi'      => htmlentities((string) request('instansi')),
            'jenis_kelamin' => bilangan(request('jenis_kelamin')),
            'alamat'        => htmlentities((string) request('alamat')),
            'bidang'        => bilangan(request('bidang')),
            'keperluan'     => htmlentities((string) request('keperluan')),
        ];
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (TamuModel::destroy($this->request['id_cb'] ?? $id) !== 0) {
            KepuasanModel::whereIdNama($this->request['id_cb'] ?? $id)->delete();
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function cetak()
    {
        return view('bukutamu::backend.tamu.cetak', [
            'data_tamu' => $this->data(),
        ]);
    }

    private function data()
    {
        $paramDatatable = json_decode((string) $this->input->post('params'), 1);
        $_GET           = $paramDatatable;
        $query          = $this->sumberData();
        if ($paramDatatable['start']) {
            $query->skip($paramDatatable['start']);
        }

        return $query->take($paramDatatable['length'])->get();
    }

    private function sumberData()
    {
        $filters = [
            'tanggal' => $this->input->get('tanggal') ?? null,
        ];

        return TamuModel::filters($filters);
    }

    public function ekspor(): void
    {
        $tanggal = $this->input->get('tanggal');
        $writer  = new Writer();
        $writer->openToBrowser(namafile('Buku Tamu') . '.xlsx');
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Data Tamu');

        // Deklarasi Style
        $border = new Border(
            new BorderPart(Border::TOP, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::BOTTOM, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::LEFT, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::RIGHT, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID)
        );

        $borderStyle = (new Style())
            ->setBorder($border);

        $yellowBackgroundStyle = (new Style())
            ->setBackgroundColor(Color::YELLOW)
            ->setFontBold()
            ->setBorder($border);

        // Cetak Header Tabel
        $values        = ['NO', 'HARI / TANGGAL', 'NAMA', 'TELEPON', 'INSTANSI', 'JENIS KELAMIN', 'ALAMAT', 'BERTEMU', 'KEPERLUAN'];
        $rowFromValues = Row::fromValues($values, $yellowBackgroundStyle);
        $writer->addRow($rowFromValues);

        // Cetak Data
        foreach ($this->data($tanggal) as $no => $data) {
            $cells = [
                $no + 1,
                Carbon::parse($data->created_at)->dayName . ' / ' . tgl_indo($data->created_at) . ' - ' . Carbon::parse($data->created_at)->format('H:i:s'),
                $data->nama,
                $data->telepon,
                $data->instansi,
                JenisKelaminEnum::all()[$data->jenis_kelamin],
                $data->alamat,
                $data->bidang,
                $data->keperluan,
            ];

            $singleRow = Row::fromValues($cells);
            $singleRow->setStyle($borderStyle);
            $writer->addRow($singleRow);
        }

        $writer->close();
    }
}
