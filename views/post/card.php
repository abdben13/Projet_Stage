<?php
$marques = [];
foreach ($post->getMarques() as $marque) {
    $url = $router->url('marque', ['id' => $marque->getID(), 'slug' => $marque->getSlug()]);
    $marques[] = <<<HTML
    <a href="{$url}">{$marque->getName()}</a>
HTML;
}
?>
<div class="card mb-3">
    <div class="card-body">
        <?php if(!empty($post->getMarques())): ?>
            <?= implode(', ', $marques) ?>
        <?php endif ?>
        <h5 class="card-title"><?= htmlentities($post->getName())?></h5>
        <p class="text-muted">
        </p>
        <img src="<?= $post->getImagePath() ?>" alt="Image du post" style="max-width: 100px; height: auto;">
        <p><?= $post->getExcerpt() ?></p>
        <p>
            <a href="<?= $router->url('post', ['id' =>$post->getID(), 'slug'=>$post->getSlug()]) ?>"  class="btn btn-primary">Voir plus</a>
        </p>
    </div>
</div>