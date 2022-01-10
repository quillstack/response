<?php

declare(strict_types=1);

namespace QuillStack\Response\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use QuillStack\Http\HeaderBag\HeaderBag;
use QuillStack\Response\Exceptions\UnknownResponseClassException;
use QuillStack\Response\Response;
use QuillStack\Response\Validators\ResponseCodeValidator;

final class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var ResponseCodeValidator
     */
    public ResponseCodeValidator $responseCodeValidator;

    /**
     * @var string
     */
    private string $responseClass = Response::class;

    /**
     * @param string $responseClass
     *
     * @return $this
     */
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
