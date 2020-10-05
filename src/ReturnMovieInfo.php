<?php namespace Acme;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use GuzzleHttp\ClientInterface;

class ReturnMovieInfo extends Command{


  private $client;

  public function __construct(ClientInterface $client) 
  {
    $this->client = $client;
    parent::__construct();
  }

  public function configure()
  {
      $this->setName('show')
           ->setDescription('Returns information of given movie')
           ->addArgument('movieName', InputArgument::REQUIRED, 'Movie name')
           ->addOption('fullPlot', true ,InputOption::VALUE_NONE, 'Return full movie plot');
          
  }
  public function execute(InputInterface $input, OutputInterface $output)
  {
    $movieName = $input->getArgument('movieName');
    $fullPlot = $input->getOption('fullPlot');
    //get movie information
    $movieData = $this->getMovieData($movieName, $fullPlot);
/*     //print movie info
    $this->renderTabbedJson($movieData, $output); */
    $movieData = json_decode($movieData);
    unset($movieData->Ratings);
    $this->renderTabbedJson($movieData, $output);
/*     foreach ($movieData as $key => $value) {
      echo $key;
      echo '    ';
      echo $value;
      echo "\r\n";
    }
 */
    return 0;
  }

  private function getMovieData($movieName, $fullPlot)
  {
    $requestTarget = 'http://www.omdbapi.com/?apikey=5266aa44&t='. $movieName;
    if($fullPlot){
      $requestTarget = $requestTarget . '&plot=full';
    }
    $movieDataResponse = $this->client->get($requestTarget)->getBody();
    return $movieDataResponse;
  }

  private function renderTabbedJson($movieData, $output)
  {
    $arraydData = [];
    $i = 0;
    foreach ($movieData as $key => $value) {
      $arraydData[$i]=[$key, $value];
      $i++;
    }
    $table = new Table($output);
    $table->setRows($arraydData)
          ->render();
  }
}