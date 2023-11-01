<?php

use App\Connection;
use App\Model\Marque;
use App\Model\Post;
use App\URL;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$query = $pdo->prepare('SELECT * FROM marque WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Marque::class);
$marque = $query->fetch();

if($marque === false) {
    throw new Exception('Aucune marque ne correspond à cet référence');
}

if($marque->getSlug() !== $slug) {
    $url = $router->url('marque', ['slug' => $marque->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

$title = "Marque {$marque->getName()}";
$currentPage = URL::getPositiveInt('page', 1);
if($currentPage <= 0) {
    throw new Exception('Numero de page invalide');
}
$count = (int)$pdo
    ->query('SELECT COUNT(marque_id) FROM post_marque WHERE marque_id = ' . $marque->getID())
    ->fetch(PDO::FETCH_NUM)[0];
$perPage = 12;
$pages = ceil($count / $perPage);
if($currentPage > $pages) {
    throw new Exception('Cette page n\'existe pas');
}
$offset = $perPage * ($currentPage - 1);
$query = $pdo->query("
    SELECT p.* 
    FROM post p 
    JOIN post_marque pm ON pm.post_id = p.id
    WHERE pm.marque_id = {$marque->getID()}
    ORDER BY created_at DESC 
    LIMIT $perPage OFFSET $offset
    
    ");
$posts = $query->fetchALL(PDO::FETCH_CLASS, Post::class);
$link = $router->url('marque', ['id' => $marque->getID(), 'slug' =>$marque->getSlug()]);
?>


<h1>Categorie <?= e($title) ?></h1>

<div class="row">
    <?php foreach($posts as $post): ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__) . '/post/card.php' ?>
        </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?php if ($currentPage > 1): ?>
        <?php
    $l = $link;
    if($currentPage > 2) $l .= $link . '?page=' . ($currentPage - 1);
        ?>
        <a href="<?= $l ?>" class="btn btn-primary">&laquo; Page précédente</a>
    <?php endif ?>
    <?php if ($currentPage < $pages): ?>
        <div style="margin-left: auto;"> <!-- affichage du boutton en bas a droite lorsque l user est sur la page 1 -->
            <a href="<?= $link ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary">Page suivante &raquo;</a>
        </div>
    <?php endif ?>
</div>
