<?php

namespace App\Services\DataTables\Traits;

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

        /** @var \CI_Output */
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
     *
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    protected function errorResponse(\Exception $exception)
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
                'error'           => $error ?: "Exception Message:\n\n".$exception->getMessage(),
            ], JSON_PRETTY_PRINT));
    }

    protected function isDebugging(): bool
    {
        return ENVIRONMENT === 'development';
    }
}