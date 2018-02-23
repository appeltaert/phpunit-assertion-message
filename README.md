PHPUnit Assertion Message [![Build Status](https://travis-ci.org/appeltaert/phpunit-assertion-message.svg?branch=master)](https://travis-ci.org/appeltaert/phpunit-assertion-message)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3f42fd40-e1dd-422c-bc84-3b8b4769ddc2/mini.png)](https://insight.sensiolabs.com/projects/3f42fd40-e1dd-422c-bc84-3b8b4769ddc2)
=======


```
composer require --dev appeltaert/phpunit-assertion-message
```

### Usage

1) Simply wrap whatever message you normally pass to assertions in a `new PAM(string $message, [mixed $context,...])` instance.
2) Run `phpunit` with **--debug**

`$context` can be basically be anything, for now there's only explicit support for Symfony `Response` and `Request` objects, but
as a final resort a var flattening processor will take over to basically make anything readable if no processor can handle your context.


#### Before

This will turn this occasional mess while debugging.

```php
$client->enableProfiler();
$response = $client->getResponse();

$this->assertTrue($response->isSuccessful(), "My message");
```
```
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

#### After

Into this.

```php
$client->enableProfiler();
$response = $client->getResponse();

$this->assertTrue($response->isSuccessful(), 
    new PAM("My message", [$response, $profiler->getCollector('request')]));
```
```
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

## How it works

### Processors

All context passed is handled by a chain of `Processors`. The first one to accept the context wins.
The processors are then printed by a `Printer`. The default printer `Plain` dumps everything into human-readable blocks.

**Symfony Response object**

```php
$response = $client->getResponse();
$this->assertTrue($response->isSuccessful(), new PAM("My message", [$response]));
```
```text
HTTP response:      Code: 500
                 Headers:  content-type: ["text\/html; charset=UTF-8"]
                          cache-control: ["no-cache, private"]
                                   date: ["Fri, 23 Feb 2018 21:12:32 GMT"]
                          x-debug-token: ["771d20"]
               Exception: qewr (500 Internal Server Error)
                   
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
                 SessionAttributes: key: val
                   SessionMetaData:   Created: Fri, 23 Feb 18 22:12:31 +0100
                                    Last used: Fri, 23 Feb 18 22:12:31 +0100
                                     Lifetime: 0
                            Action: AppBundle\Controller\BlogController::indexAction
```

**Array**
```php
$someArray = [];
$this->assertArrayHasKey("test", $array, new PAM("My message", [
    'qwerqwer' => 'qwerqewr', ['qwerqewr', 'qwerqwer']
]));
```
```
Array: qwerqwer: qwerqewr
              0: 0: qwerqewr
                 1: qwerqwer

```


### Config

#### Printer
At the moment there is only one printer, `Plain`.

#### Statically set all options
```php
PAM::setDefaults($processors, $printer, $env);
```

#### Overriding the environment
```
$env = new Env($debug = null, $verbose = null, $supportsColors = null);
PAM::setDefaults([], null, $env);
```

### roadmap 

#### v1.1

**processors**

- more symfony
- move them to separate suggested repos
- make them pluggable
 
**printer**

- distinctive dumps based on env, more info --verbose, less without
- detect interactive terminal(for colored output f.e.), also check ansi arguments.
- posix_isatty, XTERM check?
