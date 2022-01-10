<?php

declare(strict_types=1);

namespace QuillStack\Response\Response;

use QuillStack\Mocks\AbstractTest;
use QuillStack\Mocks\Response\SimpleResponse;

final class ReasonPhraseTest extends AbstractTest
{
    protected const NAME = SimpleResponse::class;

    public function testDefaultReasonPhrase()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('OK', $this->response->getReasonPhrase());
        $this->assertEquals(['response' => 'simple'], $this->response->send());
        $this->assertEquals('{"response":"simple"}', json_encode($this->response));
    }
}
