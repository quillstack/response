<?php

declare(strict_types=1);

namespace QuillStack\Http\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use QuillStack\Http\HeaderBag\HeaderBag;
use QuillStack\Http\Response\Exceptions\UnableToFindReasonPhraseException;

class Response implements ResponseInterface
{
    /**
     * @var int
     */
    public const CODE_OK = 200;

    /**
     * @var string
     */
    public const MESSAGE_OK = 'OK';

    /**
     * @var array
     */
    public const ALLOWED_CODES = [
        self::CODE_OK,
    ];

    public const CODE_TO_MESSAGE = [
        self::CODE_OK => self::MESSAGE_OK,
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
     * @codeCoverageIgnore
     */
    public function withHeader($name, $value)
    {
        return $this->headerBag->withoutHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function withAddedHeader($name, $value)
    {
        return $this->headerBag->withAddedHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function withoutHeader($name)
    {
        return $this->headerBag->withoutHeader($name);
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
}
