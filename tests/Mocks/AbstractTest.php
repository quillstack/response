<?php

declare(strict_types=1);

namespace Quillstack\Response\Tests\Mocks;

use Psr\Http\Message\ResponseInterface;
use Quillstack\DI\Container;
use Quillstack\Response\Factory\ResponseFactory;
use Quillstack\Response\Response;

abstract class AbstractTest
{
    protected const NAME = '';
    protected ResponseInterface $response;

    public function __construct()
    {
        $container = new Container();
        $factory = $container->get(ResponseFactory::class);

        if (static::NAME) {
            $factory->setResponseClass(static::NAME);
        }

        $this->response = $factory->createResponse(Response::CODE_OK);
    }
}
