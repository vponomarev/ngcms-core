<?php

//
// Configurable HTTP GET with timeout support
// (c) Vitaly Ponomarev, revision: 2014.12.13
//

class http_get
{
    public function get($url, $timeout = 3, $referer = 0)
    {
        $resp = $this->request('GET', $url, '', $timeout, $referer);
        if (!is_array($resp) || !$resp[0]) {
            return false;
        }

        return $resp[2];
    }

    // Split URL into host, port and path
    public function request($proto, $url, $params = '', $timeout = 5, $referer = 0)
    {
        // Open TCP connection
        if ((mb_strtolower($proto) != 'get') && (mb_strtolower($proto) != 'post')) {
            return false;
        }
        list($host, $port, $path) = self::parse_url($url);
        if (!$host) {
            return '';
        }
        if (!function_exists('fsockopen')) {
            return false;
        }

        $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return false;
        }

        // Set stream timeout
        stream_set_timeout($fp, $timeout);

        // Manage params
        $ext = '';
        $elist = [];
        if (is_array($params)) {
            foreach ($params as $k => $v) {
                array_push($elist, $k.'='.$v);
            }
            $ext = implode('&', $elist);
        } else {
            $ext = $params;
        }

        // Send header
        if (mb_strtolower($proto) == 'get') {
            fwrite(
                $fp,
                "GET /$path".(!empty($ext) ? ('?'.$ext) : '')." HTTP/1.1\r\n".
                "Host: $host\r\nConnection: close\r\n".
                ($referer ? ('Referer: http://'.$_SERVER['HTTP_HOST']."/\r\n") : '').
                'User-Agent: PHPfetcher class '.$this->getVersion()."(designed for: http://ngcms.ru/)\r\n".
                "\r\n"
            );
        } elseif (mb_strtolower($proto) == 'post') {
            fwrite(
                $fp,
                "POST /$path HTTP/1.1\r\n".
                "Host: $host\r\nConnection: close\r\n".
                'Content-length: '.mb_strlen($ext)."\r\n".
                "Content-Type: application/x-www-form-urlencoded\r\n".
                ($referer ? ('Referer: http://'.$_SERVER['HTTP_HOST']."/\r\n") : '').
                'User-Agent: PHPfetcher class '.$this->getVersion()." (designed for: http://ngcms.ru/)\r\n".
                "\r\n".
                $ext
            );
        }

        $fi = stream_get_meta_data($fp);

        // Try to read data, not more than 1 Mb
        $maxchunks = 128;
        $chunk = 0;
        $dsize = 0;
        $dmaxsize = 1024 * 1024;
        $data = '';
        while ((!feof($fp))) {
            $fi = stream_get_meta_data($fp);
            if ($fi['timed_out']) {
                break;
            }

            $in = fread($fp, 128 * 1024);

            $dsize += mb_strlen($in);
            $data .= $in;

            if (($chunk >= $maxchunks) || ($dsize >= $dmaxsize)) {
                break;
            }
            $chunk++;
        }
        fclose($fp);

        // Check if connection was closed due to timeout
        if ($fi['timed_out']) {
            return false;
        }

        // Try to parse data
        if ($pos = mb_strpos($data, "\r\n\r\n")) {
            $header = mb_substr($data, 0, $pos);
            $data = mb_substr($data, $pos + 4);
        } else {
            // HTTP header/body splitter not found. No body given
            $header = $data;
            $data = '';
        }

        // Let's analyse header
        $hdr = explode("\r\n", $header);
        $status = 0;
        if ($hdr[0] && preg_match('/^HTTP\/1.\d +(\d+) +(.+)$/i', $hdr[0], $match)) {
            // Found status string
            $status = $match[1];
        }

        // Return false if no status answer is found
        if (!$status) {
            return false;
        }

        // Return array with params:
        // <status> - 1 - ok, 0 - error
        // <header> - array with HTTP headers
        // <body>   - answer body
        return [($status == 200) ? 1 : 0, $hdr, $data];
    }

    public function parse_url($url)
    {
        $host = $path = '';
        $port = 80;
        if (preg_match('/^http\:\/\/(.+?)\/(.*)$/', $url, $match)) {
            $host = $match[1];
            $path = $match[2];
            if (preg_match('/^(.+?)\:(\d+)$/', $host, $match)) {
                $host = $match[1];
                $port = $match[2];
            }
        }

        return [$host, $port, $path];
    }

    public function getVersion()
    {
        return '20141213';
    }
}
