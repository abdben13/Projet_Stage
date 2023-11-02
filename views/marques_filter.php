<?php
// Incluez les dépendances nécessaires, y compris la connexion à la base de données.

$marqueFilter = $_GET['marque'] ?? null;
$marqueFilter = (int)$marqueFilter; // Assurez-vous que c'est un entier valide.

// Utilisez $marqueFilter dans votre requête SQL pour filtrer les articles.
$query = $pdo->prepare('SELECT * FROM post');
if ($marqueFilter) {
    $query->execute(['marque' => $marqueFilter]);
} else {
    $query->execute();
}
