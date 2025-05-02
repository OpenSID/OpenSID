<?php
use ZipStream\CompressionMethod;

return [
    // Try to predict the zip size up front and send a Content-Length header
    'predict_size' => env('ZIPSTREAM_PREDICT_SIZE', true),

    // Compression method used only if we don't (or can't) predict the zip size
    'compression_method' => env('ZIPSTREAM_COMPRESSION_METHOD', 'store'),

    // Remove all non-ascii characters from filenames
    'ascii_filenames' => env('ZIPSTREAM_ASCII_FILENAMES', true),

    // AWS configs for S3 files
    'aws'     => [
        'credentials'             => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY')
        ],
        'version'                 => 'latest',
        'endpoint'                => env('AWS_ENDPOINT'),
        'use_path_style_endpoint' => env('ZIPSTREAM_AWS_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false)),
        'region'                  => env('ZIPSTREAM_AWS_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))
    ],

    // https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials_anonymous.html
    'aws_anonymous_client' => env('AWS_ANONYMOUS', false)
];
