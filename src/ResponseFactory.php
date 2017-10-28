<?php

namespace Sam\Openwhisk\Bridge\Symfony;

use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    public static function fromHttpFoundationResponse(Response $response) : array
    {
        return [
            'statusCode' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'body' => $response->getContent(),
        ];
    }

    public static function fromException(\Throwable $throwable) : array
    {
         return [
             'statusCode' => 500,
             'body' => $throwable->getMessage(),
         ];
    }
}

