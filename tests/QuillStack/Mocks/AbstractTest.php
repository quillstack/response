<?php

declare(strict_types=1);

namespace QuillStack\Mocks;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use QuillStack\DI\Container;
use QuillStack\Http\Response\Factory\ResponseFactory;
use QuillStack\Http\Response\AbstractResponse;

abstract class AbstractTest extends TestCase
{
    protected const NAME = '';

    protected ResponseInterface $response;

    public function setUp(): void
    {
        $container = new Container();
        $factory = $container->get(ResponseFactory::class);

        if (static::NAME) {
            $factory->setResponseClass(static::NAME);
        }

        $this->response = $factory->createResponse(AbstractResponse::CODE_OK);
    }
}
