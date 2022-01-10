<?php

declare(strict_types=1);

namespace QuillStack\Response\Exceptions;

use QuillStack\ValidationException;

class UnknownResponseCodeException extends ResponseException implements ValidationException
{
}
