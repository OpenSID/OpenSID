<?php

namespace Laminas\Session;

class Module
{
    /**
     * Retrieve default laminas-session config for laminas-mvc context.
     *
     * @return array
     */
    public function getConfig()
    {
        $provider = new ConfigProvider();
        return [
            'service_manager' => $provider->getDependencyConfig(),
        ];
    }
}
