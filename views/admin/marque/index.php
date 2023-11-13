<?php
namespace App\Connection;

use App\Auth;
use App\Connection;
use App\Table\MarqueTable;

Auth::check();
$title = "Gestion des marques";
$pdo = Connection::getPDO();
$link = $router->url('admin_marques');
$items = (new MarqueTable($pdo))->all();

?>

<?php if(isset($_GET['delete'])): ?>
<div class="alert alert-success">
    La marque a bien été supprimée
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
        <th>Marques</th>
        <th>
            <a href="<?= $router->url('admin_marque_new') ?>" class="btn btn-primary">Créer une marque</a>
        </th>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item->getID() ?></td>
            <td>
                <a href="<?= $router->url('admin_marque', ['id' => $item->getID()]) ?>">
                <?= e($item->getName()) ?></a>
           </td>
            <td>
                <a href="<?= $router->url('admin_marque', ['id' => $item->getID()]) ?>" class="btn btn-primary">
                    Editer
                </a>
                <form action="<?= $router->url('admin_marque_delete', ['id' => $item->getID()]) ?>" method="POST"
                onsubmit="return confirm('Voulez vous vraiment effectuer cette suppression ?')" style="display:inline">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

