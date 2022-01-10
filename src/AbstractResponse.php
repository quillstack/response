<?php

declare(strict_types=1);

namespace QuillStack\Response;

use JsonSerializable;
use Psr\Http\Message\StreamInterface;
use QuillStack\Http\HeaderBag\HeaderBag;
use QuillStack\Response\Exceptions\UnableToFindReasonPhraseException;

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

    /**
     * @var int
     */
    private int $code;

    /**
     * @var string
     */
    private string $reasonPhrase;

    /**
     * @var HeaderBag|null
     */
    private ?HeaderBag $headerBag;

    /**
     * @var string
     */
    private string $protocolVersion;

    /**
     * @var StreamInterface|null
     */
    private ?StreamInterface $body;

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @param ?HeaderBag $headerBag
     * @param string $protocolVersion
     * @param StreamInterface|null $body
     */
    public function __construct(
        int $code = 200,
        string $reasonPhrase = '',
        HeaderBag $headerBag = null,
        string $protocolVersion = '',
        StreamInterface $body = null
    ) {
        $this->code = $code;
        $this->reasonPhrase = $reasonPhrase !== '' ? $reasonPhrase : $this->findReasonPhrase();
        $this->headerBag = $headerBag;
        $this->protocolVersion = $protocolVersion;
        $this->body = $body;
    }

    /**
     * @return array
     */
    abstract public function send(): array;

    /**
     * @return string
     */
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
     * @codeCoverageIgnore
     */
    public function getHeaders()
    {
        return $this->headerBag->getHeaders();
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function hasHeader($name)
    {
        return $this->headerBag->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function getHeader($name)
    {
        return $this->headerBag->getHeader($name);
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
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
    public function jsonSerialize()
    {
        return $this->send();
    }
}
