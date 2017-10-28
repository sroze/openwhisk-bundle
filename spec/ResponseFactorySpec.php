<?php

namespace spec\Sam\Openwhisk\Bridge\Symfony;

use Sam\Openwhisk\Bridge\Symfony\ResponseFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactorySpec extends ObjectBehavior
{
    function it_returns_the_handers_and_body()
    {
        $response = self::fromHttpFoundationResponse(new Response('HELLO', 200, ['Content-Type' => 'unknown']));
        $response->shouldHaveKeyWithValue('statusCode', 200);
        $response->shouldHaveKeyWithValue('body', 'HELLO');
        $response->shouldHaveHeader('content-type', 'unknown');
    }

    function it_displays_the_exception_message()
    {
        $response = self::fromException(new \RuntimeException('Something went wrong'));
        $response->shouldHaveKeyWithValue('statusCode', 500);
        $response->shouldHaveKeyWithValue('body', 'Something went wrong');
    }

    public function getMatchers(): array
    {
        return [
            'haveHeader' => function($subject, $name, $value) {
                return isset($subject['headers'][$name]) && in_array($value, $subject['headers'][$name]);
            },
        ];
    }
}
