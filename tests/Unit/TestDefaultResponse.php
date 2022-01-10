<?php

declare(strict_types=1);

namespace Quillstack\Response\Tests\Unit;

use Quillstack\Response\Tests\Mocks\AbstractTest;
use Quillstack\UnitTests\AssertEqual;

class TestDefaultResponse extends AbstractTest
{
    public function __construct(private AssertEqual $assertEqual)
    {
        parent::__construct();
    }

    public function testDefaultReasonPhrase()
    {
        $this->assertEqual->equal(200, $this->response->getStatusCode());
        $this->assertEqual->equal('OK', $this->response->getReasonPhrase());
        $this->assertEqual->equal([], $this->response->send());
        $this->assertEqual->equal('[]', json_encode($this->response));
    }
}
