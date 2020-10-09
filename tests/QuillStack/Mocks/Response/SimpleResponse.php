<?php

declare(strict_types=1);

namespace QuillStack\Mocks\Response;

use QuillStack\Http\Response\AbstractResponse;

final class SimpleResponse extends AbstractResponse
{
    public function send(): array
    {
        return [
            'response' => 'simple',
        ];
    }
}
