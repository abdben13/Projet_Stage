<?php

//use App\Router;

require '../vendor/autoload.php';

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'post/index', 'home')
    ->get('/Projet_Stage/[*:slug]-[i:id]', 'post/show', 'post')
    ->get('/Projet_Stage/category', 'category/show', 'category')
    ->run();
