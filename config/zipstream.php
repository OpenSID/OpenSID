<?php

return [
    // Try to predict the zip size up front and send a Content-Length header
    'predict_size' => true,

    // Compression method used only if we don't (or can't) predict the zip size
    'compression_method' => 'deflate',

    // Remove all non-ascii characters from filenames
    'ascii_filenames' => true,

    // AWS configs for S3 files
    'aws' => [
        'credentials' => [
            'key'    => null,
            'secret' => null
        ],
        'version'                 => 'latest',
        'endpoint'                => null,
        'use_path_style_endpoint' => false,
        'region'                  => 'us-east-1'
    ],

    // https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials_anonymous.html
    'aws_anonymous_client' => false
];
