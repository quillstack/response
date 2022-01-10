<?php

declare(strict_types=1);

namespace Quillstack\Response\Validators;

use Quillstack\Response\Exceptions\UnknownResponseCodeException;
use Quillstack\Response\AbstractResponse;
use Quillstack\ValidatorInterface\ValidatorInterface;

class ResponseCodeValidator implements ValidatorInterface
{
    private int $responseCode;

    public function setCode(int $responseCode): self
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
