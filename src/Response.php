<?php

declare(strict_types=1);

namespace Quillstack\Response;

class Response extends AbstractResponse
{
    public const CODE_OK = StatusCode::OK;
    public const CODE_INTERNAL_SERVER_ERROR = StatusCode::INTERNAL_SERVER_ERROR;
    public const MESSAGE_OK = 'OK';
    public const MESSAGE_INTERNAL_SERVER_ERROR = 'Internal Server Error';

    /**
     * @return array
     */
    public function send(): array
    {
        return [];
    }
}
