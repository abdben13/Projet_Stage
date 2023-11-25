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

// requete de base
$sql = "SELECT p.*
        FROM {$table->getTable()} p
        LEFT JOIN post_marque pm ON p.id = pm.post_id";


// preparation de la requete pour filtrer par marque
if (!empty($marqueID)) {
    if ($marqueID === 0) {
        $marquesQuery = "SELECT * FROM marque";
        $marques = $pdo->query($marquesQuery)->fetchAll(PDO::FETCH_CLASS, Marque::class);
    } else {
        $conditions[] = 'pm.marque_id = :marqueID';
        $parameters[':marqueID'] = $marqueID;
    }
}
// Null != 0 et != false != '' != indefined != string

// preparation  de la requete pour filtrer par prix
if (isset($priceMax) && $priceMax !== 0) {
    $conditions[] = 'p.prix <= :priceMax';
    $parameters[':priceMax'] = $priceMax;
}
// construction de la requete
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
                <div class="card-body d-flex flex-column">
                    <?php if(!empty($post->getMarques())): ?>
                        <?= implode(', ', $marques) ?>
                    <?php endif ?>
                    <h5 class="card-title"><?= e($post->getName())?></h5>
                    <p class="text-muted"></p>
                    <div class="thumbnail">
                        <img src="<?= $post->getImagePath() ?>" alt="<?= e($post->getName())?>" class="img-fluid fixed-image">
                    </div>
                    <p><?= $post->getExcerpt() ?></p>
                    <p class="mt-auto text-center">
                        <a href="<?= $router->url('post', ['id' =>$post->getID(), 'slug'=>$post->getSlug()]) ?>" class="btn btn-primary">Voir plus</a>
                    </p>
                </div> <!-- .card-body -->
            </div>
        </div>
    <?php endforeach ?>
</div>