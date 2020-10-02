<?php namespace Acme;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
/* use Symfony\Component\Console\Helper\Table; */
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
           ->addArgument('movieName', InputArgument::REQUIRED, 'Movie name');
          
  }
  public function execute(InputInterface $input, OutputInterface $output)
  {
    $movieName = $input->getArgument('movieName');
    //get movie information
    $movieData = $this->getMovieData($movieName);
/*     //print movie info
    $this->renderTabbedJson($movieData, $output); */
    $movieData = json_decode($movieData);
    unset($movieData->Ratings);

    foreach ($movieData as $key => $value) {
      echo $key;
      echo $value;
    }

    return 0;
  }

  private function getMovieData($movieName)
  {
    $requestTarget = 'http://www.omdbapi.com/?apikey=5266aa44&t='. $movieName;
    $movieDataResponse = $this->client->get($requestTarget)->getBody();
    return $movieDataResponse;
  }

/*   private function renderTabbedJson($movieData, $output)
  {
    $table = new Table($output);
    $result = json_decode($movieData);

  } */
}