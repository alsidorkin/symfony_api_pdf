<?php
namespace App\Service;

use SplObjectStorage;
use Smalot\PdfParser\Parser;
use App\Model\DictionaryItem;

class GetDictionaryService
{
    private $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    public function getDictionaryData(): SplObjectStorage
    {
    
        $storage = new SplObjectStorage();
        $pdf = $this->retrievePDFFile();
        $rawDictionaryData = $this->parsePDFFile($pdf);
      
         $this->elasticsearchService->indexDictionaryData($rawDictionaryData);

        foreach ($rawDictionaryData as $dictionaryRow) {
            $dictionaryItem = new DictionaryItem();
            $dictionaryItem->setCode($dictionaryRow["code"])->setDescription($dictionaryRow["description"]);
            $storage->attach($dictionaryItem);
        }

        return $storage;
    }

    protected function retrievePDFFile()
    {
        $url = 'https://mspu.gov.ua/storage/app/sites/17/%D0%BE%D0%B1%D0%BE%D1%80%D0%BE%D0%BD%D0%BD%D1%96%20%D0%B7%D0%B0%D0%BA%D1%83%D0%BF%D1%96%D0%B2%D0%BB%D1%96%20%D1%84%D0%B0%D0%B9%D0%BB%D0%B8/dk021-2015.pdf';
        $fileContent = file_get_contents($url);
        if ($fileContent === FALSE) {
            throw new \Exception("Не удалось скачать PDF файл.");
        }
        return $fileContent;
    }

    protected function parsePDFFile($pdf): array 
    {
        $parser = new Parser();      
        $pdf = $parser->parseContent($pdf);       
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
    
        return $tableData;
    }
}
