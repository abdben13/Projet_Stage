<?php
use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validator;
use App\Validators\PostValidator;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;
$errors = [];
$form = new Form($post, $errors);

if (!empty($_POST)) {
    Validator::lang('fr');
    $v = new PostValidator($_POST);
    ObjectHelper::hydrate($post, $_POST, ['name', 'content', 'prix', 'kilometrage', 'mise_en_circulation', 'energie']);
    if ($v->validate()) {
        $postTable->update($post);
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
<?php require('_form.php') ?>
<br>
<?php if ($success): ?>
    <?php $updatedPost = $postTable->find($post->getID()); ?>
    <?php $form = new Form($updatedPost, $errors); ?>
<?php endif ?>