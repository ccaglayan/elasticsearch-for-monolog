# Elasticsearch For Monolog

A Monolog handler and formatter that makes use of the elasticsearch/elasticsearch package

#### Composer require command
`composer require ccaglayan/elasticsearch-for-monolog`

## Usage

It is fairly easy to use. I'll throw in an example.

```php
use Elasticsearch\ClientBuilder;
use Monolog\Logger;

    $config = ['127.0.0.1:9200'];
    $client = ClientBuilder::create()->setHosts($config)->build();
    $options = [
        'index' => 'logs_monolog',
        'type' => 'logs_doc'
    ];
    $handler = new \ElasticSearch4Monolog\Handler\ElasticsearchHandler($client, $options);
    $logger = new Logger('monologElastic');
    $logger->pushHandler($handler);
```

## Contributing
Pull requests and issues are open!

## License
Elasticsearch For Monolog handler is released under the MIT License. See the bundled LICENSE file for details.