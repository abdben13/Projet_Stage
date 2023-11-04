<?php

use App\Connection;
use App\Table\PostTable;


$title = 'Nos véhicules';
$pdo = Connection::getPDO();

$table = new PostTable($pdo);
[$posts, $pagination] = $table->findPaginated();

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
    <?= $pagination->previousLink($link); ?>
    <div style="margin-left: auto;">
        <?= $pagination->nextLink($link); ?>
    </div>
</div>

