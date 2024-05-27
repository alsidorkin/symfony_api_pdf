<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\GetDictionaryService;

#[AsCommand(
    name: 'parse:pdf',
    description: 'This command parse pdf file',
    hidden: false,
    aliases: ['aparse:pdf']
)]

class ParserCommand extends Command
{
    protected static $defaultName = 'parse:pdf';
    protected static $defaultDescription = 'This command parse pdf file';

    private $getDictionaryService;

    public function __construct(GetDictionaryService $getDictionaryService)
    {
        $this->getDictionaryService = $getDictionaryService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Get dictionary data from a PDF file.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dictionary = $this->getDictionaryService->getDictionaryData();
    
        foreach ($dictionary as $item) {
            $output->writeln(sprintf('Code: %s Description: %s', $item->getCode(), $item->getDescription()));
        }

         return Command::SUCCESS;
    }
}
