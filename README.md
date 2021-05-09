# json #

_JSON 1.0 Library for Web APIs_

This library was designed using the [JSON:API v1.0 STABLE](https://jsonapi.org/) specification.

### Getting Started ###

1. Add the following to your `composer.json` file:

```JavaScript
  "require": {
    "apsxj/json": "dev-main"
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/apsxj/json.git"
    }
  ]
```

2. Run `composer install`

3. Before calling any of the methods, require the vendor autoloader

```PHP
// For example, from the root directory...
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
```

4. To create a `Response` object and render it:

```PHP
<?php

// unwrap the Response class from the apsxj\json namespace
use apsxj\json\Response;

// Create a new response
$response = new Response();

// Add some data
$response->appendData(
  array(
    'method' => 'test',
    'result' => 'success'
  )
);

// Render the response
$response->render();
```

_Output should look something like this:_

```JavaScript
{
  "links": {
    "self": "https:\/\/localhost\/api\/test"
  },
  "meta": {
    "timestamp": 1620587659,
    "object": "dictionary",
    "count": 1
  },
  "data": [
    {
      "method": "test",
      "result": "success"
    }
  ]
}
```

4. To create an error response and render it:

```PHP
<?php

// unwrap the Response class from the apsxj\json namespace
use apsxj\json\Response;

// Create a new response
$response = new Response();

// Add the error
$response->appendError(
  404,
  'Not Found',
  'The requested resource was not found'
);

// Render the response
$response->render();
```

_Output should look something like this:_

```JavaScript
{
  "errors": [
    {
      "status": 404,
      "title": "Not Found",
      "detail": "The requested resource was not found"
    }
  ]
}
```
