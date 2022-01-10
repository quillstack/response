<?php

declare(strict_types=1);

namespace QuillStack\Mocks\Response;

use QuillStack\Response\AbstractResponse;

final class SimpleResponse extends AbstractResponse
{
    /**
     * {@inheritDoc}
     */
    public function send(): array
    {
        return [
            'response' => 'simple',
        ];
    }
}
