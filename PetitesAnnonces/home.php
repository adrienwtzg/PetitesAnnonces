<?php
session_start();

if (!isset($_SESSION['login'])) {
  header('location: index.php');
}

?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Page d'accueil</title>
  </head>
  <body>
    <div>
      <p>Bienvenue Monsieur <?php echo $_SESSION["login"]?></p>
      <a href="Logout.php">Déconnexion</a>
    </div>
  </body>
</html>
