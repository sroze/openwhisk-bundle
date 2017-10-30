<?php

namespace Sam\Openwhisk\Bridge\Symfony;

class RequestCookieParser
{
    /**
     * Return the parsed cookie values (key/value pairs).
     *
     * @param string $cookieHeader
     *
     * @return array
     */
    public static function parseCookie(string $cookieHeader) : array
    {
        $cookies = [];

        foreach(explode('; ', $cookieHeader) as $k => $v){
            if (preg_match('/^(.*?)=(.*?)$/i', trim($v), $matches)) {
                $cookies[trim($matches[1])] = urldecode($matches[2]);
            }
        }

        return $cookies;
    }
}
