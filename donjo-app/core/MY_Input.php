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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

class MY_Input extends CI_Input
{
    /**
     * Override ip_address function
     * 
	 * Fetch the IP Address
	 *
	 * Determines and validates the visitor's IP address.
	 *
	 * @return	string	IP address
	 */
	public function ip_address()
	{
        if ($this->ip_address !== FALSE)
        {
            return $this->ip_address;
        }

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $this->ip_address = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $proxy_ips = config_item('proxy_ips');
            if ( ! empty($proxy_ips) && ! is_array($proxy_ips))
            {
                $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
            }

            $this->ip_address = $this->server('REMOTE_ADDR');

            if ($proxy_ips)
            {
                foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header)
                {
                    if (($spoof = $this->server($header)) !== NULL)
                    {
                        // Some proxies typically list the whole chain of IP
                        // addresses through which the client has reached us.
                        // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                        sscanf($spoof, '%[^,]', $spoof);

                        if ( ! $this->valid_ip($spoof))
                        {
                            $spoof = NULL;
                        }
                        else
                        {
                            break;
                        }
                    }
                }

                if ($spoof)
                {
                    for ($i = 0, $c = count($proxy_ips); $i < $c; $i++)
                    {
                        // Check if we have an IP address or a subnet
                        if (strpos($proxy_ips[$i], '/') === FALSE)
                        {
                            // An IP address (and not a subnet) is specified.
                            // We can compare right away.
                            if ($proxy_ips[$i] === $this->ip_address)
                            {
                                $this->ip_address = $spoof;
                                break;
                            }

                            continue;
                        }

                        // We have a subnet ... now the heavy lifting begins
                        isset($separator) OR $separator = $this->valid_ip($this->ip_address, 'ipv6') ? ':' : '.';

                        // If the proxy entry doesn't match the IP protocol - skip it
                        if (strpos($proxy_ips[$i], $separator) === FALSE)
                        {
                            continue;
                        }

                        // Convert the REMOTE_ADDR IP address to binary, if needed
                        if ( ! isset($ip, $sprintf))
                        {
                            if ($separator === ':')
                            {
                                // Make sure we're have the "full" IPv6 format
                                $ip = explode(':',
                                    str_replace('::',
                                        str_repeat(':', 9 - substr_count($this->ip_address, ':')),
                                        $this->ip_address
                                    )
                                );

                                for ($j = 0; $j < 8; $j++)
                                {
                                    $ip[$j] = intval($ip[$j], 16);
                                }

                                $sprintf = '%016b%016b%016b%016b%016b%016b%016b%016b';
                            }
                            else
                            {
                                $ip = explode('.', $this->ip_address);
                                $sprintf = '%08b%08b%08b%08b';
                            }

                            $ip = vsprintf($sprintf, $ip);
                        }

                        // Split the netmask length off the network address
                        sscanf($proxy_ips[$i], '%[^/]/%d', $netaddr, $masklen);

                        // Again, an IPv6 address is most likely in a compressed form
                        if ($separator === ':')
                        {
                            $netaddr = explode(':', str_replace('::', str_repeat(':', 9 - substr_count($netaddr, ':')), $netaddr));
                            for ($j = 0; $j < 8; $j++)
                            {
                                $netaddr[$j] = intval($netaddr[$j], 16);
                            }
                        }
                        else
                        {
                            $netaddr = explode('.', $netaddr);
                        }

                        // Convert to binary and finally compare
                        if (strncmp($ip, vsprintf($sprintf, $netaddr), $masklen) === 0)
                        {
                            $this->ip_address = $spoof;
                            break;
                        }
                    }
                }
            }
        }

        if ( ! $this->valid_ip($this->ip_address))
        {
            return $this->ip_address = '0.0.0.0';
        }

        return $this->ip_address;
	}
}
