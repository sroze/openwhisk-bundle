<?php

namespace Sam\Openwhisk\Bridge\Symfony;

use Symfony\Component\HttpFoundation\Request;

class RequestFactory
{
    public static function fromArgs(array $args) : Request
    {
        return Request::create(
            $args['__ow_path'] ?? '/',
            $args['__ow_method'] ?? 'get'
        );
    }
}
