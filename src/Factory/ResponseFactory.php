<?php

declare(strict_types=1);

namespace Quillstack\Response\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\Response\Exceptions\UnknownResponseClassException;
use Quillstack\Response\Response;
use Quillstack\Response\Validators\ResponseCodeValidator;

class ResponseFactory implements ResponseFactoryInterface
{
    public ResponseCodeValidator $responseCodeValidator;
    private string $responseClass = Response::class;

    public function setResponseClass(string $responseClass): self
    {
        if (!class_exists($responseClass)) {
            throw new UnknownResponseClassException("Response class not known: {$responseClass}");
        }

        $this->responseClass = $responseClass;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $this->responseCodeValidator->setCode($code)->validate();
        $headers = new HeaderBag([]);

        return new $this->responseClass($code, $reasonPhrase, $headers);
    }
}
