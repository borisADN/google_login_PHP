<?php
require_once './vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('1096725193918-ch2bnhqu9meuvp4mca98si945l61bvs7.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google_login/google-callback.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    // Échanger le code contre un jeton d'accès
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Récupérer les informations de l'utilisateur
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Informations de l'utilisateur
    $googleId = $userInfo->getId();
    $name = $userInfo->getName();
    $email = $userInfo->getEmail();
    $profilePicture = $userInfo->getPicture();

    // Connexion à la base de données MySQL
    $host = 'localhost';
    $dbname = 'google_auth';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = :google_id");
        $stmt->execute(['google_id' => $googleId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Insérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (google_id, name, email, profile_picture) VALUES (:google_id, :name, :email, :profile_picture)");
            $stmt->execute([
                'google_id' => $googleId,
                'name' => $name,
                'email' => $email,
                'profile_picture' => $profilePicture
            ]);

           // Message de succès
           $_SESSION['message'] = "Inscription réussie !";
        } else {
             // Message d'information
             $_SESSION['message'] = "Vous êtes déjà inscrit.";
        }

          // Stocker les informations de l'utilisateur dans la session
          $_SESSION['user'] = [
            'id' => $googleId,
            'name' => $name,
            'email' => $email,
            'profile_picture' => $profilePicture
        ];

        // Rediriger vers la page de profil
        header('Location: profile.php');
        exit();
        
    } catch (Exception $e) {
        // Gestion des erreurs
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        // header('Location: signup.php');
        exit();
    }
} else {
   // Rediriger vers la page d'accueil si aucun code n'est reçu
   header('Location: index.php');
   exit();
}
?>