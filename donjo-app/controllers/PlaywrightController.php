<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class PlaywrightController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (! in_array(ENVIRONMENT, ['development', 'testing']) && ! config_item('demo_mode')) {
            show_404();
        }
    }

    public function artisan()
    {
        $request = request();

        $this->validated($request, [
            'command'    => 'required|string',
            'parameters' => 'nullable|array',
        ]);

        return $this->handleWithJsonResponse(function () use ($request) {
            Artisan::call(
                command: $request->input('command'),
                parameters: $request->input('parameters', [])
            );

            return Artisan::output();
        });
    }

    public function user()
    {
        return $this->jsonResponse(auth()->user()?->setHidden([])->setVisible([]));
    }

    public function query()
    {
        $request = request();

        $this->validated($request, [
            'connection' => 'nullable|string',
            'query'      => 'required|string',
            'bindings'   => 'nullable|array',
            'unprepared' => 'nullable|boolean'
        ]);

        return $this->handleWithJsonResponse(function () use ($request) {
            $connection = DB::connection($request->input('connection'));
            $query      = $request->input('query');
            $bindings   = $request->input('bindings', []);
            $unprepared = $request->boolean('unprepared', false);

            $success = $unprepared
                ? $connection->unprepared($query)
                : $connection->statement($query, $bindings);

            return ['success' => $success];
        });
    }

    public function select()
    {
        $request = request();

        $this->validated($request, [
            'connection' => 'nullable|string',
            'query'      => 'string|required',
            'bindings'   => 'nullable|array',
        ]);

        return $this->handleWithJsonResponse(function () use ($request) {
            $connection = DB::connection($request->input('connection'));
            $query      = $request->input('query');
            $bindings   = $request->input('bindings', []);

            return $connection->select($query, $bindings);
        });
    }

    protected function jsonResponse($data, int $status = 200)
    {
        return response(json_encode($data), $status)
            ->header('Content-Type', 'application/json')
            ->send();
    }

    protected function handleWithJsonResponse(Closure $callback)
    {
        try {
            return $this->jsonResponse($callback());
        } catch (\Exception $e) {
            logger()->error($e);

            return $this->jsonResponse([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
