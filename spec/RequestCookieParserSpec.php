<?php

namespace spec\Sam\Openwhisk\Bridge\Symfony;

use Sam\Openwhisk\Bridge\Symfony\RequestCookieParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestCookieParserSpec extends ObjectBehavior
{
    function it_parse_a_simple_cookie()
    {
        self::parseCookie('foo=bar')->shouldBeLike([
            'foo' => 'bar',
        ]);
    }

    function it_parse_multiple_cookies()
    {
        self::parseCookie('ajs_anonymous_id=value; ajs_user_id=user-value; amplitude_idibmcloud.com=VALUE')->shouldBeLike([
            'ajs_anonymous_id' => 'value',
            'ajs_user_id' => 'user-value',
            'amplitude_idibmcloud.com' => 'VALUE'
        ]);
    }
}
