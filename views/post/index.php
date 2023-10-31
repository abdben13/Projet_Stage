<?php

use App\Helpers\Text;
use App\Model\Post;


$title = 'Nos véhicules';
$pdo = new PDO('mysql:dbname=projet_stage;host=127.0.0.1', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$query = $pdo->query('SELECT * FROM post ORDER BY created_at DESC LIMIT 12');
$posts = $query->fetchALL(PDO::FETCH_CLASS, Post::class);
?>

<h1>Catalogue</h1>

<div class="row">
    <?php foreach($posts as $post): ?>
    <div class="col-md-3">
        <?php require 'card.php' ?>
    </div>
    <?php endforeach ?>
</div>

