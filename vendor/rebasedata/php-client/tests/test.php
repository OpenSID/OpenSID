<?php

declare(strict_types = 1);
error_reporting(E_ALL | E_STRICT);

require_once dirname(__DIR__).'/vendor/autoload.php';

use RebaseData\Client;
use RebaseData\Exception\ConversionException;

$inputFiles = [dirname(__DIR__).'/samples/access.accdb'];
$options = ['outputFormat' => 'xlsx'];

try {
    echo "Executing conversion, this might take some time..\n";

    $client = new Client('freemium');
    $zipFile = $client->convertAndReceiveZip($inputFiles, $options);

    echo "Conversion successful!\n";
    echo "You can find the ZIP archive containing the XLSX files in $zipFile\n";
} catch (ConversionException $e) {
    echo "Conversion failed: ".$e->getMessage()."\n";
}
