<?php

declare(strict_types=1);

namespace Quillstack\Response;

use JsonSerializable;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\Response\Exceptions\UnableToFindReasonPhraseException;
use Quillstack\Stream\EmptyStream;

abstract class AbstractResponse implements ResponseInterface, JsonSerializable
{
    /**
     * Only 200 and 500 used to be allowed, which left a not-found response no code to
     * answer with. Every status this library knows a reason phrase for is allowed.
     *
     * @var array<int, string>
     */
    public const CODE_TO_MESSAGE = StatusCode::REASON_PHRASES;

    private HeaderBag $headerBag;

    public function __construct(
        private int $code = StatusCode::OK,
        private string $reasonPhrase = '',
        ?HeaderBag $headerBag = null,
        private string $protocolVersion = '',
        private ?StreamInterface $body = null
    ) {
        // A response built without one used to be a single header call away from a fatal
        // error, and the factory had to pass an empty bag to every one it made.
        $this->headerBag = $headerBag ?? new HeaderBag();
        $this->reasonPhrase = $reasonPhrase !== '' ? $reasonPhrase : $this->findReasonPhrase();
    }

    /**
     * What the response carries, ready to be encoded.
     *
     * @return array<string, mixed>
     */
    abstract public function send(): array;

    /**
     * The phrase that goes with the code, where none was given.
     *
     * A status code this library has never heard of is a typo in an application which wrote
     * it, which is worth refusing outright. It is not worth refusing in a response which
     * arrived from somewhere else, and that is what a client overrides this for.
     */
    protected function findReasonPhrase(): string
    {
        if (!StatusCode::isKnown($this->code)) {
            throw new UnableToFindReasonPhraseException("Unknown status code: {$this->code}");
        }

        return StatusCode::reasonPhrase($this->code);
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders(): array
    {
        return $this->headerBag->getHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name): bool
    {
        return $this->headerBag->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name): array
    {
        return $this->headerBag->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name): string
    {
        return $this->headerBag->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withAddedHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name): MessageInterface
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withoutHeader($name);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getBody(): StreamInterface
    {
        return $this->body ?? new EmptyStream();
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode(): int
    {
        return $this->code;
    }

    /**
     * {@inheritDoc}
     */
    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->code = $code;
        $new->reasonPhrase = $reasonPhrase;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): mixed
    {
        return $this->send();
    }
}
