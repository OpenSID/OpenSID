<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\Config;

defined('BASEPATH') || exit('No direct script access allowed');

class LogViewer
{
    public const LOG_LINE_START_PATTERN = '/^\\[\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\] \\w+\\.((INFO)|(ERROR)|(DEBUG)|(ALL)|(NOTICE)):/';
    public const LOG_DATE_PATTERN       = ['/^\\[/', '/\\]\\s\\w+\\.((INFO)|(ERROR)|(DEBUG)|(ALL)|(NOTICE)):/'];
    public const LOG_LEVEL_PATTERN      = '/\\b((INFO)|(ERROR)|(DEBUG)|(ALL)|(NOTICE))\\b/';

    //these are the config keys expected in the config.php
    public const LOG_FILE_PATTERN_CONFIG_KEY = 'clv_log_file_pattern';
    public const LOG_FOLDER_PATH_CONFIG_KEY  = 'log_path';

    //this is the name of the view file passed to CI load->view()
    public const CI_LOG_VIEW_FILE_PATH = 'cilogviewer/logs';
    public const MAX_LOG_SIZE          = 52_428_800; //50MB
    public const MAX_STRING_LENGTH     = 300; //300 chars

    /**
     * These are the constants representing the
     * various API commands there are
     */
    private const API_QUERY_PARAM = 'api';

    private const API_FILE_QUERY_PARAM      = 'f';
    private const API_LOG_STYLE_QUERY_PARAM = 'sline';
    private const API_CMD_LIST              = 'list';
    private const API_CMD_VIEW              = 'view';
    private const API_CMD_DELETE            = 'delete';

    private static array $levelsIcon = [
        'INFO'   => 'glyphicon glyphicon-info-sign',
        'ERROR'  => 'glyphicon glyphicon-warning-sign',
        'DEBUG'  => 'glyphicon glyphicon-exclamation-sign',
        'ALL'    => 'glyphicon glyphicon-minus',
        'NOTICE' => 'glyphicon glyphicon-info-sign',
    ];
    private static array $levelClasses = [
        'INFO'   => 'info',
        'ERROR'  => 'danger',
        'DEBUG'  => 'warning',
        'ALL'    => 'muted',
        'NOTICE' => 'success',
    ];

    //this is the path (folder) on the system where the log files are stored
    private string $logFolderPath;

    //this is the pattern to pick all log files in the $logFilePath
    private $logFilePattern;

    //this is a combination of the LOG_FOLDER_PATH and LOG_FILE_PATTERN
    private string $fullLogFilePath = '';

    public function __construct()
    {
        $this->init();
    }

    /**
     * Bootstrap the library
     * sets the configuration variables
     *
     * @throws Exception
     */
    private function init(): void
    {
        $configLog = Config::get('app.log');
        //configure the log folder path and the file pattern for all the logs in the folder
        $this->logFolderPath  = null !== $configLog[self::LOG_FOLDER_PATH_CONFIG_KEY] ? rtrim($configLog[self::LOG_FOLDER_PATH_CONFIG_KEY], '/') : rtrim(APPPATH, '/') . '/logs';
        $this->logFilePattern = $configLog[self::LOG_FILE_PATTERN_CONFIG_KEY] ?? 'opensid-*.log';

        //concatenate to form Full Log Path
        $this->fullLogFilePath = $this->logFolderPath . '/' . $this->logFilePattern;
    }

    /**
     * This function will return the processed HTML page
     * and return it's content that can then be echoed
     *
     * @param $fileName optional base64_encoded filename of the log file to process.
     *
     * @returns the parse view file content as a string that can be echoed
     */
    public function showLogs()
    {
        if (null !== request()->get('del')) {
            $this->deleteFiles(base64_decode(request()->get('del'), true));
            redirect(request()->getPathInfo());

            return;
        }

        //process download of log file command
        //if the supplied file exists, then perform download
        //otherwise, just ignore which will resolve to page reloading
        $dlFile = request()->get('dl');
        if (null !== $dlFile && file_exists($this->logFolderPath . '/' . basename(base64_decode($dlFile, true)))) {
            $file = $this->logFolderPath . '/' . basename(base64_decode($dlFile, true));
            $this->downloadFile($file);
        }

        if (null !== request()->get(self::API_QUERY_PARAM)) {
            return $this->processAPIRequests(request()->get(self::API_QUERY_PARAM));
        }

        //it will either get the value of f or return null
        $fileName = request()->get('f');

        //get the log files from the log directory
        $files = $this->getFiles();

        //let's determine what the current log file is
        if (null !== $fileName) {
            $currentFile = $this->logFolderPath . '/' . basename(base64_decode($fileName, true));
        } elseif (null === $fileName && $files !== []) {
            $currentFile = $this->logFolderPath . '/' . $files[0];
        } else {
            $currentFile = null;
        }

        //if the resolved current file is too big
        //just trigger a download of the file
        //otherwise process its content as log

        if (null !== $currentFile && file_exists($currentFile)) {
            $fileSize = filesize($currentFile);

            $logs = is_int($fileSize) && $fileSize > self::MAX_LOG_SIZE ? null : $this->processLogs($this->getLogs($currentFile));
        } else {
            $logs = [];
        }

        $data['logs']        = $logs;
        $data['files']       = ! empty($files) ? $files : [];
        $data['currentFile'] = null !== $currentFile ? basename($currentFile) : '';

        return $data;
    }

