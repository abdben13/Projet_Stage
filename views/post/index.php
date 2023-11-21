<?php

use App\Connection;
use App\Table\MarqueTable;
use App\Table\PostTable;


$title = 'Nos véhicules';
$pdo = Connection::getPDO();
$marqueTable = new MarqueTable($pdo);
$marques = $marqueTable->findAll();
$table = new PostTable($pdo);
[$posts, $pagination] = $table->findPaginated();

$link = $router->url('home');
?>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        L'annonce a bien été créer
    </div>
<?php endif ?>
<h1 class='text-center'>Trouver votre voiture au meilleur prix</h1>


<div class="container mt-4">
    <form action="<?= $router->url('marques_filter') ?>" method="get">
        <div class="row">
            <div class="col-md-3"> <!-- 3 colonnes pour la sélection de marque -->
                <label for="marque"> Par marque </label>
                <select name="marque" id="marque" class="form-control">
                    <option value="">Sélectionner une marque</option>
                    <?php foreach ($marques as $marque): ?>
                        <option value="<?= $marque->getID() ?>"><?= e($marque->getName()) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3"><!-- 3 colonnes  -->
                <label for="price_max">Prix maximum :</label>

                <input type="number" value="" id="price_max" name="price_max" min="100" step="100" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12"> <!-- Utilisation de 12 colonnes pour le bouton de soumission -->
                <button type="submit" class="btn btn-primary btn-block">Filtrer</button>
            </div>
        </div>
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
    <div class="btn-pagination">
        <?= $pagination->nextLink($link); ?>
    </div>
</div>
<br>
