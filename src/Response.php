<?php

declare(strict_types=1);

namespace Quillstack\Response;

class Response extends AbstractResponse
{
    /**
     * @var int
     */
    public const CODE_OK = 200;

    /**
     * @var int
     */
    public const CODE_INTERNAL_SERVER_ERROR = 500;

    /**
     * @var string
     */
    public const MESSAGE_OK = 'OK';

    /**
     * @var string
     */
    public const MESSAGE_INTERNAL_SERVER_ERROR = 'Internal Server Error';

    /**
     * @return array
     */
    public function send(): array
    {
        return [];
    }
}
