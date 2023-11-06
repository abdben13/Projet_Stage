<?php
// Incluez les dépendances nécessaires, y compris la connexion à la base de données.
use App\Connection;

$marqueFilter = $_GET['marque'] ?? null;
$marqueFilter = (int) $marqueFilter; // Assurez-vous que c'est un entier valide.

try {
    $pdo = Connection::getPDO(); // Utilisez votre méthode de connexion personnalisée ici.
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Utilisez $marqueFilter dans votre requête SQL pour filtrer les articles.
if ($marqueFilter) {
    $query = $pdo->prepare('SELECT * FROM post WHERE marque_id = :marque');
    $query->bindValue(':marque', $marqueFilter, PDO::PARAM_INT);
    $query->execute();
} else {
    $query = $pdo->query('SELECT * FROM post');
}