    private function processAPIRequests(string $command): string
    {
        if ($command === self::API_CMD_LIST) {
            //respond with a list of all the files
            $response['status']    = true;
            $response['log_files'] = $this->getFilesBase64Encoded();
        } elseif ($command === self::API_CMD_VIEW) {
            //respond to view the logs of a particular file
            $file                  = request()->get(self::API_FILE_QUERY_PARAM);
            $response['log_files'] = $this->getFilesBase64Encoded();

            if (null === $file || empty($file)) {
                $response['status']           = false;
                $response['error']['message'] = 'Invalid File Name Supplied: [' . json_encode($file, JSON_THROW_ON_ERROR) . ']';
                $response['error']['code']    = 400;
            } else {
                $singleLine         = request()->get(self::API_LOG_STYLE_QUERY_PARAM);
                $singleLine         = null !== $singleLine && ($singleLine === true || $singleLine === 'true' || $singleLine === '1');
                $logs               = $this->processLogsForAPI($file, $singleLine);
                $response['status'] = true;
                $response['logs']   = $logs;
            }
        } elseif ($command === self::API_CMD_DELETE) {
            $file = request()->get(self::API_FILE_QUERY_PARAM);

            if (null === $file) {
                $response['status']           = false;
                $response['error']['message'] = 'NULL value is not allowed for file param';
                $response['error']['code']    = 400;
            } else {
                //decode file if necessary
                $fileExists = false;

                if ($file !== 'all') {
                    $file       = basename(base64_decode($file, true));
                    $fileExists = file_exists($this->logFolderPath . '/' . $file);
                } else {
                    //check if the directory exists
                    $fileExists = file_exists($this->logFolderPath);
                }

                if ($fileExists) {
                    $this->deleteFiles($file);
                    $response['status']  = true;
                    $response['message'] = 'File [' . $file . '] deleted';
                } else {
                    $response['status']           = false;
                    $response['error']['message'] = 'File does not exist';
                    $response['error']['code']    = 404;
                }
            }
        } else {
            $response['status']           = false;
            $response['error']['message'] = 'Unsupported Query Command [' . $command . ']';
            $response['error']['code']    = 400;
        }

        //convert response to json and respond
        header('Content-Type: application/json');
        if (! $response['status']) {
            //set a generic bad request code
            http_response_code(400);
        } else {
            http_response_code(200);
        }

        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    /**
     * This function will process the logs. Extract the log level, icon class and other information
     * from each line of log and then arrange them in another array that is returned to the view for processing
     *
     * @params logs. The raw logs as read from the log file
     *
     * @param mixed $logs
     *
     * @return array. An [[], [], [] ...] where each element is a processed log line
     */
    private function processLogs($logs): ?array
    {
        if (null === $logs) {
            return null;
        }

        $superLog = [];

        foreach ($logs as $log) {
            //get the logLine Start
            $logLineStart = $this->getLogLineStart($log);

            if (! empty($logLineStart)) {
                //this is actually the start of a new log and not just another line from previous log
                $level = $this->getLogLevel($logLineStart);
                $data  = [
                    'level' => $level,
                    'date'  => $this->getLogDate($logLineStart),
                    'icon'  => self::$levelsIcon[$level],
                    'class' => self::$levelClasses[$level],
                ];

                $logMessage = preg_replace(self::LOG_LINE_START_PATTERN, '', $log);

                if (strlen($logMessage) > self::MAX_STRING_LENGTH) {
                    $data['content'] = substr($logMessage, 0, self::MAX_STRING_LENGTH);
                    $data['extra']   = substr($logMessage, (self::MAX_STRING_LENGTH + 1));
                } else {
                    $data['content'] = $logMessage;
                }

                $superLog[] = $data;
            } elseif ($superLog !== []) {
                //this log line is a continuation of previous logline
                //so let's add them as extra
                $prevLog                        = $superLog[count($superLog) - 1];
                $extra                          = (array_key_exists('extra', $prevLog)) ? $prevLog['extra'] : '';
                $prevLog['extra']               = $extra . '<br>' . $log;
                $superLog[count($superLog) - 1] = $prevLog;
            }

            //this means the file has content that are not logged
            //using log_message()
            //they may be sensitive! so we are just skipping this
            //other we could have just insert them like this
            // array_push($superLog, [
            // 	"level" => "INFO",
            // 	"date" => "",
            // 	"icon" => self::$levelsIcon["INFO"],
            // 	"class" => self::$levelClasses["INFO"],
            // 	"content" => $log
            // ]);
        }

        return $superLog;
    }

    /**
     * This function will extract the logs in the supplied
     * fileName
     *
     * @param mixed $fileNameInBase64
     *
     * @return array|null
     *
     * @internal param $logs
     */
    private function processLogsForAPI($fileNameInBase64, bool $singleLine = false)
    {
        $logs = null;

        //let's prepare the log file name sent from the client
        $currentFile = $this->prepareRawFileName($fileNameInBase64);

        //if the resolved current file is too big
        //just return null
        //otherwise process its content as log
        if (null !== $currentFile) {
            $fileSize = filesize($currentFile);

            $logs = is_int($fileSize) && $fileSize > self::MAX_LOG_SIZE ? null : $this->getLogsForAPI($currentFile, $singleLine);
        }

        return $logs;
    }

    /**
     * extract the log level from the logLine
     *
     * @param $logLineStart - The single line that is the start of log line.
     *                       extracted by getLogLineStart()
     *
     * @return log level e.g. ERROR, DEBUG, INFO
     */
    private function getLogLevel($logLineStart)
    {
        preg_match(self::LOG_LEVEL_PATTERN, $logLineStart, $matches);

        return $matches[0];
    }

    private function getLogDate($logLineStart)
    {
        return preg_replace(self::LOG_DATE_PATTERN, '', $logLineStart);
    }

    private function getLogLineStart($logLine)
    {
        preg_match(self::LOG_LINE_START_PATTERN, $logLine, $matches);
        if (! empty($matches)) {
            return $matches[0];
        }

        return '';
    }

    /**
     * returns an array of the file contents
     * each element in the array is a line
     * in the underlying log file
     *
     * @returns array | each line of file contents is an entry in the returned array.
     *
     * @params complete fileName
     *
     * @param mixed $fileName
     */
    private function getLogs(string $fileName)
    {
        $size = filesize($fileName);
        if (! $size || $size > self::MAX_LOG_SIZE) {
            return null;
        }

        return file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * This function will get the contents of the log
     * file as a string. It will first check for the
     * size of the file before attempting to get the contents.
     *
     * By default it will return all the log contents as an array where the
     * elements of the array is the individual lines of the files
     * otherwise, it will return all file content as a single string with each line ending
     * in line break character "\n"
     *
     * @param mixed $fileName
     *
     * @return bool|string
     */
    private function getLogsForAPI(string $fileName, bool $singleLine = false)
    {
        $size = filesize($fileName);
        if (! $size || $size > self::MAX_LOG_SIZE) {
            return 'File Size too Large. Please donwload it locally';
        }

        return ($singleLine) ? file_get_contents($fileName) : file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * This will get all the files in the logs folder
     * It will reverse the files fetched and
     * make sure the latest log file is in the first index
     *
     * @param bool. If true returns the basename of the files otherwise full path
     * @param mixed $basename
     *
     * @returns array of file
     */
    private function getFiles($basename = true): array
    {
        $files = glob($this->fullLogFilePath);

        $files = array_reverse($files);
        $files = array_filter($files, 'is_file');
        if ($basename && is_array($files)) {
            foreach ($files as $k => $file) {
                $files[$k] = basename($file);
            }
        }

        return array_values($files);
    }

    /**
     * This function will return an array of available log
     * files
     * The array will containt the base64encoded name
     * as well as the real name of the fiile
     *
     * @return array
     *
     * @internal param bool $appendURL
     * @internal param bool $basename
     */
    private function getFilesBase64Encoded()
    {
        $files = glob($this->fullLogFilePath);

        $files = array_reverse($files);
        $files = array_filter($files, 'is_file');

        $finalFiles = [];

        //if we're to return the base name of the files
        //let's do that here
        foreach ($files as $file) {
            $finalFiles[] = ['file_b64' => base64_encode(basename($file)), 'file_name' => basename($file)];
        }

        return $finalFiles;
    }

    /**
     * Delete one or more log file in the logs directory
     *
     * @param filename. It can be all - to delete all log files - or specific for a file
     * @param mixed $fileName
     */
    private function deleteFiles($fileName): void
    {
        if ($fileName == 'all') {
            array_map('unlink', glob($this->fullLogFilePath));
        } else {
            unlink($this->logFolderPath . '/' . basename($fileName));
        }
    }

    /**
     * Download a particular file to local disk
     * This should only be called if the file exists
     * hence, the file exist check has ot be done by the caller
     *
     * @param       $fileName the complete file path
     * @param mixed $file
     */
    private function downloadFile(string $file): void
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);

        exit;
    }

    /**
     * This function will take in the raw file
     * name as sent from the browser/client
     * and append the LOG_FOLDER_PREFIX and decode it from base64
     *
     * @internal param $fileName
     *
     * @param mixed $fileNameInBase64
     */
    private function prepareRawFileName($fileNameInBase64): ?string
    {
        //let's determine what the current log file is
        if (null === $fileNameInBase64) {
            return null;
        }
        if (empty($fileNameInBase64)) {
            return null;
        }

        return $this->logFolderPath . '/' . basename(base64_decode($fileNameInBase64, true));
    }
}
