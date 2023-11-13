<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\MarqueTable;
use App\Validators\MarqueValidator;

Auth::check();

$pdo = Connection::getPDO();
$table = new MarqueTable($pdo);
$item = $table->find($params['id']);
$success = false;
$errors = [];
$form = new Form($item, $errors);
$fields = ['name', 'slug'];

if (!empty($_POST)) {
    $v = new MarqueValidator($_POST, $table, $item->getID());
    ObjectHelper::hydrate($item, $_POST, $fields);
    if ($v->validate()) {
        $table->update([
                'name' => $item->getName(),
            'slug' => $item->getSlug()
        ], $item->getID());
        $success = true;
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($item, $errors);
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        La marque a bien été modifiée
    </div>
<?php endif ?>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        La marque a bien été créer
    </div>
<?php endif ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        La marque n'a pas pu être modifiée
    </div>
<?php endif ?>

    <div class="d-flex justify-content-between my-4">
        <div class="btn-retour">
            <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
        </div>
    </div>

<h1>Editer la marque: <?= e($item->getName()) ?></h1>
    <form action="" method="POST">
        <?= $form->input('name', 'Titre'); ?>
        <?= $form->textarea('slug', 'Url'); ?>
        <br>
        <button class="btn btn-primary">Modifié</button>
    </form>
<br>
