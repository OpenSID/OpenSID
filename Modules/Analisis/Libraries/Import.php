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

namespace Modules\Analisis\Libraries;

use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisKategori;
use Modules\Analisis\Models\AnalisisKlasifikasi;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisPeriode;
use OpenSpout\Reader\XLSX\Reader;

class Import
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function analisis($kode = '00000', $jenis = 2): void
    {
        $reader = new Reader();
        $reader->open($this->file);
        $id_master = null;

        foreach ($reader->getSheetIterator() as $sheet) {
            switch ($sheet->getName()) {
                case 'master':
                    $id_master = $this->impor_master($sheet, $kode, $jenis);
                    break;

                case 'pertanyaan':
                    $this->imporPertanyaan($sheet, $id_master);
                    break;

                case 'jawaban':
                    $this->imporJawaban($sheet, $id_master);
                    break;

                case 'klasifikasi':
                    $this->imporKlasifikasi($sheet, $id_master);
                    break;

                default:
            }
        }
        $reader->close();
    }

    private function impor_master($sheet, $kode, $jenis)
    {
        $master = [];

        foreach ($sheet->getRowIterator() as $index => $row) {
            $cells = $row->getCells();

            switch ($index) {
                case 1: // Nama analisis
                    $master['nama'] = $cells[1]->getValue();
                    break;

                case 2: // Subjek
                    $master['subjek_tipe'] = $cells[1]->getValue();
                    break;

                case 3: // Status
                    $master['lock'] = $cells[1]->getValue();
                    break;

                case 4: // Bilangan Pembagi
                    $master['pembagi'] = $cells[1]->getValue();
                    break;

                case 5: // Deskripsi Analisis
                    $master['deskripsi']   = $cells[1]->getValue();
                    $periode['keterangan'] = $cells[1]->getValue();
                    break;

                case 6: // Nama Periode
                    $periode['nama'] = $cells[1]->getValue();
                    break;

                case 7: // Tahun Pendataan
                    $periode['tahun_pelaksanaan'] = $cells[1]->getValue();
                    break;
            }
        }
        $master['kode_analisis'] = $kode;
        $master['jenis']         = $jenis;
        $master['config_id']     = identitas('id');

        $analisisMaster = AnalisisMaster::create($master);

        $periode['id_master'] = $analisisMaster->id;
        $periode['aktif']     = 1;
        $periode['config_id'] = identitas('id');

        AnalisisPeriode::create($periode);

        return $analisisMaster->id;
    }

    private function imporPertanyaan($sheet, $id_master)
    {
        foreach ($sheet->getRowIterator() as $index => $row) {
            if ($index == 1) {
                continue;
            } // Abaikan baris judul
            $cells = $row->getCells();
            // Tambahkan indikator
            $indikator                = [];
            $indikator['id_master']   = $id_master;
            $indikator['nomor']       = $cells[0]->getValue();
            $indikator['pertanyaan']  = $cells[1]->getValue();
            $indikator['id_kategori'] = $this->getIdKategori($cells[2]->getValue(), $id_master);
            $indikator['id_tipe']     = $cells[3]->getValue();
            $indikator['config_id']   = identitas('id');
            if (! empty($cells[4]) && $cells[4]->getValue()) {
                $indikator['bobot'] = (int) $cells[4]->getValue();
            }
            if (! empty($cells[5]) && $cells[5]->getValue()) {
                $indikator['act_analisis'] = $cells[5]->getValue();
            }

            AnalisisIndikator::create($indikator);
        }
    }

    private function getIdKategori($kategori, $id_master)
    {
        $adaKategori = AnalisisKategori::firstOrCreate(['kategori' => $kategori, 'id_master' => $id_master]);

        return $adaKategori->id;
    }

    private function imporJawaban($sheet, $id_master)
    {
        foreach ($sheet->getRowIterator() as $index => $row) {
            if ($index == 1) {
                continue;
            } // Abaikan baris judul
            $cells = $row->getCells();
            // Tambahkan parameter
            $parameter                 = [];
            $parameter['id_indikator'] = $this->getIdIndikator($cells[0]->getValue(), $id_master);
            $parameter['jawaban']      = $cells[2]->getValue();
            $parameter['config_id']    = identitas('id');
            if (! empty($cells[1]) && $cells[1]->getValue()) {
                $parameter['kode_jawaban'] = $cells[1]->getValue();
            }
            if (! empty($cells[3]) && $cells[3]->getValue()) {
                $parameter['nilai'] = $cells[3]->getValue();
            }
            AnalisisParameter::create($parameter);
        }
    }

    private function getIdIndikator($kode_pertanyaan, $id_master)
    {
        return AnalisisIndikator::where(['id_master' => $id_master, 'nomor' => $kode_pertanyaan])->first()?->id;
    }

    private function imporKlasifikasi($sheet, $id_master)
    {
        foreach ($sheet->getRowIterator() as $index => $row) {
            if ($index == 1) {
                continue;
            } // Abaikan baris judul
            $cells = $row->getCells();
            // Tambahkan parameter
            $klasifikasi              = [];
            $klasifikasi['id_master'] = $id_master;
            $klasifikasi['nama']      = $cells[0]->getValue();
            $klasifikasi['minval']    = $cells[1]->getValue();
            $klasifikasi['maxval']    = $cells[2]->getValue();
            $klasifikasi['config_id'] = identitas('id');

            AnalisisKlasifikasi::create($klasifikasi);
        }
    }
}
