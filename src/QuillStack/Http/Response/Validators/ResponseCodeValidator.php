<?php

declare(strict_types=1);

namespace QuillStack\Http\Response\Validators;

use QuillStack\Http\Response\Exceptions\ResponseException;
use QuillStack\Http\Response\Exceptions\UnknownResponseCodeException;
use QuillStack\Http\Response\Response;
use QuillStack\ValidatorInterface;

final class ResponseCodeValidator extends ResponseException implements ValidatorInterface
{
    /**
     * @var int
     */
    private int $responseCode;

    /**
     * @param int $responseCode
     *
     * @return $this
     */
    public function setCode(int $responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        if (!in_array($this->responseCode, Response::ALLOWED_CODES, true)) {
            throw new UnknownResponseCodeException("Response code is not allowed: {$this->responseCode}");
        }

        return true;
    }
}
