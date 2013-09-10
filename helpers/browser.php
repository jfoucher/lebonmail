<?php

/**
 * Created by JetBrains PhpStorm.
 * User: jonathan
 * Date: 4/1/12
 * Time: 10:32 AM
 * To change this template use File | Settings | File Templates.
 */
class browser{
    public static function post($uri, $data, $format = null, $headers = null){
        $h = "";
        if(is_array($headers))
        {
            foreach ($headers as $k => $v) {
                $h .= $k.': '.$v."\r\n";
            }
        }else{
            $h = $headers;
        }


        $head = $h.'Content-Type: application/x-www-form-urlencoded'."\r\n";
        return self::request($uri, $data, 'POST', $format, $head);
    }


    public static function request($url, array $params = null, $verb = 'GET', $format = 'json', $optional_headers = null)
    {
        $cparams = array(
            'http' => array(
                'method' => $verb,
                'ignore_errors' => true
            )
        );
        if ($params !== null) {
            $params = http_build_query($params);
            if ($verb == 'POST') {
                $cparams['http']['content'] = $params;
            } else {
                $url .= '?' . $params;
            }
        }



        if ($optional_headers !== null) {

            $cparams['http']['header'] = $optional_headers;
        }

        $context = stream_context_create($cparams);
        if( ini_get('allow_url_fopen') ) {

            $fp = fopen($url, 'rb', false, $context);
            if (!$fp) {
                $res = false;
            } else {

                $res = stream_get_contents($fp);
            }

            if ($res === false) {
                throw new Exception("$verb $url failed: $php_errormsg");
            }
        } elseif  (in_array  ('curl', get_loaded_extensions())) {
            //use curl
            $c = curl_init ($url);
            curl_setopt ($c, CURLOPT_POST, true);
            curl_setopt ($c, CURLOPT_POSTFIELDS, $params);
            curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec ($c);
            curl_close ($c);
        }


        switch ($format) {
            case 'json':
                $r = json_decode($res);
                if ($r === null) {
                    throw new Exception("failed to decode $res as json :".print_r($res, true)."|");
                }
                return $r;

            case 'xml':
                $r = simplexml_load_string($res);
                if ($r === null) {
                    throw new Exception("failed to decode $res as xml");
                }
                return $r;
            default:
                return $res;
        }

    }

}