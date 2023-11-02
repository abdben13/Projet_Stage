<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <link rel="manifest" href="manifest.json">
    <title><?= $title ?? 'Ma voiture' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" href="/img/auto noir.png" type="image/x-icon">


</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a href="<?= $router->url('home') ?>" class="navbar-brand">Achetez-Auto.com</a>
</nav>

<div class="container mt-4">
    <form action="<?= $router->url('marques_filter') ?>" method="get">
        <label for="marque">Filtrer par marque :</label>
        <select name="marque" id="marque">
            <option value="">Toutes les marques</option>
            <?php foreach ($marques as $marque): ?>
                <option value="<?= $marque->getID() ?>"><?= e($marque->getName()) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
</div>
<div class="container mt-4">
    <?= $content ?>
</div>
</body>
<footer class="d-flex justify-content-center fixed-bottom">
    <p>© By Abdelaziz 2023</p>
</footer>

</html>