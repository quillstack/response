<?php

declare(strict_types=1);

namespace Quillstack\Response\Tests\Unit;

use Quillstack\HeaderBag\HeaderBag;
use Quillstack\Response\Exceptions\UnableToFindReasonPhraseException;
use Quillstack\Response\Response;
use Quillstack\Response\StatusCode;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;
use Quillstack\UnitTests\Types\AssertBoolean;

/**
 * Only 200 and 500 used to be allowed, so a response could not say it had not found
 * anything, and every not-found answer went out as a success.
 */
class TestStatusCode
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean,
        private AssertExceptions $assertExceptions
    ) {
        //
    }

    public function aResponseCanCarryAnyKnownStatus()
    {
        foreach ([404, 422, 201, 204, 401, 403, 429, 503] as $code) {
            $response = new Response($code, '', new HeaderBag());

            $this->assertEqual->equal($code, $response->getStatusCode());
            $this->assertEqual->equal(StatusCode::reasonPhrase($code), $response->getReasonPhrase());
        }
    }

    public function theReasonPhraseComesFromTheCode()
    {
        $this->assertEqual->equal('Not Found', (new Response(404, '', new HeaderBag()))->getReasonPhrase());
        $this->assertEqual->equal('Unprocessable Content', StatusCode::reasonPhrase(422));
        $this->assertEqual->equal('', StatusCode::reasonPhrase(799));
    }

    public function aGivenReasonPhraseIsKept()
    {
        $this->assertEqual->equal(
            'Nothing here',
            (new Response(404, 'Nothing here', new HeaderBag()))->getReasonPhrase()
        );
    }

    public function anUnknownStatusIsReported()
    {
        $this->assertExceptions->expect(UnableToFindReasonPhraseException::class);

        new Response(799, '', new HeaderBag());
    }

    public function knownStatusesAreRecognised()
    {
        $this->assertBoolean->isTrue(StatusCode::isKnown(404));
        $this->assertBoolean->isFalse(StatusCode::isKnown(799));
    }
}
