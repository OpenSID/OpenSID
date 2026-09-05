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

namespace Modules\Analisis\Libraries;

use Exception;
use Illuminate\Support\Facades\DB;
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
    private array $errors = [];

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Import analisis dari file Excel
     *
     * @param string $kode
     * @param int    $jenis
     */
    public function analisis($kode = '00000', $jenis = 2): array
    {
        try {
            return DB::transaction(function () use ($kode, $jenis) {
                $reader = new Reader();
                $reader->open($this->file);
                $id_master = null;

                foreach ($reader->getSheetIterator() as $sheet) {
                    switch ($sheet->getName()) {
                        case 'master':
                            $id_master = $this->imporMaster($sheet, $kode, $jenis);
                            break;

                        case 'pertanyaan':
                            if ($id_master) {
                                $this->imporPertanyaan($sheet, $id_master);
                            }
                            break;

                        case 'jawaban':
                            if ($id_master) {
                                $this->imporJawaban($sheet, $id_master);
                            }
                            break;

                        case 'klasifikasi':
                            if ($id_master) {
                                $this->imporKlasifikasi($sheet, $id_master);
                            }
                            break;

                        default:
                    }
                }
                $reader->close();

                // Jika ada error, throw exception untuk rollback transaction
                if (! empty($this->errors)) {
                    throw new Exception(implode('; ', $this->errors));
                }

                return [
                    'success'   => true,
                    'id_master' => $id_master,
                    'errors'    => [],
                ];
            });
        } catch (Exception $e) {
            return [
                'success'   => false,
                'id_master' => null,
                'errors'    => [$e->getMessage()],
            ];
        }
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Import data master dari sheet Excel
     *
     * @param object $sheet
     * @param string $kode
     * @param int    $jenis
     *
     * @return int|null ID master yang dibuat
     */
    private function imporMaster($sheet, $kode, $jenis): ?int
    {
        try {
            $master  = [];
            $periode = [];

            foreach ($sheet->getRowIterator() as $index => $row) {
                $cells = $row->getCells();

                switch ($index) {
                    case 1: // Nama analisis
                        $nama = $cells[1]->getValue();
                        if (empty($nama)) {
                            $this->addError('Sheet master: Nama analisis tidak boleh kosong (baris 1)');
                        }
                        $master['nama'] = judul($nama);
                        break;

                    case 2: // Subjek
                        $subjek = $cells[1]->getValue();
                        if (empty($subjek)) {
                            $this->addError('Sheet master: Subjek tipe tidak boleh kosong (baris 2)');
                        }
                        $master['subjek_tipe'] = $subjek;
                        break;

                    case 3: // Status
                        $master['lock'] = (int) $cells[1]->getValue() ?: 0;
                        break;

                    case 4: // Bilangan Pembagi
                        $pembagi = $cells[1]->getValue();
                        if (! empty($pembagi)) {
                            $master['pembagi'] = bilangan_titik($pembagi);
                        }
                        break;

                    case 5: // Deskripsi Analisis
                        $deskripsi             = $cells[1]->getValue();
                        $master['deskripsi']   = htmlentities($deskripsi);
                        $periode['keterangan'] = $deskripsi;
                        break;

                    case 6: // Nama Periode
                        $periode['nama'] = $cells[1]->getValue();
                        break;

                    case 7: // Tahun Pendataan
                        $periode['tahun_pelaksanaan'] = (int) $cells[1]->getValue();
                        break;
                }
            }

            // Validasi data master
            if (! $this->validateMasterData($master)) {
                return null;
            }

            // Siapkan data periode untuk validasi sebelum create master
            $periodeTemp              = $periode;
            $periodeTemp['id_master'] = 1; // Temporary ID untuk validasi
            $periodeTemp['aktif']     = 1;
            $periodeTemp['config_id'] = identitas('id');

            // Validasi data periode sebelum membuat master
            if (! $this->validatePeriodeData($periodeTemp)) {
                return null;
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
        } catch (Exception $e) {
            $this->addError('Error saat import master: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Validasi data master
     */
    private function validateMasterData(array $master): bool
    {
        if (empty($master['nama'])) {
            $this->addError('Nama analisis tidak boleh kosong');

            return false;
        }

        if (empty($master['subjek_tipe'])) {
            $this->addError('Subjek tipe tidak boleh kosong');

            return false;
        }

        return true;
    }

    /**
     * Validasi data periode
     */
    private function validatePeriodeData(array $periode): bool
    {
        if (empty($periode['nama'])) {
            $this->addError('Nama periode tidak boleh kosong');

            return false;
        }

        if (empty($periode['tahun_pelaksanaan'])) {
            $this->addError('Tahun pelaksanaan tidak boleh kosong');

            return false;
        }

        if (! is_numeric($periode['tahun_pelaksanaan'])) {
            $this->addError('Tahun pelaksanaan harus berupa angka');

            return false;
        }

        return true;
    }

    /**
     * Import pertanyaan/indikator dari sheet Excel
     *
     * @param mixed $sheet
     * @param mixed $id_master
     */
    private function imporPertanyaan($sheet, $id_master): void
    {
        try {
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index == 1) {
                    continue;
                } // Abaikan baris judul

                $cells = $row->getCells();

                // Validasi data minimal
                $nomor      = $cells[0]->getValue();
                $pertanyaan = $cells[1]->getValue();

                if (empty($nomor) || empty($pertanyaan)) {
                    $this->addError("Sheet pertanyaan (baris {$index}): Nomor dan Pertanyaan tidak boleh kosong");

                    continue;
                }

                // Tambahkan indikator
                $indikator                = [];
                $indikator['id_master']   = $id_master;
                $indikator['nomor']       = $nomor;
                $indikator['pertanyaan']  = htmlentities($pertanyaan);
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
        } catch (Exception $e) {
            $this->addError('Error saat import pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Import jawaban/parameter dari sheet Excel
     *
     * @param mixed $sheet
     * @param mixed $id_master
     */
    private function imporJawaban($sheet, $id_master): void
    {
        try {
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index == 1) {
                    continue;
                } // Abaikan baris judul

                $cells = $row->getCells();

                // Validasi data minimal
                $kode_pertanyaan = $cells[0]->getValue();
                $jawaban         = $cells[2]->getValue();

                if (empty($kode_pertanyaan) || empty($jawaban)) {
                    $this->addError("Sheet jawaban (baris {$index}): Kode Pertanyaan dan Jawaban tidak boleh kosong");

                    continue;
                }

                // Tambahkan parameter
                $parameter                 = [];
                $parameter['id_indikator'] = $this->getIdIndikator($kode_pertanyaan, $id_master);

                if (empty($parameter['id_indikator'])) {
                    $this->addError("Sheet jawaban (baris {$index}): Indikator dengan kode '{$kode_pertanyaan}' tidak ditemukan");

                    continue;
                }

                $parameter['jawaban']   = htmlentities($jawaban);
                $parameter['config_id'] = identitas('id');

                if (! empty($cells[1]) && $cells[1]->getValue()) {
                    $parameter['kode_jawaban'] = $cells[1]->getValue();
                }
                if (! empty($cells[3]) && $cells[3]->getValue()) {
                    $parameter['nilai'] = $cells[3]->getValue();
                }

                AnalisisParameter::create($parameter);
            }
        } catch (Exception $e) {
            $this->addError('Error saat import jawaban: ' . $e->getMessage());
        }
    }

    /**
     * Get kategori ID atau create jika belum ada
     *
     * @param mixed $kategori
     * @param mixed $id_master
     */
    private function getIdKategori($kategori, $id_master): ?int
    {
        if (empty($kategori)) {
            return null;
        }

        try {
            $adaKategori = AnalisisKategori::firstOrCreate(
                ['kategori' => $kategori, 'id_master' => $id_master],
                ['config_id' => identitas('id')]
            );

            return $adaKategori->id;
        } catch (Exception $e) {
            $this->addError('Error saat membuat kategori: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Get indikator ID berdasarkan kode pertanyaan
     *
     * @param mixed $kode_pertanyaan
     * @param mixed $id_master
     */
    private function getIdIndikator($kode_pertanyaan, $id_master): ?int
    {
        if (empty($kode_pertanyaan)) {
            return null;
        }

        return AnalisisIndikator::where([
            'id_master' => $id_master,
            'nomor'     => $kode_pertanyaan,
        ])->first()?->id;
    }

    /**
     * Add error message
     */
    private function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * Import klasifikasi dari sheet Excel
     *
     * @param mixed $sheet
     * @param mixed $id_master
     */
    private function imporKlasifikasi($sheet, $id_master): void
    {
        try {
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index == 1) {
                    continue;
                } // Abaikan baris judul

                $cells = $row->getCells();

                // Validasi data minimal
                $nama   = $cells[0]->getValue();
                $minval = $cells[1]->getValue();
                $maxval = $cells[2]->getValue();

                if (empty($nama) || $minval === '' || $maxval === '') {
                    $this->addError("Sheet klasifikasi (baris {$index}): Nama, Nilai Minimal, dan Nilai Maksimal tidak boleh kosong");

                    continue;
                }

                // Tambahkan klasifikasi
                $klasifikasi              = [];
                $klasifikasi['id_master'] = $id_master;
                $klasifikasi['nama']      = htmlentities($nama);
                $klasifikasi['minval']    = (float) $minval;
                $klasifikasi['maxval']    = (float) $maxval;
                $klasifikasi['config_id'] = identitas('id');

                AnalisisKlasifikasi::create($klasifikasi);
            }
        } catch (Exception $e) {
            $this->addError('Error saat import klasifikasi: ' . $e->getMessage());
        }
    }
}
