<?php

//use App\Router;
require '../vendor/autoload.php';

if(isset($_GET['page']) && $_GET ['page'] === '1') {
    // réecrire l url sans le parametrage de page
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(!empty($query)) {
        $uri = $uri . '?' . $query;
    }
    http_response_code(301);
    header('Location: ' . $uri);
    exit();
}
$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'post/index', 'home')
    ->get('/Projet_Stage/[*:slug]-[i:id]', 'post/show', 'post')
    ->get('/Projet_Stage/category', 'category/show', 'category')
    ->run();
