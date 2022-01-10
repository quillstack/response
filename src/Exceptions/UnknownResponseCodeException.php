<?php

declare(strict_types=1);

namespace Quillstack\Response\Exceptions;

use Quillstack\ValidatorInterface\ValidationExceptionInterface;

class UnknownResponseCodeException extends ResponseException implements ValidationExceptionInterface
{
    //
}
