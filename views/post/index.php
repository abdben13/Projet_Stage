<?php

use App\Helpers\Text;
use App\Model\Post;


$title = 'Nos véhicules';
$pdo = new PDO('mysql:dbname=projet_stage;host=127.0.0.1', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$page = $_GET['page'] ?? 1;
if (!filter_var($page, FILTER_VALIDATE_INT)) {
    throw new Exception('Numero de page invalide');
}
if($page ==='1') {
    header('Location: ' .$router->url('home'));
    http_response_code(301);
    exit();
}
$currentPage = (int)$page;
if($currentPage <= 0) {
    throw new Exception('Numero de page invalide');
}
$count = (int)$pdo->query('SELECT COUNT(id) FROM post')->fetch(PDO::FETCH_NUM)[0];
$perPage = 12;
$pages = ceil($count / $perPage);
if($currentPage > $pages) {
    throw new Exception('Cette page n\'existe pas');
}
$offset = $perPage * ($currentPage - 1);
$query = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$posts = $query->fetchALL(PDO::FETCH_CLASS, Post::class);
?>

<h1>Catalogue</h1>

<div class="row">
    <?php foreach($posts as $post): ?>
    <div class="col-md-3">
        <?php require 'card.php' ?>
    </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?php if ($currentPage > 1): ?>
        <a href="<?= $router->url('home') ?>?page=<?= $currentPage - 1 ?>" class="btn btn-primary">&laquo; Page précédente</a>
    <?php endif ?>

    <?php if ($currentPage < $pages): ?>
        <div style="margin-left: auto;"> <!-- affichage du boutton en bas a droite lorsque l user est sur la page 1 -->
            <a href="<?= $router->url('home') ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary">Page suivante &raquo;</a>
        </div>
    <?php endif ?>
</div>

