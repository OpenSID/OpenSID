<?php

defined('BASEPATH') || exit('No direct script access allowed');

trait ModulTrait
{
    public $moduleDirectory;
    public $moduleName;

    protected $except = [
        'Anjungan',
    ];

    protected function getModuleDirectory()
    {
        $reflection = new ReflectionClass(static::class);
        $directory = dirname($reflection->getFileName());

        // Find the position of "\Http\Controllers" to trim everything after the module directory
        $moduleDirectory = substr($directory, 0, strpos($directory, 'Http\Controllers') - 1);

        return $moduleDirectory;
    }

    private function loadHelper(): void
    {
        foreach (glob($this->moduleDirectory . '/Helpers/*.php') as $file) {
            require_once $file;
        }
    }

    private function loadConfig(): void
    {
        foreach (glob($this->moduleDirectory . '/Config/*.php') as $file) {
            $this->mergeConfigFrom($file, substr(basename($file), 0, -4));
        }
    }

    protected function mergeConfigFrom($path, $key)
    {
        $config = app()->make('config');

        $config->set($key, array_merge(
            require $path,
            $config->get($key, [])
        ));
    }

    protected function loadModuleJson()
    {
        $path = $this->moduleDirectory . '/module.json';
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }

    protected function activate()
    {
        if (in_array($this->moduleName, $this->except)) {
            return true;
        }
    
        if ((config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO)) || cache('siappakai') === true) {
            return true;
        }

        if (! in_array($this->moduleName, cache('modul_aktif') ?? [])) {
            set_session('error', 'Paket ' . $this->moduleName . ' belum bisa digunakan karena belum diaktivasi.');

            redirect('plugin');
        }
    }
}