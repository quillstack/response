<?php

declare(strict_types=1);

namespace QuillStack\Http\Response\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use QuillStack\Http\Response\Response;
use QuillStack\Http\Response\Validators\ResponseCodeValidator;

final class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var ResponseCodeValidator
     */
    private ResponseCodeValidator $responseCodeValidator;

    /**
     * {@inheritDoc}
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $this->responseCodeValidator->setCode($code)->validate();

        return new Response($code, $reasonPhrase);
    }
}
