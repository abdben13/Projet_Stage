<?php

use App\Connection;
use App\Table\MarqueTable;
use App\Table\PostTable;

$title = 'Nos véhicules';
$pdo = Connection::getPDO();
$marqueTable = new MarqueTable($pdo);
$marques = $marqueTable->findAll();

// Récupére la marque sélectionnée depuis $_GET
$marqueID = $_GET['marque'] ?? null;
$table = new PostTable($pdo);
// Vérifie si la marque sélectionnée est valide
if (is_numeric($marqueID)) {
    $marqueID = (int)$marqueID;
    // Mise à jour la requête SQL pour filtrer les articles en fonction de la marque sélectionnée
    $posts = $table->findPaginatedMarque($marqueID);
} else {
    // Si la marque n'est pas sélectionnée ou si elle n'est pas valide, affichez tous les articles
    $posts = $table->findAll();
}
$link = $router->url('home');
?>

<h1>Catalogue</h1>
<div class="container mt-4">
    <form action="<?= $router->url('marques_filter') ?>" method="get">
        <label for="marque">Filtrer par marque :</label>
        <select name="marque" id="marque">
            <?php foreach ($marques as $marque): ?>
                <option value="<?= $marque->getID() ?>" <?= $marque->getID() == $marqueID ? 'selected' : '' ?>>
                    <?= e($marque->getName()) ?>
                </option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
</div>
<br>

<div class="row">
    <?php foreach ($posts as $postGroup): ?>
        <?php if (is_array($postGroup)): ?>
            <?php foreach ($postGroup as $post): ?>
                <div class="col-md-3">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
                            <p class="text-muted">
                                <?= $post->getCreatedAt()->format('d F Y') ?>
                                <?php if (!empty($post->getMarques())): ?>
                                    ::
                                    <?php
                                    $marques = [];
                                    foreach ($post->getMarques() as $marque) {
                                        $url = $router->url('marque', ['id' => $marque->getID(), 'slug' => $marque->getSlug()]);
                                        $marques[] = '<a href="' . $url . '">' . $marque->getName() . '</a>';
                                    }
                                    echo implode(', ', $marques);
                                    ?>
                                <?php endif ?>
                            </p>
                            <p><?= $post->getExcerpt() ?></p>
                            <p>
                                <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]) ?>" class="btn btn-primary">Voir plus</a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>



