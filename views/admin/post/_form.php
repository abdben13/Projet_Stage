<form action="" method="POST">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->textarea('content', 'Description'); ?>
    <?= $form->input('prix', 'Prix'); ?>
    <?= $form->input('kilometrage', 'Kilometrage'); ?>
    <?= $form->datetimeInput('mise_en_circulation', 'Mise en circulation'); ?>
    <?= $form->input('energie', 'Energie'); ?>
    <br>
    <button class="btn btn-primary">Publié</button>
</form>