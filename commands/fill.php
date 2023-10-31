<?php

use App\Connection;

require dirname(__DIR__) . '/vendor/autoload.php';

$pdo = Connection::getPDO();

/*for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO post SET name='Article #$i', slug='article-$i', created_at='2023-10-30 16:00:00', content='lorem ipsum'");

}*/

/*for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO marque SET name='Article #$i', slug='article-$i'");
}*/

$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='admin', password='$password'");