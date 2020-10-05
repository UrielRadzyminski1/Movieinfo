#! /usr/bin/env php

<?php

use Symfony\Component\Console\Application;
use Acme\ReturnMovieInfo;


require 'vendor/autoload.php';

$app = new Application('Moviepedia', '1.0');

 $app->add(new ReturnMovieInfo(new GuzzleHttp\Client()));

$app->run();
