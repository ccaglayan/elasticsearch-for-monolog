<?php

namespace ElasticSearch4Monolog\Formatter;

use DateTime;
use Monolog\Formatter\NormalizerFormatter;

class ElasticsearchFormatter extends NormalizerFormatter
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $index;

    /**
     * ElasticsearchFormatter constructor.
     * @param string $index
     * @param string $type
     */
    public function __construct($index, $type)
    {
        parent::__construct(DateTime::ISO8601);
        $this->index = $index;
        $this->type = $type;
    }

    /**
     * @param array $record
     * @return array
     */
    public function format(array $record)
    {
        $record = parent::format($record);

        $format = [
            'type'      => $this->type,
            'index'     => $this->index,
            'body'      => $record
        ];

        return $format;
    }

    /**
     * @param array $records
     * @return array
     */
    public function formatBatch(array $records)
    {
        $bulk = ['body' => []];

        foreach ($records as $record) {
            $bulk['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_type'  => $this->type,
                ]
            ];

            $bulk['body'][] = parent::format($record);
        }

        return $bulk;
    }
}