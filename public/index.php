<?php

use App\Router;

require'../vendor/autoload.php';

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/Projet_Stage', 'post/index', 'Projet_Stage')
    ->get('/Projet_Stage/category', 'category/show', 'category')
    ->run();
