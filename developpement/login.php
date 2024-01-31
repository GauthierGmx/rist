<?php
// Informations de connexion à la base de données
$dsn = 'mysql:host=lakartxela.iutbayonne.univ-pau.fr;dbname=gvernis_cms';
$username = 'gvernis_cms';
$password = 'gvernis_cms';

try {
    // Création d'une instance de la classe PDO pour établir la connexion
    $pdo = new PDO($dsn, $username, $password);

    // Paramétrage de PDO pour afficher les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connexion réussie
    echo "Connexion réussie à la base de données.";
    
    if (isset($_POST['login']) && isset($_POST['pwd'])) {
        // on vérifie les informations saisies

        // Récupération du hash dans la bdd
        $login = $_POST['login'];
        // Utilisation de requête préparée pour éviter les injections SQL
        $sql = "SELECT motDePasse FROM Rist_Utilisateur WHERE pseudonyme = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$login]); // Passer le paramètre dans un tableau pour la sécurité
        $result = $stmt->fetchColumn(); // Utilisation de fetchColumn() pour récupérer directement le résultat
        
        // Vérification
        if ($result && password_verify($_POST['pwd'], $result)) {
            // Si le mot de passe correspond, on démarre une session
            session_start();
            $_SESSION['login'] = $_POST['login'];
            $_SESSION['pwd'] = $_POST['pwd'];
            echo "Bonjour " . $_SESSION['login']; // Affichage du message de bienvenue

            header("Location: page_Recommandation.php");

        } else {
            echo "Identifiant ou mot de passe incorrect.";
        }
    }

} catch (PDOException $e) {
    // En cas d'erreur de connexion, affichage du message d'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}

?>
