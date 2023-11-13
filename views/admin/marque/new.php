<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Marque;
use App\ObjectHelper;
use App\Table\MarqueTable;
use App\Validators\MarqueValidator;

Auth::check();

$errors = [];
$item = new Marque();

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $table = new MarqueTable($pdo);
    $v = new MarqueValidator($_POST, $table);
    ObjectHelper::hydrate($item, $_POST, ['name', 'slug']);
    if ($v->validate()) {
        $table->create([
            'name' => $item->getName(),
            'slug' => $item->getSlug()
        ]);
        header('Location: ' . $router->url('admin_marques') . '?success=1');
        exit();
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($item, $errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        La marque n'a pas pu être enregistrée
    </div>
<?php endif ?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<h1>Créer une marque</h1>
<form action="" method="POST">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->textarea('slug', 'Url'); ?>
    <br>
    <button class="btn btn-primary">Publié</button>
</form>
<br>
