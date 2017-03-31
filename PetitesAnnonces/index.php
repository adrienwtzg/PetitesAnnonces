<?php
session_start(); //Démarre la session

$bdd = new PDO('mysql:host=localhost;dbname=mydb', "root", ""); //Se conncecte à la base de donnée


$loginTest = (isset($_REQUEST["login"])?$_REQUEST["login"]:""); // Stockage de la réponse de l'utilisateur pour le login
$passTest = (isset($_REQUEST["pass"])?$_REQUEST["pass"]:""); // Stockage de la réponse de l'utilisateur pour le pass

$MessageErreurLogin = "";
$MessageErreurPass = "";
$AlertTentative = "";

try
{
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $verif = "SELECT Id_User, Nm_Last, Txt_Password_Hash, Txt_Password_Salt FROM tbl_user WHERE Nm_Last='$loginTest'";
  foreach ($bdd->query($verif) as $i) {
           if (strcmp(sha1($passTest . $i['Txt_Password_Salt']), $i['Txt_Password_Hash']) == 0) {
               $_SESSION["login"] = $loginTest;
               header("location: home.php");
           }
   }
   if ($loginTest != "") {
     $MessageErreurPass = "Mot de passe incorrect !";
   }
}
catch (PDOException $e)
{
  echo "err" . $e->getMessage();
}


?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Page d'accueil</title>
  </head>
  <body>
    <form action="#" method="POST">
          <table>
              <tr>
                  <td>
                      Login :
                  </td>
                  <td>
                      <input type="text" name="login" required="required" value="<?php echo $loginTest ?>"/>
                  </td>
                  <td>
                      <?php
                          echo "<a style='color: red'>".$MessageErreurLogin."</a>";
                      ?>
                  </td>
              </tr>
              <tr>
                  <td>
                      Password :
                  </td>
                  <td>
                      <input type="password" name="pass" required="required"/>
                  </td>
                  <td>
                      <?php
                          echo "<a style='color: red'>".$MessageErreurPass."</a>";
                      ?>
                  </td>
              </tr>
              <tr>
                  <td></td>
                  <td>
                      <input type="submit" value="Login" name="btnSubmit"/>
                  </td>
              </tr>
              <tr>
                  <td>
                      <a href="signup.php">Inscription</a>
                  </td>
              </tr>
          </table>
      </form>
  </body>
</html>
