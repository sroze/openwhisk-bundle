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
        self::fromArgs([])->shouldBeLike(Request::create('/', 'get'));
    }

    function it_gets_the_path_and_methods_from_ow_envs()
    {
        self::fromArgs([
            '__ow_path' => '/foo',
            '__ow_method' => 'post',
        ])->shouldBeLike(Request::create('/foo', 'post'));

    }
}
