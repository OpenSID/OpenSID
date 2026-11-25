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

namespace Modules\Kehadiran\Exports;

use App\Models\Pamong;
use Modules\Kehadiran\Enums\StatusCatatan;
use Modules\Kehadiran\Services\RekapCatatanService;
use Modules\Kehadiran\Traits\HasIndonesianDateFormat;
use Rap2hpoutre\FastExcel\FastExcel;

class RekapCatatanExport
{
    use HasIndonesianDateFormat;

    protected $fields = [
        'tanggal',
        'hari',
        'uraian_kegiatan',
        'hasil_diharapkan',
    ];

    public function __construct(
        protected ?string $bulan = null,
        protected ?string $pamongId = null
    ) {
    }

    public function filename($name = null)
    {
        $period = $this->bulan ?? date('Y-m-d');

        return $name ?? namafile('rekap_catatan_' . $period);
    }

    public function data()
    {
        $service = new RekapCatatanService();

        // Parse bulan if provided
        $tahun      = null;
        $bulanNum   = null;
        $pamongName = '';

        if ($this->bulan) {
            [$tahun, $bulanNum] = explode('-', $this->bulan);
        }

        // Get pamong name if filtered by specific perangkat
        if ($this->pamongId) {
            $pamong = Pamong::where('pamong_id', $this->pamongId)
                ->with('penduduk:id,nama')
                ->first();
            $pamongName = $pamong?->penduduk?->nama ?? '-';
        }

        $catatans = $service->getDataForExcelExport(
            StatusCatatan::DISETUJUI->value,
            $this->bulan,
            $tahun,
            $this->pamongId
        );

        $dataExport = [];

        // Add data rows
        $catatans->each(static function ($catatan, $key) use (&$dataExport) {
            $dataExport[] = [
                'No'                    => $key + 1,
                'Tanggal'               => $catatan->tanggal?->format('d/m/Y') ?? '',
                'Hari'                  => $catatan->hari ?? '',
                'Nama Perangkat'        => $catatan->pamong?->pamong_nama ?? '',
                'Jabatan'               => $catatan->pamong?->jabatan?->nama ?? '',
                'Uraian Kegiatan'       => $catatan->uraian_kegiatan ?? '',
                'Hasil yang Diharapkan' => $catatan->hasil_diharapkan ?? '',
            ];
        });

        // Return empty row if no data
        if (empty($dataExport)) {
            return [[
                'No'                    => '',
                'Tanggal'               => '',
                'Hari'                  => '',
                'Nama Perangkat'        => '',
                'Jabatan'               => '',
                'Uraian Kegiatan'       => '',
                'Hasil yang Diharapkan' => '',
            ]];
        }

        return $dataExport;
    }

    public function download()
    {
        return (new FastExcel())->data($this->data())->download($this->filename() . '.xlsx');
    }

    public function export()
    {
        $filePath = sys_get_temp_dir() . '/' . $this->filename() . '.xlsx';

        return (new FastExcel())->data($this->data())->export($filePath);
    }
}
