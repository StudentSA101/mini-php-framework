<?php

namespace App\Commands;

use Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use Enqueue\Redis\RedisConnectionFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportData extends Command
{
  protected static $defaultName = 'app:import-data';

  public function __construct()
  {
    parent::__construct();
  }

  protected function configure()
  {
    $this->setDescription('Import CSV Data')
      ->setHelp('This command allows you to import data from a csv file');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    try {
      $csv = Reader::createFromPath(__DIR__ . '/../../storage/MOCK_DATA.csv', 'r');
      $csv->setHeaderOffset(0); //set the CSV header offset
      $records = (new Statement())->limit(1000)
        ->process($csv);
      $offset = 1000;
      $connectionFactory = new RedisConnectionFactory([
        'host' => 'localhost',
        'port' => 6379,
        'scheme_extensions' => ['predis'],
      ]);
      $context = $connectionFactory->createContext();
      $queue = $context->createQueue('HyveQueue');
      while (count($records) && !$context->getRedis()->eval("return redis.call('get',KEYS[1])", ['HyveQueue'])) {
        // Data has been saved to a key so kan be delegated to multiple instances of application
        $message = $context->createMessage(json_encode($records));
        $context->createProducer()->send($queue, $message);
        $records = (new Statement())
          ->offset($offset)
          ->limit(1000)->process($csv);
        $offset += 1000;
      }
      return Command::SUCCESS;
    } catch (Exception $e) {
      return Command::FAILURE;
    }
  }
}
