# Streamlike PHP Webservices SDK


## Requirements

PHP needs to be a minimum version of PHP 5.4.0.

## Installation

Download package and include streamlikeWs.php classe.

Or with composer:

`composer require streamlike/php-ws-sdk`

## Available services

- `playlists`
- `playlist`
- `media`
- `related`
- `qr`
- `rss`
- `languages`
- `countries`
- `getStreamlikeVersion`

## Samples

### Autoloading

```php
<?php

// via composer autoload
require './vendor/autoload.php';

// or basic require
require './src/streamlikeWs.php';


$ws = new streamlikeWs('https://cdn.streamlike.com', 'json');

```

### Get playlists list

```php
<?php

try {
    $params = array(
      'company_id' => '48c6eab371919246',
    );

    $content = $ws->getResult('playlists', $params, streamlikeWs::RESULTTYPE_RAW);

    var_dump($content);
} catch (\Exception $e) {
    // handle exception, log, retry...
}
```

### GET vote

```php
<?php

try {
    $ws = new streamlikeWs('https://cdn.streamlike.com', 'xml', streamlikeWs::VERSION_V2);
    $params = array(
      'company_id' => '48c6eab371919246',
      'media_id' => '4df5ede70f252c07',
      'value' => 3,
    );

    $content = $ws->setVote($params);
} catch (\Exception $e) {
    // handle exception, log, retry...
}

```

### GET Media list with many filters

Get json content about first 6 french media in playlist 983e6509573f4849 sorted by descending creation date:

```php
<?php

try {
    $ws = new streamlikeWs('https://cdn.streamlike.com', 'json');
    $params = array(
      'playlist_id' => '983e6509573f4849',
      'lng' => 'fr',
      'pagesize' => 6,
      'orderby' => 'date',
      'sortorder' => 'down'
    );

    $content = $ws->getResult('playlist', $params);
} catch (\Exception $e) {
    // handle exception, log, retry...
}
```

### GET Media with statistics

```php
<?php

try {
    $ws = new streamlikeWs('https://cdn.streamlike.com', 'xml');
    $params = array(
      'media_id' => '4df5ede70f252c07',
      'rate' => 'true',
    );

    $content = $ws->getResult('media', $params, streamlikeWs::RESULTTYPE_RAW);
} catch (\Exception $e) {
    // handle exception, log, retry...
}

```


### GET Media with statistics

```php
<?php

try {
    $ws = new streamlikeWs('https://cdn.streamlike.com', 'xml');
    $params = array(
      'media_id' => '4df5ede70f252c07',
      'rate' => 'true',
    );

    $content = $ws->getResult('media', $params, streamlikeWs::RESULTTYPE_RAW);
} catch (\Exception $e) {
    // handle exception, log, retry...
}

```

### GET Qr code picture

```php
<?php

try {
    $ws = new streamlikeWs('https://cdn.streamlike.com');
    $params = array(
      'media_id' => '4df5ede70f252c07',
    );

    $content = $ws->getResult('qr', $params);
} catch (\Exception $e) {
    // handle exception, log, retry...
}
