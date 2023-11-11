<?php
namespace App\Connection;

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();
$title = "Administration";
$pdo = Connection::getPDO();
$link = $router->url('admin_posts');
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();

?>

<?php if(isset($_GET['delete'])): ?>
<div class="alert alert-success">
    L'annonce a bien été supprimé
</div>
<?php endif ?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<table class="table table-striped">
    <thead>
        <th>References</th>
        <th>Annonces</th>
        <th><a href="<?= $router->url('admin_post_new') ?>" class="btn btn-primary">Créer une annonce</a></th>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= $post->getID() ?></td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>">
                <?= e($post->getName()) ?></a>
           </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>" class="btn btn-primary">
                    Editer
                </a>
                <form action="<?= $router->url('admin_post_delete', ['id' => $post->getID()]) ?>" method="POST"
                onsubmit="return confirm('Voulez vous vraiment effectuer cette suppression ?')" style="display:inline">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link); ?>
    <div class="btn-pagination">
        <?= $pagination->nextLink($link); ?>
    </div>
</div>
