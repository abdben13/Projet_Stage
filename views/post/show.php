<?php

use App\Connection;
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
session_start();

// Récupérez l'id' du post depuis la session
$_SESSION['postID'] = $post->getID();
?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<h1> <?php foreach ($post->getMarques() as $marque): ?>
        <a href="<?= $router->url('marque', ['id' => $marque->getID(), 'slug' => $marque->getSlug()]) ?>"><?= e($marque->getName()) ?></a>
    <?php endforeach ?>
    <?= e($post->getName()) ?>
</h1>
<br>
<div class="img">
    <a href="<?= $post->getImagePath() ?>" target="_blank">
        <img src="<?= $post->getImagePath() ?>" alt="Image du post" style="max-width: 300px; height: auto;">
    </a>
</div>
<br>
<div class="border p-3 mb-3 d-flex align-items-baseline">
    <div class="flex-grow-1 me-3">
        <fieldset>
            <legend class="w-auto">-------- Critères --------</legend>
            <p>Reference: <?= $post->getID() ?></p>

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
<?php
$name = $post->getName();

?>
<div class="mt-3 mb-5 text-center">
    <a href="<?= $router->url('contact', ['vehicle' => urlencode($post->getID())]) ?>" class="btn btn-primary">Nous contacter</a>
</div>




