<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\MarqueTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$marqueTable = new MarqueTable($pdo);
$marques = $marqueTable->list();
$post = $postTable->find($params['id']);
$marqueTable->hydratePosts([$post]);
$success = false;
$errors = [];
$form = new Form($post, $errors);

if (!empty($_POST)) {
    $v = new PostValidator($_POST, $marques);
    ObjectHelper::hydrate($post, $_POST, ['name', 'slug', 'content', 'prix', 'kilometrage', 'mise_en_circulation', 'energie']);
    if ($v->validate()) {
        $pdo->beginTransaction();
        $postTable->updatePost($post);
        $postTable->attachMarques($post->getID(), $_POST['marquesIds']);
        $pdo->commit();
        $marqueTable->hydratePosts([$post]);
        $success = true;
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        L'annonce a bien été modifiée
    </div>
<?php endif ?>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        L'annonce a bien été créer
    </div>
<?php endif ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        L'annonce n'a pas pu être modifiée
    </div>
<?php endif ?>

    <div class="d-flex justify-content-between my-4">
        <div class="btn-retour">
            <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
        </div>
    </div>

<h1>Editer l'annonce: <?= e($post->getName()) ?></h1>
    <form action="" method="POST">
        <?= $form->input('name', 'Titre'); ?>
        <?= $form->textarea('content', 'Description'); ?>
        <?= $form->select('marquesIds', 'Marques', $marques); ?>
        <?= $form->input('prix', 'Prix'); ?>
        <?= $form->input('kilometrage', 'Kilometrage'); ?>
        <?= $form->datetimeInput('mise_en_circulation', 'Mise en circulation'); ?>
        <?= $form->input('energie', 'Energie'); ?>
        <?= $form->datetimeInput('CreatedAt', 'Date de publication'); ?>
        <br>
        <button class="btn btn-primary">Modifié</button>
    </form>
<br>
<?php if ($success): ?>
    <?php $updatedPost = $postTable->find($post->getID()); ?>
    <?php $form = new Form($updatedPost, $errors); ?>
<?php endif ?>