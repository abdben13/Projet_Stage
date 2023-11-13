<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <link rel="manifest" href="manifest.json">
    <title><?= isset($title) ? e($title) : 'Achetez-Auto.com' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" href="/img/auto noir.png" type="image/x-icon">


</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a href="<?= $router->url('home') ?>" class="navbar-brand">Achetez-Auto.com</a>
    <div class="nav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="<?= $router->url('admin_posts') ?>" class="nav-link">Posts</a>
            </li>
            <li class="nav-item">
                <a href="<?= $router->url('admin_marques') ?>" class="nav-link">Marques</a>
            </li>
            <li class="nav-item">
                <a href="<?= $router->url('home') ?>" class="nav-link">Accueil</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Contact</a>
            </li>
            <li class="nav-item">
                <form action="<?= $router->url('logout') ?>" method="post" style="display:inline">
                    <div class="btn-logout">
                        <button type="submit" class="nav-link">Se deconnecter</button>
                    </div>
                </form>
            </li>
        </ul>
</nav>


<div class="container mt-4">
    <?= $content ?>
</div>
</body>
<footer class="d-flex justify-content-center fixed-bottom">
    <p>© By Abdelaziz 2023</p>
</footer>

</html>