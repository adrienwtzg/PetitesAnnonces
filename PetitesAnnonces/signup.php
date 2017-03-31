<?php
session_start(); //Démarre la session

$login = (isset($_REQUEST["login"])?$_REQUEST["login"]:"");
$pass = (isset($_REQUEST["pass"])?$_REQUEST["pass"]:"");
$pass1 = (isset($_REQUEST["pass1"])?$_REQUEST["pass1"]:"");

$MessageLoginExiste = "";
$MessagePassEquals = "";

$bdd = new PDO('mysql:host=localhost;dbname=mydb', "root", ""); //Se conncecte à la base de donnée




try
{
  if ($login != "" && $pass != "")
  {
    $salt = uniqid();

    $login_Exist = $bdd->prepare("SELECT Nm_Last FROM tbl_user WHERE Nm_Last = :login");
    //On recupère les pseudo de t'as base ou les pseudo son egal au pseudo passer par le formulaire
    $login_Exist->bindValue('login', $login, PDO::PARAM_STR);
    $login_Exist->execute();
    //on exécute la requête

    $pseudoINbdd = $login_Exist->rowCount();
    //Rowcount permet de sortir le nombre de valeur que t'as requête renvoi, que l'on rentre dans la variable pseudoINbdd (ou autre )

    if($pseudoINbdd == 0)
    {
      if ($pass == $pass1) {
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $ins = $bdd->prepare('INSERT INTO tbl_user (Nm_Last, Txt_Password_Hash, Txt_Password_Salt) VALUES("'.$login.'", "'. sha1($pass.$salt) .'", "'.$salt.'")');
        $ins->execute();
        $_SESSION["login"] = $login;
        header('location: home.php');
      }
      else {
        $MessagePassEquals = "Les mots de passe de correspondent pas !";
      }
    }
    else {
      $MessageLoginExiste = "Ce login est déja utilisé !";
    }
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
    <title>Page d'inscription</title>
  </head>
  <body>
    <form action="#" method="POST">
          <table>
              <tr>
                  <td>
                      Créer un login :
                  </td>
                  <td>
                      <input type="text" name="login" required="required" value=""/>
                  </td>
                  <td>
                      <?php
                          echo "<a style='color: red'>".$MessageLoginExiste."</a>";
                      ?>
                  </td>
              </tr>
              <tr>
                  <td>
                      Créer un mot de passe :
                  </td>
                  <td>
                      <input type="password" name="pass" required="required"/>
                  </td>
              </tr>
              <tr>
                  <td>
                      Confirmer le mot de passe :
                  </td>
                  <td>
                      <input type="password" name="pass1" required="required"/>
                  </td>
                  <td>
                      <?php
                          echo "<a style='color: red'>".$MessagePassEquals."</a>";
                      ?>
                  </td>
              </tr>
              <tr>
                  <td></td>
                  <td>
                      <input type="submit" value="Signup" name="btnSubmit"/>
                  </td>
              </tr>

          </table>
      </form>
  </body>
</html>
