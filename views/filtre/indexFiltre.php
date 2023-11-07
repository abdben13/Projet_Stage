<?php
use App\Connection;
use App\Table\MarqueTable;
use App\Table\PostTable;

$title = 'Nos véhicules';
$pdo = Connection::getPDO();
$marqueTable = new MarqueTable($pdo);
$table = new PostTable($pdo);
$marques = $marqueTable->findAll();

// Récupére la marque sélectionnée depuis $_GET
$marqueID = isset($_GET['marque']) ? (int)$_GET['marque'] : null;
$priceMax = isset($_GET['price_max']) ? (int)$_GET['price_max'] : null;

// Vérifiez si l'utilisateur n'a pas sélectionné de marque (marqueID est null) mais a spécifié un prix maximum
if ($marqueID === null && $priceMax !== null) {
    // Appel de la méthode avec les paramètres de filtre normaux, sauf la marque (en laissant null)
    $posts = $table->findPostsByFilters(null, $priceMax);
} else {
    // Appel de la méthode avec les paramètres de filtre normaux
    $posts = $table->findPostsByFilters($marqueID, $priceMax);
}
$table = new PostTable($pdo);
// Récupére le prix maximum depuis $_GET
$priceMax = $_GET['price_max'] !== '' ? (int)$_GET['price_max'] : null;
// Utilise la  méthode pour trouver les posts en fonction des filtres
$posts = $table->findPostsByFilters($marqueID, $priceMax);
$link = $router->url('home');
?>

<h1>Catalogue</h1>

<br>
<?php if (empty($posts)): ?>
    <p>Aucun véhicule ne correspond à votre recherche actuellement.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($posts as $postGroup): ?>
            <?php if (is_array($postGroup)): ?>
                <?php foreach ($postGroup as $post): ?>
                    <!-- Affichez les résultats de la recherche ici -->
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
                        <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]) ?>"
                           class="btn btn-primary">Voir plus</a>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
