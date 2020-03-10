<?php

namespace ElasticSearch4Monolog\Handler;


use Elasticsearch\Client;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use ElasticSearch4Monolog\Formatter\ElasticsearchFormatter;
use InvalidArgumentException;

class ElasticsearchHandler extends AbstractProcessingHandler
{

    const INDEX_NAME = 'monolog';
    const TYPE_NAME  = 'type';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * ElasticsearchHandler constructor.
     * @param Client $client  ElasticSearch Client Object
     * @param array $options  Handler options
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(Client $client, array $options = [], $level = Logger::DEBUG, $bubble = true)
    {
        $this->client = $client;

        if(!isset($options['index']))
            $options['index'] = static::INDEX_NAME;

        if(!isset($options['type']))
            $options['type'] = static::TYPE_NAME;

        $this->options = $options;

        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        $this->client->index($record['formatted']);
    }

    /**
     * @param FormatterInterface $formatter
     * @return AbstractProcessingHandler|HandlerInterface
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        if($formatter instanceof ElasticsearchFormatter){
            return parent::setFormatter($formatter);
        }

        throw new InvalidArgumentException('ElasticsearchHandler is only compatible with ElasticsearchFormatter');
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return FormatterInterface|ElasticsearchFormatter
     */
    public function getDefaultFormatter()
    {
        return new ElasticsearchFormatter($this->options['index'], $this->options['type']);
    }

    /**
     * @param array $records
     */
    public function handleBatch(array $records)
    {
        $this->client->bulk(
            $this->getFormatter()->formatBatch($records)
        );
    }


}