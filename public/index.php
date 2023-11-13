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
    ->get('/marque/[*:slug]-[i:id]', 'marque/show', 'marque')
    ->get('/Projet_Stage/[*:slug]-[i:id]', 'post/show', 'post')
    ->get('/marques-filter', 'filtre/indexFiltre', 'marques_filter')
    ->match('/login', 'auth/login', 'login')
    ->post('/logout', 'auth/logout', 'logout')
    //ADMIN
    //Gestion des posts
    ->get('/admin', 'admin/post/index', 'admin_posts')
    ->match('/admin/post/[i:id]', 'admin/post/edit', 'admin_post')
    ->post('/admin/post/[i:id]/delete', 'admin/post/delete', 'admin_post_delete')
    ->match('/admin/post/new', 'admin/post/new', 'admin_post_new')
    //Gestion des marques
    ->get('/admin/marques', 'admin/marque/index', 'admin_marques')
    ->match('/admin/marques/[i:id]', 'admin/marque/edit', 'admin_marque')
    ->post('/admin/marques/[i:id]/delete', 'admin/marque/delete', 'admin_marque_delete')
    ->match('/admin/marques/new', 'admin/marque/new', 'admin_marque_new')
    ->run();

