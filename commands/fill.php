<?php
$pdo = new PDO('mysql:dbname=projet_stage;host=127.0.0.1', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

/*for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO post SET name='Article #$i', slug='article-$i', created_at='2023-10-30 16:00:00', content='lorem ipsum'");

}*/

/*for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO marque SET name='Article #$i', slug='article-$i'");
}*/

$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='admin', password='$password'");