<?php

use App\Connection;
use App\Helpers\Text;
use App\Model\Post;
use App\URL;

$title = 'Nos véhicules';
$pdo = Connection::getPDO();

$paginatedQuery = new \App\PaginatedQuery(
    "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(id) FROM post"
);
$posts = $paginatedQuery->getItems(Post::class);
$postsByID = [];
foreach ($posts as $post) {
    $postsByID[$post->getID()] = $post;
}

$marque = $pdo
    ->query('SELECT m.*, pm.post_id
    FROM post_marque pm
    JOIN marque m ON m.id = pm.marque_id
    WHERE pm.post_id IN (' . implode(',', array_keys($postsByID)) . ')'
    )->fetchAll(PDO::FETCH_CLASS, \App\Model\Marque::class);

foreach ($marque as $marques) {
    $postsByID[$marques->getPostID()]->addMarque($marques);
}
$link = $router->url('home');
?>

<h1>Catalogue</h1>

<div class="container mt-4">
    <form action="<?= $router->url('marques_filter') ?>" method="get">
        <label for="marque">Filtrer par marque :</label>
        <select name="marque" id="marque">
            <option value="">Toutes les marques</option>
            <?php foreach ($marques as $marque): ?>
                <option value="<?= $marque->getID() ?>"><?= e($marque->getName()) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
</div>
<br>
<div class="row">
    <?php foreach($posts as $post): ?>
    <div class="col-md-3">
        <?php require 'card.php' ?>
    </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousLink($link); ?>
    <div style="margin-left: auto;">
        <?= $paginatedQuery->nextLink($link); ?>
    </div>
</div>

