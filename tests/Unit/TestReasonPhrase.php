<?php

declare(strict_types=1);

namespace Quillstack\Response\Tests\Unit;

use Quillstack\Response\Tests\Mocks\AbstractTest;
use Quillstack\Response\Tests\Mocks\SimpleResponse;
use Quillstack\UnitTests\AssertEqual;

class TestReasonPhrase extends AbstractTest
{
    protected const NAME = SimpleResponse::class;

    public function __construct(private AssertEqual $assertEqual)
    {
        parent::__construct();
    }

    public function testDefaultReasonPhrase()
    {
        $this->assertEqual->equal(200, $this->response->getStatusCode());
        $this->assertEqual->equal('OK', $this->response->getReasonPhrase());
        $this->assertEqual->equal(['response' => 'simple'], $this->response->send());
        $this->assertEqual->equal('{"response":"simple"}', json_encode($this->response));
    }
}
