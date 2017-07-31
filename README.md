PHPUnit Assertion Message [![Build Status](https://travis-ci.org/appeltaert/phpunit-assertion-message.svg?branch=master)](https://travis-ci.org/appeltaert/phpunit-assertion-message)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3f42fd40-e1dd-422c-bc84-3b8b4769ddc2/mini.png)](https://insight.sensiolabs.com/projects/3f42fd40-e1dd-422c-bc84-3b8b4769ddc2)
=======


## Usage
```
composer require --dev appeltaert/phpunit-assertion-message
```

### Processors

**symfony response**
```php
$response = $client->getResponse();
$this->assertTrue($response->isSuccessful(), new PAM("My message", [$response]));
```
**array response**
```php
$someArray = [];
$this->assertArrayHasKey("test", $array, new PAM("My message", [$response]));
```
**any other var**
```php
$this->assertInstanceOf($someVar, \SomeObject::class, new PAM("My message", [$someVar]));
```

### Config

**printer**

- The default printer(Appeltaert\PAM\Printer\Plain) can be configured for whitespace, max depth and style. 
- Changing the defaults before every test

```php
PAM::setDefaults($processors, $printer, $env);
```
- ...




---
### roadmap v1.1

**processors**

- symfony data collectors
- distinctive request info based on env debug vs verbose
 
**printer**

- detect interactive terminal(for colored output f.e.), also check ansi arguments.
- posix_isatty, XTERM check?
- even possible without too much overhead? cant sacrifise performance for some flowers
