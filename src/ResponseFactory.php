<?php

namespace Sam\Openwhisk\Bridge\Symfony;

use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    public static function fromHttpFundationResponse(Response $response) : array
    {
        return [
            'headers' => $response->headers->all(),
            'body' => $response->getContent(),
        ];
    }

    public static function fromException(\Throwable $throwable) : array
    {
         return [
             'statusCode' => 500,
             'body' => $e->getMessage(),
         ];
    }
}

