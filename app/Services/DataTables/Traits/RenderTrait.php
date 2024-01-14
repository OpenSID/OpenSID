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

namespace App\Services\DataTables\Traits;

use CI_Output;
use Exception;

trait RenderTrait
{
    /**
     * Render json response.
     */
    protected function render(array $data)
    {
        $output = $this->attachAppends([
            'draw'            => (int) $this->request->input('draw'),
            'recordsTotal'    => $this->totalRecords,
            'recordsFiltered' => $this->filteredRecords,
            'data'            => $data,
        ]);

        if ($this->isDebugging()) {
            $output = $this->showDebugger($output);
        }

        foreach ($this->searchPanes as $column => $searchPane) {
            $output['searchPanes']['options'][$column] = $searchPane['options'];
        }

        /** @var CI_Output */
        $response = app('ci')->output
            ->set_content_type('application/json', 'utf-8')
            ->set_status_header(200)
            ->set_output(json_encode($output, $this->isDebugging() ? JSON_PRETTY_PRINT : $this->config->get('datatables.json.options', 0)));

        foreach ($this->config->get('datatables.json.header', []) as $key => $value) {
            $response = $response->set_header("{$key}: {$value}");
        }

        return $response;
    }

    /**
     * Return an error json response.
     *
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    protected function errorResponse(Exception $exception)
    {
        $error = $this->config->get('datatables.error');
        $debug = $this->isDebugging();

        if ($error === 'throw' || (! $error && ! $debug)) {
            throw $exception;
        }

        log_message('error', $exception);

        return app('ci')->output
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode([
                'draw'            => (int) $this->request->input('draw'),
                'recordsTotal'    => $this->totalRecords,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => $error ?: "Exception Message:\n\n" . $exception->getMessage(),
            ], JSON_PRETTY_PRINT));
    }

    protected function isDebugging(): bool
    {
        return ENVIRONMENT === 'development';
    }
}
