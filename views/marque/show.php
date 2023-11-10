<?php

use App\Connection;
use App\Model\Marque;
use App\Model\Post;
use App\PaginatedQuery;
use App\Table\MarqueTable;
use App\Table\PostTable;
use App\URL;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$marque = (new MarqueTable($pdo))->find($id);

if($marque->getSlug() !== $slug) {
    $url = $router->url('marque', ['slug' => $marque->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

$title = " {$marque->getName()}";
[$posts, $paginatedQuery] = (new PostTable($pdo))->findPaginatedMarque($marque->getID());
$link = $router->url('marque', ['id' => $marque->getID(), 'slug' =>$marque->getSlug()]);
?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

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

