<?php

namespace OpenSID;

/**
 * Miscellaneous functions used across OpenSID CI
 *  
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Utils
{    
    /**
     * Gets the current url
     * 
     * (Taken from the CodeIgniter CI_Uri class)
     * 
     * @return string
     */
    public static function currentUrl()
    {
        if(is_cli())
        {
            $args = array_slice($_SERVER['argv'], 1);
            return $args ? implode('/', $args) : '/';
        }

        $uriProtocol = config_item('uri_protocol');

        $removeRelativeDirectory = function($uri)
        {
            $uris = array();
            $tok = strtok($uri, '/');
            while ($tok !== FALSE)
            {
                if (( ! empty($tok) OR $tok === '0') && $tok !== '..')
                {
                    $uris[] = $tok;
                }
                $tok = strtok('/');
            }

            return implode('/', $uris);
        };

        $parseRequestUri = function() use($removeRelativeDirectory)
        {
            $uri   = parse_url('http://dummy'.$_SERVER['REQUEST_URI']);
            $query = isset($uri['query']) ? $uri['query'] : '';
            $uri   = isset($uri['path']) ? $uri['path'] : '';

            if (isset($_SERVER['SCRIPT_NAME'][0]))
            {
                if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
                {
                    $uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
                }
                elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
                {
                    $uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
                }
            }

            if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0)
            {
                $query = explode('?', $query, 2);
                $uri   = $query[0];
                $_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
            }
            else
            {
                $_SERVER['QUERY_STRING'] = $query;
            }

            parse_str($_SERVER['QUERY_STRING'], $_GET);

            if ($uri === '/' OR $uri === '')
            {
                $uri = '/';
            }

            $uri = $removeRelativeDirectory($uri);

            return $uri;
        };

        if($uriProtocol == 'REQUEST_URI')
        {
            $url = $parseRequestUri();
        }
        elseif($uriProtocol == 'QUERY_STRING')
        {
            $uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');

            if (trim($uri, '/') === '')
            {
                $uri = '';
            }
            elseif (strncmp($uri, '/', 1) === 0)
            {
                $uri = explode('?', $uri, 2);
                $_SERVER['QUERY_STRING'] = isset($uri[1]) ? $uri[1] : '';
                $uri = $uri[0];
            }

            parse_str($_SERVER['QUERY_STRING'], $_GET);

            $url = $removeRelativeDirectory($uri);
        }
        elseif($uriProtocol == 'PATH_INFO')
        {
            $url = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO']) : $parseRequestUri();
        }
        else
        {
            show_error('Unsupported uri protocol', 500, 'OpenSID CI boot error');
        }

        if(empty($url))
        {
            $url = '/';
        }

        return $url;
    }

    /**
     * Recursive mkdir function
     * 
     * @deprecated Use mkdir('path', 0777, true) instead!
     * @param string[]  $folders Array with folders to be created
     * @param string    $base    Target base path
     * 
     * @return void
     */
    public static function rmkdir($folders, $base)
    {
        $target = APPPATH . $base;

        foreach($folders as $folder)
        {
            $target .= '/' . $folder;

            if(!file_exists($target))
            {
                mkdir($target);
            }
        }
    }

    /**
     * Recursive copy function
     * 
     * @param string $source
     * @param string $target
     * 
     * @return void
     */
    public static function rcopy($source, $target)
    {
        foreach(scandir($source) as $res)
        {
            if($res == '.' || $res == '..')
            {
                continue;
            }

            $_source = $source . '/' . $res;
            $_target = $target . '/' . $res;

            if(is_dir($_source))
            {
                if(!file_exists($_target))
                {
                    mkdir($_target);
                }

                self::rcopy($_source, $_target);
            }
            else
            {
                if(!file_exists($_target))
                {
                    copy($_source, $_target);
                    if(is_cli())
                    {
                        echo "CREATED: $_target\n";
                    }
                }
                else
                {
                    if(is_cli())
                    {
                        echo "SKIPPED: $_target (already exists)\n";
                    }
                }
            }
        }
    }
}