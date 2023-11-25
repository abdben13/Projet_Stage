<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$vehicleName = $_GET['vehicle'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $to = "abdelazizben.dev@gmail.com";
    $subject = "Nouveau message de $name via le formulaire de contact";
    $body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";

    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "<p>Votre message a été envoyé avec succès!</p>";
    } else {
        echo "<p>Désolé, une erreur s'est produite lors de l'envoi de votre message.</p>";
        // Vérifiez les erreurs d'envoi de l'email
        echo error_get_last()['message'];
    }
}
session_start();

// Récupérez l' id' du post depuis la session
$vehicleName = $_SESSION['postID'] ?? '';
?>

<div class="d-flex justify-content-between my-4">
    <div class="btn-retour">
        <a href="javascript:history.back()" class="btn btn-primary">Retour</a>
    </div>
</div>

<div class="container mt-5">

    <div class="coordonnées">
        <h1>Contactez-nous</h1>
        <br>
        <h3>Téléphone : 07.69.88.84.39</h3>
        <h3>Email : abdelazizben.dev@gmail.com</h3>
    </div>
    <form action="" method="post">

        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="vehicle">Référence du Véhicule :</label>
            <input type="text" id="vehicle" name="vehicle" class="form-control" value="<?= htmlspecialchars($vehicleName) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="message">Message :</label>
            <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
        </div>
        <br>
        <button type="submit" class="btn btn-primary" name="submit">Envoyer</button>
    </form>
</div>