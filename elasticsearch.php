<?php

require 'vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder;
use Smalot\PdfParser\Parser;

$hosts = [
    'http://localhost:9200'  
];

$client = ClientBuilder::create()->setHosts($hosts)->build();

// $params = [
//     'index' => 'pdf_file' 
// ];

// $response = $client->indices()->delete($params);

// echo "Индекс удален!\n";



// $params = [
//     'index' => 'pdf_file'
// ];

// $response = $client->indices()->create($params);

// echo " индекс  создан!\n";


$url = 'https://mspu.gov.ua/storage/app/sites/17/%D0%BE%D0%B1%D0%BE%D1%80%D0%BE%D0%BD%D0%BD%D1%96%20%D0%B7%D0%B0%D0%BA%D1%83%D0%BF%D1%96%D0%B2%D0%BB%D1%96%20%D1%84%D0%B0%D0%B9%D0%BB%D0%B8/dk021-2015.pdf';
$fileContent = file_get_contents($url);

$parser = new Parser();      
$pdf = $parser->parseContent($fileContent);       
$text = $pdf->getText();   

$pattern = '/(\d{8}-\d{1})\s+((?:(?!\d{8}-\d{1}|ДК 021:2015).)*)/us';
$tableData = [];

if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        $code = $match[1];
        $description = trim(preg_replace('/\s+/', ' ', $match[2]));
        $tableData[] = [
            'code' => $code,
            'description' => $description,
        ];
    }
}

// foreach ($tableData as $data) {
//     $params = [
//         'index' => 'pdf_file',
//         'id' => $data['code'],
//         'body' => $data
//     ];

//     $response = $client->index($params);
    // echo "{$data['code']}:{$data['description']} \n";
// }
$searchWord='М’ясо';
$quantityCodes=5;
$params = [
    'index' => 'pdf_file',
    'body' => [
        'query' => [
            'match' => [
                'description' => $searchWord
            ]
        ],
        'size' => $quantityCodes
    ]
];

$results = $client->search($params);
print_r(array_map(fn($hit) => $hit['_source'], $results['hits']['hits']));






