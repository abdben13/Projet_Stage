<?php
use App\Connection;
use App\HTML\Form;
use App\Table\PostTable;
use App\Validator;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;
$errors = [];
$form = new Form($post, $errors);

if (!empty($_POST)) {
    Validator::lang('fr');
    $v = new Validator($_POST);

    $v->rule('required', ['name', 'slug']);
    $v->rule('lengthBetween', 'name', 3, 200);
    $v->rule('lengthBetween', 'slug', 3, 200);
    $v->rule('required', 'content');
    $v->rule('required', 'prix');
    $v->rule('required', 'kilometrage');
    $v->rule('required', 'mise_en_circulation');
    $v->rule('required', 'energie');

    if ($v->validate()) {
        $miseEnCirculation = DateTime::createFromFormat('Y-m-d', $_POST['mise_en_circulation']);
        $fieldsToUpdate = [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'content' => $_POST['content'],
            'prix' => $_POST['prix'],
            'kilometrage' => $_POST['kilometrage'],
            'mise_en_circulation' => $miseEnCirculation,
            'energie' => $_POST['energie'],
        ];
        $postTable->updateFields($post, $fieldsToUpdate);

        $success = true;
    } else {
        $errors = $v->errors();
    }
}
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
        <div style="margin-left: auto;">
            <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
        </div>
    </div>

<h1>Editer l'annonce: <?= e($post->getName()) ?></h1>
<form action="" method="POST">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->input('slug', 'URL'); ?>
    <?= $form->textarea('content', 'Description'); ?>
    <?= $form->input('prix', 'Prix'); ?>
    <?= $form->input('kilometrage', 'Kilometrage'); ?>
    <?= $form->datetimeInput('mise_en_circulation', 'Mise en circulation'); ?>
    <?= $form->input('energie', 'Energie'); ?>

    <button class="btn btn-primary">Modifier</button>
</form>
<?php if ($success): // Check if the form was submitted successfully ?>
    <!-- Update the $updatedPost variable with the latest data -->
    <?php $updatedPost = $postTable->find($post->getID()); ?>

    <!-- Create a new form instance with the updated data -->
    <?php $form = new Form($updatedPost, $errors); ?>
<?php endif ?>