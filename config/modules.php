<?php

return [
    'namespace' => 'Modules',

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'modules' => base_path('Modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules' assets path.
        |
        */
        'assets' => public_path('assets/modules'),

        /*
        |--------------------------------------------------------------------------
        | The migrations' path
        |--------------------------------------------------------------------------
        |
        | Where you run the 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        */
        'generator' => [
            // 'actions' => ['path' => 'Actions', 'generate' => false],
            // 'casts' => ['path' => 'Casts', 'generate' => false],
            // 'channels' => ['path' => 'Broadcasting', 'generate' => false],
            // 'class' => ['path' => 'Classes', 'generate' => false],
            // 'command' => ['path' => 'Console', 'generate' => false],
            // 'component-class' => ['path' => 'View/Components', 'generate' => false],
            // 'emails' => ['path' => 'Emails', 'generate' => false],
            // 'event' => ['path' => 'Events', 'generate' => false],
            'enums' => ['path' => 'Enums', 'generate' => false],
            // 'exceptions' => ['path' => 'Exceptions', 'generate' => false],
            // 'jobs' => ['path' => 'Jobs', 'generate' => false],
            'helpers' => ['path' => 'Helpers', 'generate' => false],
            // 'interfaces' => ['path' => 'Interfaces', 'generate' => false],
            // 'listener' => ['path' => 'Listeners', 'generate' => false],
            // 'model' => ['path' => 'Models', 'generate' => false],
            // 'notifications' => ['path' => 'Notifications', 'generate' => false],
            'observer' => ['path' => 'Observers', 'generate' => false],
            // 'policies' => ['path' => 'Policies', 'generate' => false],
            'provider' => ['path' => 'Providers', 'generate' => true],
            'repository' => ['path' => 'Repositories', 'generate' => false],
            // 'resource' => ['path' => 'Transformers', 'generate' => false],
            // 'route-provider' => ['path' => 'Providers', 'generate' => true],
            // 'rules' => ['path' => 'Rules', 'generate' => false],
            'services' => ['path' => 'Services', 'generate' => false],
            // 'scopes' => ['path' => 'Models/Scopes', 'generate' => false],
            'traits' => ['path' => 'Traits', 'generate' => false],

            // Http/
            'controller' => ['path' => 'Http/Controllers', 'generate' => true],
            // 'filter' => ['path' => 'Http/Middleware', 'generate' => false],
            // 'request' => ['path' => 'Http/Requests', 'generate' => false],

            // config/
            'config' => ['path' => 'config', 'generate' => true],

            // database/
            // 'factory' => ['path' => 'database/factories', 'generate' => true],
            'migration' => ['path' => 'Database/Migrations', 'generate' => true],
            'seeder' => ['path' => 'Database/Seeders', 'generate' => true],

            // lang/
            // 'lang' => ['path' => 'lang', 'generate' => false],

            // resource/
            'assets' => ['path' => 'Assets', 'generate' => true],
            // 'component-view' => ['path' => 'resources/views/components', 'generate' => false],
            'views' => ['path' => 'resources/views', 'generate' => true],

            // routes/
            'routes' => ['path' => 'Routes', 'generate' => true],

            // tests/
            // 'test-feature' => ['path' => 'tests/Feature', 'generate' => true],
            // 'test-unit' => ['path' => 'tests/Unit', 'generate' => true],
        ],
    ],
];