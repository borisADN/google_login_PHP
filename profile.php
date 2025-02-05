<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: signup.php');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">

</head>

<body style="background-color: #003049">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto mt-20">
        <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
            <div class="max-w-md mx-auto">
                <!-- Afficher les messages de session -->
                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?= htmlspecialchars($_SESSION['message']) ?>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <div class="flex items-center space-x-5 justify-center">
                    <p class="text-3xl font-semibold text-blue-600">Profil</p>
                </div>

                <div class="mt-5 text-center">
                    <!-- Photo de profil -->
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Photo de profil" class="w-24 h-24 rounded-full mx-auto">
                    <!-- Nom de l'utilisateur -->
                    <p class="text-xl font-semibold mt-3"><?= htmlspecialchars($user['name']) ?></p>
                    <!-- Email de l'utilisateur -->
                    <p class="text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
                </div>

                <div class="mt-5 text-center">
                    <!-- Bouton de déconnexion -->
                    <button onclick="confirmLogout()" class="text-blue-600 hover:underline">Déconnexion</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
    <script>
        // Masquer le message de confirmation lors du redirection
        setTimeout(function() {
            document.querySelector('.bg-green-100').style.display = 'none';
        }, 3000);

        // Fonction de Confirmation avant la deconnexion
        function confirmLogout() {
            Swal.fire({
                title: 'Confirmer la déconnexion',
                text: 'Etes-vous sur de vouloir vous déconnecter ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, je souhaite me déconnecter',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }
    </script>

    </head>
</body>

</html>