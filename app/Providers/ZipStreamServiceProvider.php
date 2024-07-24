<?php

namespace App\Providers;

use STS\ZipStream\Builder;
use Illuminate\Support\ServiceProvider;

class ZipStreamServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->configure('zipstream');
        $this->app->singleton('zipstream.builder', Builder::class);

        if (class_exists(\Aws\S3\S3Client::class)) {
            $this->app->singleton('zipstream.s3client', function($app) {
                $config = $app['config']->get('zipstream.aws');
    
                if (!count(array_filter($config['credentials']))) {
                    unset($config['credentials']);
                }
    
                if ($app['config']->get('zipstream.aws_anonymous_client')) {
                    $config['credentials'] = false;
                }
    
                return new \Aws\S3\S3Client($config);
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function provides(): array
    {
        return ['zipstream.builder', 'zipstream.s3client'];
    }
}
