<?php

namespace App\Commands;

use Exception;
use App\Jobs\ImportJob;
use App\Models\Queue;
use Enqueue\Redis\RedisConnectionFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RunWorker extends Command 
{
    protected static $defaultName = 'app:run-worker';
  
    public function __construct()
    {
      parent::__construct();
    }

  protected function configure()
  {
    $this->setDescription('Processes queue data')
        ->setHelp('This command allows you to process queue data');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    try {
      // Data has been saved to a key so kan be delegated to multiple instances of application
      $connectionFactory = new RedisConnectionFactory([
        'host' => 'localhost',
        'port' => 6379,
        'scheme_extensions' => ['predis'],
      ]);
      $context = $connectionFactory->createContext();
      $queue = $context->createQueue('HyveQueue');
      $consumer = $context->createConsumer($queue);
      // Once the message is retrieved it is removed from redis.
      $message = $consumer->receive();
      (new ImportJob)->process($message,$context);
      $context->deleteQueue($queue);  
      // For test running consumer within this file.
      // Setup multiple instances of application to consume queue. 
      // Save queue names in database for consistency
      return Command::SUCCESS;
    } catch(Exception $e) {
      return Command::FAILURE;
    }
  }   
  
}