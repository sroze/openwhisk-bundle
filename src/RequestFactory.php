<?php

namespace Sam\Openwhisk\Bridge\Symfony;

use Symfony\Component\HttpFoundation\Request;

class RequestFactory
{
    public static function fromArgs(array $args) : Request
    {
        $path = $args['__ow_path'] ?? '/';
        $method = strtolower($args['__ow_method'] ?? 'get');

        $server = $_SERVER;
        foreach ($args['__ow_headers'] ?? [] ?: [] as $header => $value) {
            // We specifically create the `HTTP_CONTENT_TYPE` server variable, as it's used by
            // Symfony to identify the content type
            if (0 === strcasecmp($header, 'content-type')) {
                $server['HTTP_CONTENT_TYPE'] = $value;
            }

            $server['HTTP_'.$header] = $value;
        }

        // The POST-ed values are transformed into the `args` sent to the action front controller,
        // we get the parameters from the `args` not prefixed by `__ow_`
        $parameters = array_filter(
            $args,
            function ($key) {
                return strpos($key, '__ow_') === false;
            },
            ARRAY_FILTER_USE_KEY
        );

        // We can find cookies within the `Cookie` header. We'll parse them.
        $cookies = [];
        if (isset($args['__ow_headers']['cookie'])) {
            $cookies = RequestCookieParser::parseCookie($args['__ow_headers']['cookie']);
        }

        // We can't get the request content from the Openwhisk action,
        // just from the args. Based on the content-type, we'll reconstruct the
        // body for compatibility reasons
        $content = null;
        if ($method != 'get' && !empty($parameters)) {
            $contentType = strtolower($server['HTTP_CONTENT_TYPE'] ?? '');

            if ($contentType == 'application/json') {
                $content = json_encode($parameters);
            }
        }

        return Request::create(
            $path,
            $method,
            $parameters,
            $cookies,
            [], // $files
            $server,
            $content
        );
    }
}
