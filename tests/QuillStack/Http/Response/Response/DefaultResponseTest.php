<?php

declare(strict_types=1);

namespace QuillStack\Response\Response;

use QuillStack\Mocks\AbstractTest;

final class DefaultResponseTest extends AbstractTest
{
    public function testDefaultReasonPhrase()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('OK', $this->response->getReasonPhrase());
        $this->assertEquals([], $this->response->send());
        $this->assertEquals('[]', json_encode($this->response));
    }
}
