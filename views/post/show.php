<?php

use App\Connection;
use App\Model\Marque;
use App\Model\Post;
use App\Table\MarqueTable;
use App\Table\PostTable;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$post = (new PostTable($pdo))->find($id);
(new MarqueTable($pdo))->hydratePosts([$post]);

if($post->getSlug() !== $slug) {
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}
?>

<div class="d-flex justify-content-between my-4">
    <div style="margin-left: auto;">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<h1><?= e($post->getName()) ?></h1>
<img src="<?= $post->getImagePath() ?>" alt="Image du post">
<br>
<br>
<div class="border p-3 mb-3 d-flex align-items-baseline">

    <div class="flex-grow-1 me-3">
        <fieldset>
            <legend class="w-auto">-------- Critères --------</legend>
            <p>Constructeur: <?php foreach ($post->getMarques() as $marque): ?>
                    <a href="<?= $router->url('marque', ['id' => $marque->getID(), 'slug' => $marque->getSlug()]) ?>"><?= e($marque->getName()) ?></a>
                <?php endforeach ?></p>
            <p>Date de mise en circulation: <?= $post->getMise_en_circulation()->format('d/m/y') ?></p>
            <p>Energie: <?= $post->getEnergie() ?></p>
        </fieldset>
    </div>

    <div class="flex-grow-1">
        <p class="mb-3">Kilométrage: <?= $post->getKilometrage() ?> kms</p>
        <p>Prix: <?= $post->getPrix() ?>€</p>
        <p>Mise en ligne le: <?= $post->getCreatedAt()->format('d/m/y') ?></p>
    </div>

</div>

<div class="border p-3">
    <fieldset>
        <legend class="w-auto">-------- Description --------</legend>
        <p><?= $post->getFormattedContent() ?></p>
    </fieldset>
</div>



