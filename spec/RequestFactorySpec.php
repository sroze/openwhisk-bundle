<?php

namespace spec\Sam\Openwhisk\Bridge\Symfony;

use Sam\Openwhisk\Bridge\Symfony\RequestFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class RequestFactorySpec extends ObjectBehavior
{
    function it_default_to_get_root()
    {
        $request = self::fromArgs([]);
        $request->shouldHavePath('/');
        $request->shouldHaveMethod('get');
    }

    function it_gets_the_path_and_methods_from_ow_envs()
    {
        $request = self::fromArgs([
            '__ow_path' => '/foo',
            '__ow_method' => 'post',
        ]);

        $request->shouldHavePath('/foo');
        $request->shouldHaveMethod('post');
    }

    function it_gets_the_request_headers()
    {
        $request = self::fromArgs([
            '__ow_path' => '/do-something',
            '__ow_headers' => [
                'x-request-id' => 'RD85g6K35fhB5T8vPcQlE0Cka5f4UMrk',
                'content-type' => 'application/json'
            ]
        ]);

        $request->shouldHaveTheHeader('x-request-id', 'RD85g6K35fhB5T8vPcQlE0Cka5f4UMrk');
    }

    function it_gets_the_cookies_from_the_request()
    {
        $request = self::fromArgs([
            '__ow_path' => '/do-something',
            '__ow_headers' => [
                'cookie' => 'ajs_anonymous_id=value; ajs_user_id=user-value; amplitude_idibmcloud.com=VALUE',
            ]
        ]);

        $request->shouldHaveTheHeader('cookie', 'ajs_anonymous_id=value; ajs_user_id=user-value; amplitude_idibmcloud.com=VALUE');
        $request->shouldHaveTheCookie('ajs_anonymous_id', 'value');
        $request->shouldHaveTheCookie('ajs_user_id', 'user-value');
        $request->shouldHaveTheCookie('amplitude_idibmcloud.com', 'VALUE');
    }

    function it_gets_the_json_args_as_body()
    {
        $request = self::fromArgs([
            '__ow_method' => 'post',
            '__ow_headers' => [
                'content-type' => 'application/json',
            ],
            'message' => 'FOO',
        ]);

        $request->shouldHaveTheHeader('content-type', 'application/json');
        $request->shouldHaveTheContent('{"message":"FOO"}');
    }

    public function getMatchers() : array
    {
        return [
            'haveTheHeader' => function(Request $subject, $headerName, $headerValue) {
                return $subject->headers->get($headerName) == $headerValue;
            },
            'haveTheCookie' => function(Request $subject, $cookieName, $cookieValue) {
                return $subject->cookies->get($cookieName) == $cookieValue;
            },
            'haveTheContent' => function(Request $subject, $content) {
                return $subject->getContent() == $content;
            },
            'havePath' => function(Request $subject, $path) {
                return $subject->getPathInfo() == $path;
            },
            'haveMethod' => function(Request $subject, $method) {
                return 0 == strcasecmp($subject->getMethod(), $method);
            },
        ];
    }
}
