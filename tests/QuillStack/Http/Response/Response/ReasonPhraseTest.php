<?php

declare(strict_types=1);

namespace QuillStack\Http\Response\Response;

use PHPUnit\Framework\TestCase;
use QuillStack\Mocks\Response\SimpleResponse;

final class ReasonPhraseTest extends TestCase
{
    public function testDefaultReasonPhrase()
    {
        $response = (new SimpleResponse())->get();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }
}
