<?php

use App\Connection;
use App\Model\Marque;
use App\Model\Post;
use App\PaginatedQuery;
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

$title = " {$marque->getName()}";

$paginatedQuery = new PaginatedQuery(
    "SELECT p.* 
    FROM post p 
    JOIN post_marque pm ON pm.post_id = p.id
    WHERE pm.marque_id = {$marque->getID()}
    ORDER BY created_at DESC",
    "SELECT COUNT(marque_id) FROM post_marque WHERE marque_id = {$marque->getID()}"
);


/** @var Post[] */
$posts = $paginatedQuery->getItems(Post::class);
$link = $router->url('marque', ['id' => $marque->getID(), 'slug' =>$marque->getSlug()]);
?>


<h1>Véhicules <?= e($title) ?></h1>

<div class="row">
    <?php foreach($posts as $post): ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__) . '/post/card.php' ?>
        </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousLink($link) ?>
    <?= $paginatedQuery->nextLink($link) ?>
</div>

