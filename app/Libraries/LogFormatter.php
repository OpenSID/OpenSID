<?php
namespace App\Libraries;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class LogFormatter extends LineFormatter
{
    public function __construct()
    {
        // Define the format for the log entries
        $format = "[%datetime%] %channel%.%level_name%: %message%\n";
        $dateFormat = "Y-m-d H:i:s";
        parent::__construct($format, $dateFormat);
    }

    // Override the method to customize the output
    public function format(LogRecord $record): string
    {
        // Format the context and extra data if needed
        $context = json_encode($record['context']);
        $extra = json_encode($record['extra']);
        
        // Return the formatted string
        return str_replace(
            ['%context%', '%extra%'],
            [$context, $extra],
            parent::format($record)
        );
    }
}