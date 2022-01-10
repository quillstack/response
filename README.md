# Quillstack Response

[![Build Status](https://app.travis-ci.com/quillstack/response.svg?branch=main)](https://app.travis-ci.com/quillstack/response)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=ncloc)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![StyleCI](https://github.styleci.io/repos/291464500/shield?branch=main)](https://github.styleci.io/repos/291464500?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/response/badge)](https://www.codefactor.io/repository/github/quillstack/response)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_response)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_response&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_response)

Quillstack Response is the response library which implements
_PSR-7: HTTP message interfaces_ and is based on
_PSR-17: HTTP Factories_.

### Unit tests

Run tests using a command:

```
phpdbg -qrr ./vendor/bin/unit-tests
```

### Docker

```shell
$ docker-compose up -d
$ docker exec -w /var/www/html -it quillstack_response sh
```
