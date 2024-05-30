<?php 
namespace App\Service;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    private $client;
    private $indexName;
    public function __construct()
    {
        // $this->client = ClientBuilder::create()->build();

        // $hosts = [$_ENV['ELASTICSEARCH_HOSTS']];
        $hosts = ['localhost:9200'];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();

        $this->indexName = 'pdf_file1';
    }

    
    public function indexDictionaryData(array $dictionaryData)
    {
        foreach ($dictionaryData as $data) {
            $params = [
                'index' => $this->indexName,
                'id' => $data['code'],
                'body' => $data
            ];

            $this->client->index($params);
        }
    }

  
    public function search(string $searchWord, int $quantityCodes)
    {
        $params = [
            'index' => $this->indexName,
            'body' => [
                'query' => [
                    'match' => [
                        'description' => $searchWord
                    ]
                ],
                'size' => $quantityCodes
            ]
        ];

        $results = $this->client->search($params);
        return array_map(fn($hit) => $hit['_source'], $results['hits']['hits']);
    }
}
