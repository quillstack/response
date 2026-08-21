<?php

declare(strict_types=1);

namespace Quillstack\Response\Tests\Unit;

use Quillstack\HeaderBag\HeaderBag;
use Quillstack\Response\Exceptions\UnknownResponseClassException;
use Quillstack\Response\Exceptions\UnknownResponseCodeException;
use Quillstack\Response\Factory\ResponseFactory;
use Quillstack\Response\Response;
use Quillstack\Response\Stream\EmptyStream;
use Quillstack\Response\Tests\Mocks\SimpleResponse;
use Quillstack\Response\Validators\ResponseCodeValidator;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\UnitTests\Types\AssertObject;

/**
 * The PSR-7 message a response is, which nothing used to read past its status.
 */
class TestMessage
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean,
        private AssertObject $assertObject,
        private AssertExceptions $assertExceptions
    ) {
        //
    }

    private function response(): Response
    {
        return new Response(200, '', new HeaderBag(['Content-Type' => 'text/json']));
    }

    public function theProtocolVersionIsCarried()
    {
        $response = new Response(200, '', new HeaderBag(), '1.1');

        $this->assertEqual->equal('1.1', $response->getProtocolVersion());
        $this->assertEqual->equal('2', $response->withProtocolVersion('2')->getProtocolVersion());

        // Immutable: the one it was called on is unchanged.
        $this->assertEqual->equal('1.1', $response->getProtocolVersion());
    }

    public function headersAreReadThroughTheResponse()
    {
        $response = $this->response();

        $this->assertEqual->equal(['Content-Type' => ['text/json']], $response->getHeaders());
        $this->assertEqual->equal(['text/json'], $response->getHeader('Content-Type'));
        $this->assertEqual->equal('text/json', $response->getHeaderLine('Content-Type'));
        $this->assertBoolean->isTrue($response->hasHeader('content-type'));
        $this->assertBoolean->isFalse($response->hasHeader('X-Nothing'));
        $this->assertEqual->equal([], $response->getHeader('X-Nothing'));
    }

    public function headersAreChangedWithoutTouchingTheOriginal()
    {
        $response = $this->response();

        $withHeader = $response->withHeader('X-Trace', 'abc');
        $withAdded = $withHeader->withAddedHeader('X-Trace', 'def');
        $without = $withHeader->withoutHeader('X-Trace');

        $this->assertEqual->equal(['abc'], $withHeader->getHeader('X-Trace'));
        $this->assertEqual->equal(['abc', 'def'], $withAdded->getHeader('X-Trace'));
        $this->assertEqual->equal([], $without->getHeader('X-Trace'));
        $this->assertEqual->equal([], $response->getHeader('X-Trace'));
    }

    /**
     * PSR-7 promises a stream, and a response built without one has to answer with an empty
     * one rather than null.
     */
    public function aResponseWithoutABodyStillHasOne()
    {
        $body = $this->response()->getBody();

        $this->assertObject->instanceOf(EmptyStream::class, $body);
        $this->assertEqual->equal('', (string) $body);
        $this->assertEqual->equal(0, $body->getSize());
        $this->assertEqual->equal(0, $body->tell());
        $this->assertBoolean->isTrue($body->eof());
        $this->assertBoolean->isFalse($body->isSeekable());
        $this->assertBoolean->isFalse($body->isWritable());
        $this->assertBoolean->isTrue($body->isReadable());
        $this->assertEqual->equal('', $body->read(10));
        $this->assertEqual->equal('', $body->getContents());
        $this->assertEqual->equal([], $body->getMetadata());
    }

    public function anEmptyBodyCannotBeSeekedOrWritten()
    {
        $body = new EmptyStream();
        $body->close();
        $body->rewind();

        $this->assertEqual->equal(null, $body->detach());
        $this->assertEqual->equal(null, $body->getMetadata('anything'));

        $this->assertExceptions->expect(\RuntimeException::class);
        $body->write('nope');
    }

    public function aBodyGivenIsHandedBack()
    {
        $body = new EmptyStream();
        $response = (new Response(200, '', new HeaderBag()))->withBody($body);

        $this->assertObject->instanceOf(EmptyStream::class, $response->getBody());
    }

    public function theStatusIsChangedWithoutTouchingTheOriginal()
    {
        $response = $this->response();
        $notFound = $response->withStatus(404);

        $this->assertEqual->equal(404, $notFound->getStatusCode());
        $this->assertEqual->equal(200, $response->getStatusCode());
    }

    public function theFactoryBuildsTheClassItWasTold()
    {
        $factory = new ResponseFactory(new ResponseCodeValidator());
        $response = $factory->setResponseClass(SimpleResponse::class)->createResponse(201);

        $this->assertObject->instanceOf(SimpleResponse::class, $response);
        $this->assertEqual->equal(201, $response->getStatusCode());
        $this->assertEqual->equal('Created', $response->getReasonPhrase());
    }

    public function theFactoryRefusesAStatusNobodyKnows()
    {
        $this->assertExceptions->expect(UnknownResponseCodeException::class);

        (new ResponseFactory(new ResponseCodeValidator()))->createResponse(799);
    }

    public function anEmptyBodyCannotBeSeeked()
    {
        $this->assertExceptions->expect(\RuntimeException::class);

        (new EmptyStream())->seek(0);
    }

    public function theFactoryRefusesAClassNobodyKnows()
    {
        $this->assertExceptions->expect(UnknownResponseClassException::class);

        (new ResponseFactory(new ResponseCodeValidator()))->setResponseClass('Nothing\\Here');
    }
}
