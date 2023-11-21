<?php

use App\Connection;

$marqueFilter = $_GET['marque'] ?? null;
$marqueFilter = (int) $marqueFilter;s

try {
    $pdo = Connection::getPDO();
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}


if ($marqueFilter) {
    $query = $pdo->prepare('SELECT * FROM post WHERE marque_id = :marque');
    $query->bindValue(':marque', $marqueFilter, PDO::PARAM_INT);
    $query->execute();
} else {
    $query = $pdo->query('SELECT * FROM post');
}



