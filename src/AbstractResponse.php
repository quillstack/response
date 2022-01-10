<?php

declare(strict_types=1);

namespace Quillstack\Response;

use JsonSerializable;
use Psr\Http\Message\StreamInterface;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\Response\Exceptions\UnableToFindReasonPhraseException;

abstract class AbstractResponse implements ResponseInterface, JsonSerializable
{
    /**
     * @var array
     */
    public const ALLOWED_CODES = [
        Response::CODE_OK,
        Response::CODE_INTERNAL_SERVER_ERROR,
    ];

    /**
     * @var array
     */
    public const CODE_TO_MESSAGE = [
        Response::CODE_OK => Response::MESSAGE_OK,
        Response::CODE_INTERNAL_SERVER_ERROR => Response::MESSAGE_INTERNAL_SERVER_ERROR,
    ];

    public function __construct(
        private int $code = 200,
        private string $reasonPhrase = '',
        private ?HeaderBag $headerBag = null,
        private string $protocolVersion = '',
        private ?StreamInterface $body = null
    ) {
        $this->reasonPhrase = $reasonPhrase !== '' ? $reasonPhrase : $this->findReasonPhrase();
    }

    abstract public function send(): array;

    private function findReasonPhrase(): string
    {
        if (!isset(self::CODE_TO_MESSAGE[$this->code])) {
            throw new UnableToFindReasonPhraseException();
        }

        return self::CODE_TO_MESSAGE[$this->code];
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders()
    {
        return $this->headerBag->getHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return $this->headerBag->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        return $this->headerBag->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        return $this->headerBag->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withAddedHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withoutHeader($name);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * {@inheritDoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->code = $code;
        $new->reasonPhrase = $reasonPhrase;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getReasonPhrase()
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
