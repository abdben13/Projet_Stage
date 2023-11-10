<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();
$pdo = Connection::getPDO();
$table = new PostTable($pdo);
$table->delete($params['id']);
header('Location:' . $router->url('admin_posts') .'?delete=1');
?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<h1>Suppression de l'annonce <?= $params['id'] ?></h1>
