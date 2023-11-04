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
        <h5 class="card-title"><?= htmlentities($post->getName())?></h5>
<p class="text-muted">
    <?= $post->getCreatedAt()->format('d F Y') ?>
    <?php if(!empty($post->getMarques())): ?>
    ::
    <?= implode(', ', $marques) ?>
    <?php endif ?>
</p>
<p><?= $post->getExcerpt() ?></p>
<p>
    <a href="<?= $router->url('post', ['id' =>$post->getID(), 'slug'=>$post->getSlug()]) ?>"  class="btn btn-primary">Voir plus</a>
</p>
</div>
</div>