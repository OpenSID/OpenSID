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

use App\Enums\JenisKelaminEnum;
use App\Enums\StatusEnum;
use App\Models\BukuKeperluan;
use App\Models\BukuKepuasan;
use App\Models\BukuTamu;
use App\Models\RefJabatan;
use Carbon\Carbon;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Writer\Common\Creator\Style\BorderBuilder;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

class Buku_tamu extends Anjungan_Controller
{
    public $modul_ini           = 'buku-tamu';
    public $sub_modul_ini       = 'data-tamu';
    public $kategori_pengaturan = 'buku-tamu';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $filters = [
                'tanggal' => $this->input->get('tanggal'),
            ];

            return datatables()->of(BukuTamu::query()
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

        return view('admin.buku_tamu.tamu.index');
    }

    public function edit($id = null)
    {
        isCan('u');

        $data['action']      = 'Ubah';
        $data['form_action'] = ci_route('buku_tamu.update', $id);
        $data['buku_tamu']   = BukuTamu::findOrFail($id);
        $data['bertemu']     = RefJabatan::pluck('nama', 'id');
        $data['keperluan']   = BukuKeperluan::whereStatus(StatusEnum::YA)->pluck('keperluan', 'id');

        return view('admin.buku_tamu.tamu.form', $data);
    }

    public function update($id = null): void
    {
        isCan('u');

        $dataTamu = BukuTamu::findOrFail($id);

        if ($dataTamu->update($this->validate())) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    private function validate(): array
    {
        $request = $this->input->post();

        return [
            'nama'          => htmlentities($request['nama']),
            'telepon'       => htmlentities($request['telepon']),
            'instansi'      => htmlentities($request['instansi']),
            'jenis_kelamin' => bilangan($request['jenis_kelamin']),
            'alamat'        => htmlentities($request['alamat']),
            'bidang'        => bilangan($request['id_bidang']),
            'keperluan'     => bilangan($request['id_keperluan']),
        ];
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (BukuTamu::destroy($this->request['id_cb'] ?? $id) !== 0) {
            BukuKepuasan::whereIdNama($this->request['id_cb'] ?? $id)->delete();
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function cetak()
    {
        return view('admin.buku_tamu.tamu.cetak', [
            'data_tamu' => $this->data(),
        ]);
    }

    private function data()
    {
        $paramDatatable = json_decode($this->input->post('params'), 1);
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

        return BukuTamu::filters($filters);
    }

    public function ekspor(): void
    {
        $tanggal = $this->input->get('tanggal');
        $writer  = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser(namafile('Buku Tamu') . '.xlsx');
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Data Tamu');

        // Deklarasi Style
        $border = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->build();

        $borderStyle = (new StyleBuilder())
            ->setBorder($border)
            ->build();

        $yellowBackgroundStyle = (new StyleBuilder())
            ->setBackgroundColor(Color::YELLOW)
            ->setFontBold()
            ->setBorder($border)
            ->build();

        // Cetak Header Tabel
        $values        = ['NO', 'HARI / TANGGAL', 'NAMA', 'TELEPON', 'INSTANSI', 'JENIS KELAMIN', 'ALAMAT', 'BERTEMU', 'KEPERLUAN'];
        $rowFromValues = WriterEntityFactory::createRowFromArray($values, $yellowBackgroundStyle);
        $writer->addRow($rowFromValues);

        // Cetak Data
        foreach ($this->data($tanggal) as $no => $data) {
            $cells = [
                WriterEntityFactory::createCell($no + 1),
                WriterEntityFactory::createCell(Carbon::parse($data->created_at)->dayName . ' / ' . tgl_indo($data->created_at) . ' - ' . Carbon::parse($data->created_at)->format('H:i:s')),
                WriterEntityFactory::createCell($data->nama),
                WriterEntityFactory::createCell($data->telepon),
                WriterEntityFactory::createCell($data->instansi),
                WriterEntityFactory::createCell(JenisKelaminEnum::all()[$data->jenis_kelamin]),
                WriterEntityFactory::createCell($data->alamat),
                WriterEntityFactory::createCell($data->bidang),
                WriterEntityFactory::createCell($data->keperluan),
            ];

            $singleRow = WriterEntityFactory::createRow($cells);
            $singleRow->setStyle($borderStyle);
            $writer->addRow($singleRow);
        }

        $writer->close();
    }
}
