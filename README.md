PHPUnit Assertion Message [![Build Status](https://travis-ci.org/appeltaert/phpunit-assertion-message.svg?branch=master)](https://travis-ci.org/appeltaert/phpunit-assertion-message)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3f42fd40-e1dd-422c-bc84-3b8b4769ddc2/mini.png)](https://insight.sensiolabs.com/projects/3f42fd40-e1dd-422c-bc84-3b8b4769ddc2)
=======


```
composer require --dev appeltaert/phpunit-assertion-message
```

### Usage

1) Simply wrap whatever message you have in a `new PAM($message, [$context, ...])` instance.
2) Run `phpunit` with **--debug**

This will turn this occasional mess while debugging:

```php
$client->enableProfiler();
$response = $client->getResponse();

$this->assertTrue($response->isSuccessful(), "My message");

There was 1 failure:
    
1) Tests\AppBundle\Controller\DefaultControllerTest::testIndex
My message

// wtf is going on
// $this->assertTrue($client->getResponse()->isSuccessful())
// $collector = $profiler->getCollector()..
// var_dump($collector->...);
// echo $response->getContent();
// var_dump($response->headers->all();
// die.. die.. die..

```

Into this:

```php
$client->enableProfiler();
$response = $client->getResponse();

$this->assertTrue($response->isSuccessful(), 
    new PAM("My message", [$response, $profiler->getCollector('request')]));

There was 1 failure:

1) Tests\AppBundle\Controller\DefaultControllerTest::testIndex
My message

HTTP response:         Code: 200
               Content-type: text/html; charset=UTF-8
                    Cookies: 0: qwer=qewrqwer; path=/; httponly
                    
Request profile:      Format: html
                  StatusText: OK
                       Route: app_router_index
                  StatusCode: 200
                 ContentType: text/html; charset=UTF-8
                    PathInfo: /router/

```

## Docs

### Processors

All context passed is handled by a chain of `Processors`. The first one to accept the context wins.
The processors are then printed by a `Printer`. The default printer `Plain` dumps everything into human-readable blocks.

**Symfony Response object**

```php
$response = $client->getResponse();
$this->assertTrue($response->isSuccessful(), new PAM("My message", [$response]));
```
```text
HTTP response:         Code: 200
               Content-type: text/html; charset=UTF-8
                    Cookies: 0: qwer=qewrqwer; path=/; httponly
                  Exception: Exception message (500 Internal Server Error)                    
```
`--verbose`
```text
HTTP response:         Code: 200
                    Headers:  content-type: ["text\/html; charset=UTF-8"]
                             cache-control: ["no-cache, private"]
                                      date: ["Sun, 26 Nov 2017 16:34:43 GMT"]
                  Exception: Exception message (500 Internal Server Error)                    

```

**Symfony request profiler**

```php
$this->assertSame(
    Response::HTTP_OK,
    $client->getResponse()->getStatusCode(),
    new PAM(sprintf('The %s public URL loads correctly.', $url), [$client->getProfile()->getCollector('request')])
);
```

```text
Request profile:            Format: html
                             Route: blog_index
                        StatusText: Internal Server Error
                        StatusCode: 500
                       ContentType: text/html; charset=UTF-8
                          PathInfo: /en/blog/
                            Method: GET
                            Locale: en
                       RouteParams:    page: 1
                                    _format: html
                                    _locale: en
                        Controller: array (count=4)
                          Redirect: 
                 SessionAttributes: Key: Value
                   SessionMetaData:   Created: Sun, 26 Nov 17 19:33:03 +0100
                                    Last used: Sun, 26 Nov 17 19:33:03 +0100
                                     Lifetime: 0

```

**Array**
```php
$someArray = [];
$this->assertArrayHasKey("test", $array, new PAM("My message", [$response]));
```
@todo result

**Any other var**
```php
$this->assertInstanceOf($someVar, \SomeObject::class, new PAM("My message", [$someVar]));
```
@todo result


### Config

**Printer @todo**

- The default printer `Appeltaert\PAM\Printer\Plain` can be configured for whitespace, max depth and style. 
- Changing the defaults before every test

instructions @todo

```php
PAM::setDefaults($processors, $printer, $env);
```
- ... @todo






---
### roadmap v1.1

**processors**

- symfony data collectors
- distinctive request info based on env debug vs verbose
 
**printer**

- detect interactive terminal(for colored output f.e.), also check ansi arguments.
- posix_isatty, XTERM check?
- even possible without too much overhead? cant sacrifise performance for some flowers
