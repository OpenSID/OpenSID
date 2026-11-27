<?php

defined('BASEPATH') || exit('No direct script access allowed');

use App\Bootstrap\RequestAwareTrait;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MY_Output extends CI_Output implements Responsable
{
    use RequestAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        if (method_exists($request, 'overrideGlobals')) {
            $request->overrideGlobals();
        }

        $this->final_output       = '';
        $this->cache_expiration   = 0;
        $this->headers            = [];
        $this->mimes              = [];
        $this->mime_type          = 'text/html';
        $this->enable_profiler    = false;
        $this->_zlib_oc           = false;
        $this->_compress_output   = false;
        $this->_profiler_sections = [];
        $this->parse_exec_vars    = true;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function _display($output = '')
    {
        if ($output !== '') {
            $this->final_output = $output;
        }

        $this->toResponse($this->getRequest());
    }

    /**
     * {@inheritDoc}
     */
    public function toResponse($request)
    {
        // Note: We use load_class() because we can't use $CI =& get_instance()
        // since this function is sometimes called by the caching mechanism,
        // which happens before the CI super object is available.
        $BM = &load_class('Benchmark', 'core');
        $CFG = &load_class('Config', 'core');

        // Grab the super object if we can.
        if (class_exists('CI_Controller', false)) {
            $CI = &get_instance();
        }

        // --------------------------------------------------------------------

        // Set the output data
        $output = $this->final_output;

        // --------------------------------------------------------------------

        // Do we need to write a cache file? Only if the controller does not have its
        // own _output() method and we are not dealing with a cache file, which we
        // can determine by the existence of the $CI object above
        if ($this->cache_expiration > 0 && isset($CI) && ! method_exists($CI, '_output')) {
            $this->_write_cache($output);
        }

        // --------------------------------------------------------------------

        // Parse out the elapsed time and memory usage,
        // then swap the pseudo-variables with the data
        $elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');

        if ($this->parse_exec_vars === true) {
            $memory = round(memory_get_usage() / 1024 / 1024, 2).'MB';
            $output = str_replace(['{elapsed_time}', '{memory_usage}'], [$elapsed, $memory], $output);
        }

        // --------------------------------------------------------------------

        // Handle compression
        $shouldCompress = isset($CI) // This means that we're not serving a cache file
            && $this->_compress_output === true
            && isset($_SERVER['HTTP_ACCEPT_ENCODING'])
            && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;

        if ($shouldCompress) {
            $output = gzencode($output);
        }

        // --------------------------------------------------------------------

        // Extract headers
        $headers = $this->extractHeaders();

        // Mark this as a CodeIgniter response
        $headers['X-CodeIgniter'] = true;

        // Add compression headers if needed
        if ($shouldCompress) {
            $headers['content-encoding'] = 'gzip';
            $headers['content-length'] = strlen($output);
        }

        // --------------------------------------------------------------------

        // Does the $CI object exist?
        // If not we know we are dealing with a cache file so we'll
        // simply echo out the data and exit.
        if (! isset($CI)) {
            if ($this->_compress_output === true) {
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                    $headers['content-encoding'] = 'gzip';
                    $headers['content-length'] = self::strlen($output);
                } else {
                    // User agent doesn't support gzip compression,
                    // so we'll have to decompress our cache
                    $output = gzinflate(self::substr($output, 10, -8));
                }
            }

            log_message('info', 'Final output sent to browser');
            log_message('debug', 'Total execution time: '.$elapsed);

            $status = max(100, min(599, http_response_code() ?: 200));

            return Response::make($output, $status, $headers);
        }

        // --------------------------------------------------------------------

        // Do we need to generate profile data?
        // If so, load the Profile class and run it.
        if ($this->enable_profiler === true) {
            $CI->load->library('profiler');
            if (! empty($this->_profiler_sections)) {
                $CI->profiler->set_sections($this->_profiler_sections);
            }

            // If the output data contains closing </body> and </html> tags
            // we will remove them and add them back after we insert the profile data
            $output = preg_replace('|</body>.*?</html>|is', '', $output, -1, $count).$CI->profiler->run();
            if ($count > 0) {
                $output .= '</body></html>';
            }
        }

        // --------------------------------------------------------------------

        // Check if controller has custom _output method
        if (method_exists($CI, '_output')) {
            // Let controller handle the output, but we still need to return response
            ob_start();
            $CI->_output($output);
            $customOutput = ob_get_clean();
            if (! empty($customOutput)) {
                $output = $customOutput;
            }
        }

        log_message('info', 'Final output sent to browser');
        log_message('debug', 'Total execution time: '.$elapsed);

        // --------------------------------------------------------------------

        // Determine response status and handle redirects
        $status = max(100, min(599, http_response_code() ?: 200));

        // Handle redirects
        if ($status >= 300 && $status < 400) {
            return Response::redirectTo($headers['location'] ?? '/', $status);
        }

        return Response::make($output, $status, $headers);
    }

    /**
     * Extract headers from CodeIgniter's output and PHP's headers_list.
     */
    protected function extractHeaders(): array
    {
        $headers = [];

        // Get all header lines CodeIgniter has set
        $headerLines = array_map(function ($headers) {
            return array_shift($headers);
        }, $this->headers);

        // Merge with PHP's headers_list()
        $allHeaders = array_merge($headerLines, headers_list());

        foreach ($allHeaders as $headerLine) {
            if (strpos($headerLine, ':') === false
                || preg_match('/^HTTP\//', $headerLine)
                || stripos($headerLine, 'X-Powered-By:') === 0
            ) {
                continue;
            }

            [$key, $value] = array_map('trim', explode(':', $headerLine, 2));
            $headers[$key] = $value;
        }

        return $headers;
    }
}
