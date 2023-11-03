<?php

use App\Connection;
use App\Model\Marque;
use App\Model\Post;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$query = $pdo->prepare('SELECT * FROM post WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Post::class);
$post = $query->fetch();

if($post === false) {
    throw new Exception('Aucune annonce ne correspond à cet référence');
}

if($post->getSlug() !== $slug) {
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

$query = $pdo->prepare('
SELECT m.id, m.slug, m.name 
FROM post_marque pm 
JOIN marque m ON pm.marque_id = m.id
WHERE pm.post_id = :id');
$query->execute(['id' => $post->getID()]);
$query->setFetchMode(PDO::FETCH_CLASS, Marque::class);
/** @var Marque[] */
$marques = $query->fetchAll();
?>

<?php foreach ($marques as $marque): ?>
    <a href="<?= $router->url('marque', ['id' => $marque->getID(), 'slug' => $marque->getSlug()]) ?>"><?= e($marque->getName()) ?></a>
<?php endforeach ?>
<h1><?= e($post->getName()) ?></h1>
<img src="<?= $post->getImagePath() ?>" alt="Image du post">
<br>
<br>
<p>Date de mise en circulation: <?= $post->getMise_en_circulation()->format('d/m/y') ?></p>
<p>Kilométrage: <?= $post->getKilometrage() ?> kms</p>
<p>Prix: <?= $post->getPrix() ?>€</p>
<p>Description: <?= $post->getFormattedContent() ?></p>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>

