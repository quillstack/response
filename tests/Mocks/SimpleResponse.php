<?php

declare(strict_types=1);

namespace Quillstack\Response\Tests\Mocks;

use Quillstack\Response\AbstractResponse;

class SimpleResponse extends AbstractResponse
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
