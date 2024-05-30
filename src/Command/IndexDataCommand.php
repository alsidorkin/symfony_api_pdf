<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\ElasticsearchService;
use App\Service\GetDictionaryService;

class IndexDataCommand extends Command
{
    protected static $defaultName = 'app:index-data';

    private $elasticsearchService;
    private $dictionaryService;

    public function __construct(ElasticsearchService $elasticsearchService, GetDictionaryService $dictionaryService)
    {
        parent::__construct();
        $this->elasticsearchService = $elasticsearchService;
        $this->dictionaryService = $dictionaryService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Indexes data from PDF into Elasticsearch.')
            ->setHelp('This command indexes data from PDF file into Elasticsearch.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Indexing data from PDF into Elasticsearch...');
        
        $this->dictionaryService->getDictionaryData();

        $output->writeln('Data indexing completed.');

        return Command::SUCCESS;
    }
}
