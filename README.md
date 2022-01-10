# QuillStack Response

[![Build Status](https://travis-ci.org/quillstack/response.svg?branch=master)](https://travis-ci.org/quillstack/response)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=alert_status)](https://sonarcloud.io/dashboard?id=quillstack_response)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/response.svg)](https://packagist.org/packages/quillstack/response)
[![StyleCI](https://github.styleci.io/repos/291464500/shield?branch=master)](https://github.styleci.io/repos/291464500?branch=master)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/response/badge)](https://www.codefactor.io/repository/github/quillstack/response)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=quillstack_response)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=ncloc)](https://sonarcloud.io/dashboard?id=quillstack_response)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=coverage)](https://sonarcloud.io/dashboard?id=quillstack_response)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/quillstack/response)
![Packagist License](https://img.shields.io/packagist/l/quillstack/response)

Quillstack Response is the response library which implements
_PSR-7: HTTP message interfaces_ and is based on
_PSR-17: HTTP Factories_.

### Unit tests

Run tests using a command:

```
phpdbg -qrr vendor/bin/phpunit
```

Check the tests coverage:

```
phpdbg -qrr vendor/bin/phpunit --coverage-html coverage tests
```

### Docker

```shell
$ docker-compose up -d
$ docker exec -w /var/www/html -it quillstack_response sh
```
