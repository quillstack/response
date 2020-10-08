<?php

declare(strict_types=1);

namespace QuillStack\Mocks;

use Psr\Http\Message\ResponseInterface;
use QuillStack\DI\Container;
use QuillStack\Http\Response\Factory\ResponseFactory;
use QuillStack\Http\Response\Response;

abstract class AbstractMock
{
    private ResponseInterface $response;

    public function __construct()
    {
        $container = new Container();
        $factory = $container->get(ResponseFactory::class);
        $this->response = $factory->createResponse(Response::CODE_OK);
    }

    public function get()
    {
        return $this->response;
    }
}
