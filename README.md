# Quillstack Response

[![Tests](https://github.com/quillstack/response/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/response/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/response.svg)](https://packagist.org/packages/quillstack/response)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/response.svg)](https://packagist.org/packages/quillstack/response)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/response)](https://packagist.org/packages/quillstack/response)
[![StyleCI](https://github.styleci.io/repos/291464500/shield?branch=main)](https://github.styleci.io/repos/291464500?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/response/badge)](https://www.codefactor.io/repository/github/quillstack/response)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![Security](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![License](https://img.shields.io/packagist/l/quillstack/response)](https://github.com/quillstack/response/blob/main/LICENSE)

The response object based on [PSR-7: Response](https://www.php-fig.org/psr/psr-7/). Full
documentation: https://quillstack.org/response

A response is written as a class: what it carries is one method, and the status is where the
class says it is. That way an endpoint's answer is a thing with a name rather than an array
assembled somewhere in a controller.

## Why this exists

A response in an API is nearly always the same shape: a status, a content type, and an object
turned into JSON. Every PSR-7 implementation makes you assemble that by hand each time, because
they are written for everything HTTP can carry rather than for the one thing an API sends.

So a response here is a class you write once and name — `UserResponse`, `NotFoundResponse` — and
`send()` says what it carries. The status code and its reason phrase come from a table checked
against RFC 9110, and **a status code this library has never heard of is refused rather than
answered with an empty phrase**, because in an application that is a typo rather than a
decision. A response arriving from somewhere else is the other case, and
[quillstack/http-client](https://github.com/quillstack/http-client) says so by overriding it.

## Requirements

- PHP 8.1 or newer

## Installation

```shell
composer require quillstack/response
```

## Usage

### A response of your own

Extend `Response` and say what it carries:

```php
use Quillstack\Response\Response;

final class UserResponse extends Response
{
    private string $id = '';

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function send(): array
    {
        return ['id' => $this->id];
    }
}
```

```php
$response = (new UserResponse())->setId('42');

$response->getStatusCode();     // 200
$response->getReasonPhrase();   // 'OK'
json_encode($response);         // {"id":"42"}
```

### Saying what happened

The status comes from the constructor, so a response which means something other than success
says so where it is defined rather than where it is used:

```php
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\Response\Response;
use Quillstack\Response\StatusCode;

final class NotFoundResponse extends Response
{
    public function __construct(?HeaderBag $headerBag = null)
    {
        parent::__construct(StatusCode::NOT_FOUND, '', $headerBag ?? new HeaderBag());
    }

    public function send(): array
    {
        return ['error' => ['status' => $this->getStatusCode(), 'message' => $this->getReasonPhrase()]];
    }
}
```

The reason phrase is found from the code, so `404` is `Not Found` without anybody writing it
down twice. Passing one explicitly overrides it.

### Headers

Every change hands back a copy, so the response you were given stays as it was:

```php
$response = (new UserResponse())
    ->withHeader('Content-Type', 'application/json')
    ->withAddedHeader('Set-Cookie', 'a=1');

$response->getHeaderLine('content-type');   // 'application/json'
```

### Building one from a factory

```php
$factory->setResponseClass(UserResponse::class);
$response = $factory->createResponse(StatusCode::CREATED);
```

## Technical documentation

`AbstractResponse` implements `Psr\Http\Message\ResponseInterface` and `JsonSerializable`;
`Response` is the class to extend, and `send()` is the one method to write.

| Method | Answers |
| --- | --- |
| `send(): array` | what this response carries — the one thing you write |
| `getStatusCode(): int` / `withStatus($code, $reasonPhrase = '')` | the status |
| `getReasonPhrase(): string` | found from the code where none was given |
| `getHeaders()`, `getHeader()`, `getHeaderLine()`, `hasHeader()` | headers, through [quillstack/header-bag](https://github.com/quillstack/header-bag) |
| `withHeader()`, `withAddedHeader()`, `withoutHeader()` | a copy with them changed |
| `getBody()` / `withBody()` | the body, as a PSR-7 stream |
| `getProtocolVersion()` / `withProtocolVersion()` | the HTTP version |

`StatusCode` names every status this package knows — 44 of them, from `CONTINUE` (100) to
`HTTP_VERSION_NOT_SUPPORTED` (505) — and `StatusCode::REASON_PHRASES` maps each to its
phrase.

| Exception | Thrown when |
| --- | --- |
| `UnknownResponseCodeException` | the status is not one of them |
| `UnableToFindReasonPhraseException` | there is no phrase for that code |
| `UnknownResponseClassException` | the factory is given a class which does not exist |

All extend `ResponseException`.

## Benchmark

Measured with [quillstack/benchmark](https://github.com/quillstack/benchmark) on one JSON
response — a status, a content type and a twenty-two byte body — built a thousand times. All
four produce the same status, phrase, header and body. Runs are interleaved, each figure is the
median of five, and PHP is 8.5.7.

| | Version |
| --- | --- |
| quillstack/response | v0.8.0 |
| nyholm/psr7 | 1.8.2 |
| laminas/laminas-diactoros | 3.8.0 |
| guzzlehttp/psr7 | 2.13.0 |

| | Per response | Relative |
| --- | --- | --- |
| **quillstack/response** | **2.86 µs** | — |
| nyholm/psr7 | 4.27 µs | 1.5× |
| laminas/laminas-diactoros | 7.79 µs | 2.7× |
| guzzlehttp/psr7 | 8.31 µs | 2.9× |

Most of that gap is the body: this one keeps a string as a string, where the others write it
into a `php://temp` resource — the same difference measured in
[quillstack/stream](https://github.com/quillstack/stream).

**What the numbers do not say:** all three of the others will carry any body PHP can open — a
socket, a compressed resource, a file handle — and construct from any of them. This is built for
the response an API sends, which is a status and some JSON.

## Tests

```shell
composer test
composer test:coverage
composer stan
```

## The rest of Quillstack

This is one component of [Quillstack](https://github.com/quillstack), a PHP framework which is
as simple to use as it is strict about what it does.

- [quillstack/serializer](https://github.com/quillstack/serializer) — what decides which fields go
- [quillstack/stream](https://github.com/quillstack/stream) — what carries the body
- [quillstack/header-bag](https://github.com/quillstack/header-bag) — the headers underneath
- [quillstack/framework](https://github.com/quillstack/framework) — where a response is answered with

## License

MIT. See [LICENSE](LICENSE).
