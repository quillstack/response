<?php

declare(strict_types=1);

namespace QuillStack\Response\Validators;

use QuillStack\Response\Exceptions\UnknownResponseCodeException;
use QuillStack\Response\AbstractResponse;
use QuillStack\ValidatorInterface;

final class ResponseCodeValidator implements ValidatorInterface
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
        if (!in_array($this->responseCode, AbstractResponse::ALLOWED_CODES, true)) {
            throw new UnknownResponseCodeException("Response code is not allowed: {$this->responseCode}");
        }

        return true;
    }
}
