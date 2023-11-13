<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$errors = [];
$post = new Post();

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $postTable = new PostTable($pdo);
    $v = new PostValidator($_POST);
    ObjectHelper::hydrate($post, $_POST, ['name', 'content', 'prix', 'kilometrage', 'mise_en_circulation', 'energie', 'created_at']);
    if ($v->validate()) {
        $postTable->createPost($post);
        header('Location: ' . $router->url('home', ['id' => $post->getID()]) . '?success=1');
        exit();
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        L'annonce n'a pas pu être enregistrée
    </div>
<?php endif ?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<h1>Créer une annonce</h1>
<form action="" method="POST">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->textarea('content', 'Description'); ?>
    <?= $form->input('prix', 'Prix'); ?>
    <?= $form->input('kilometrage', 'Kilometrage'); ?>
    <?= $form->datetimeInput('mise_en_circulation', 'Mise en circulation'); ?>
    <?= $form->input('energie', 'Energie'); ?>
    <?= $form->datetimeInput('CreatedAt', 'Date de publication'); ?>
    <br>
    <button class="btn btn-primary">Publié</button>
</form>
<br>
