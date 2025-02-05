<?php
// Démarrer la session
session_start();

// Détruire toutes les données de la session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session

// Rediriger l'utilisateur vers la page de connexion
header('Location: index.php');
exit(); // Assurez-vous que le script s'arrête après la redirection
?>