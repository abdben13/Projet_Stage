<?php

use App\Connection;
use App\Table\MarqueTable;
use App\Table\PostTable;
use App\Model\Marque;

$pdo = Connection::getPDO();
$marqueTable = new MarqueTable($pdo);
$table = new PostTable($pdo);
$marques = $marqueTable->findAll();
[$posts, $pagination] = $table->findPaginated();

$marqueID = isset($_GET['marque']) ? (int)$_GET['marque'] : null;
$priceMax = isset($_GET['price_max']) ? (int)$_GET['price_max'] : null;

$conditions = [];
$parameters = [];

if ($marqueID !== null) {
    if ($marqueID === 0) {
        $marquesQuery = "SELECT * FROM marque";
        $marques = $pdo->query($marquesQuery)->fetchAll(PDO::FETCH_CLASS, Marque::class);
    } else {
        $conditions[] = 'pm.marque_id = :marqueID';
        $parameters[':marqueID'] = $marqueID;
    }
}
if ($priceMax !== null) {
    $conditions[] = 'p.prix <= :priceMax';
    $parameters[':priceMax'] = $priceMax;
}

$sql = "SELECT p.*
        FROM {$table->getTable()} p
        LEFT JOIN post_marque pm ON p.id = pm.post_id";

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$query = $pdo->prepare($sql);
$query->execute($parameters);

$marqueName = ($marqueID !== null && $marqueID !== 0) ? $marqueTable->find($marqueID)->getName() : '';
$title = $marqueName;
$posts = $query->fetchAll(PDO::FETCH_CLASS, $table->class);

$link = $router->url('home');
?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<h1>Véhicules <?= e($title) ?></h1>

<br>
<?php if (empty($posts)): ?>
    <p>Aucun véhicule ne correspond à votre recherche actuellement.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($posts as $postGroup): ?>
            <?php if (is_array($postGroup)): ?>
                <?php foreach ($postGroup as $post): ?>
                <?php endforeach ?>
            <?php endif ?>
        <?php endforeach ?>
    </div>
<?php endif ?>
<div class="row">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
                    <p class="text-muted">
                        <img src="<?= $post->getImagePath() ?>" alt="Image du post" style="max-width: 100px; height: auto;">
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
                        <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]) ?>"
                           class="btn btn-primary">Voir plus</a>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link); ?>
    <div class="btn-pagination">
        <?= $pagination->nextLink($link); ?>
    </div>
</div>


