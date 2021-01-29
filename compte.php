<?php session_start(); if(!(isset($_SESSION['session']) && $_SESSION['session'] == 'connecter')) header('location: index.php'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>VANESTARRE</title>
    <meta name="author" content="Hugo Beauvois">
    <meta name="keywords" content="Vanestarre"> 
    <meta name="description" content="Bienvenue sur le site web de Vanestarre">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/form.js"></script>
</head>
<body>
<ul class="nav">
        <li class="menu mobile"><a><img class="theme_1_img" src="image/menu.png" alt=""></a></li>
        <li class="menu"><a class="accueil" href="index.php"></a></li>
        <li class="menu centre"><img class="loupe" src="image/loupe.png" alt=""></li>
        <li class="menu"><form class="rechercheform" method="GET"><input name="search" class="recherche" placeholder="Recherchez" type="search"></form></li>
        <li class="menu liconnexion"><a class="connexionmobile" href="signin.php">Connexion</a></li>
        <li class="menu liconnexion"><a class="connexionmobile" href="signup.php">S'inscrire</a></li>
        <?php if(isset($_SESSION['session']) && $_SESSION['session'] == 'connecter'){
        echo '<li class="menu droite2"><a class="pseudo" href="compte.php">'.$_SESSION['sessionName'].'</a><span><a class="deconnexion" href="disconnect.php">déconnexion</a></span></li>' . PHP_EOL;
        }
        else{
            echo '<li class="menu droite"><form class="connexionform" action="signup-processing.php" method="POST">Connexion : ' . PHP_EOL;
            if(isset($_SESSION['session']) && $_SESSION['session'] == 'errorEmail'){
                echo '<span class="error">Adresse mail ou mot de passe incorrect</span>' . PHP_EOL;
            }
            echo'<br><input class="connexion" name="email" placeholder="exemple@mail.com" type="text"><input class="connexion" name="mdp" placeholder="**********" type="password" autocomplete="current-password" maxlength="30"><br><input class="connexion" name="action" type="submit" value="se connecter"><a class="connexion enregistrer" href="signup.php">s\'inscrire</a>';
            if(isset($_SESSION['session']) && $_SESSION['session'] == 'errorEmail'){
                echo '<a class="connexion enregistrer" href="recuperation.php">Mot de passe oublié</a>' . PHP_EOL;
            }
            echo '</form></li>' . PHP_EOL;
        }?>  
    </ul>
    <main class="main_form">
        <h1>Paramètre du compte</h1>
        <?php
            if(isset($_SESSION['modification']) && $_SESSION['modification'] == 'errorPseudo') echo '<span class="errorFile">Pseudo déjà utilisé</span>' . PHP_EOL;
            if(isset($_SESSION['modification']) && $_SESSION['modification'] == 'errorEmail') echo '<span class="errorFile">Erreur dans l\'e-mail</span>' . PHP_EOL;
            if(isset($_SESSION['modification']) && $_SESSION['modification'] == 'errorMdp') echo '<span class="errorFile">Mot de passe actuel incorrect</span>' . PHP_EOL;

            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbmdp = 'root';
            $dbname ='vanestarre';
        
            $dbLink = mysqli_connect($dbhost,$dbuser,$dbmdp) or die('Erreurdeconnexionauserveur:'.mysqli_connect_error());
            mysqli_select_db($dbLink,$dbname) or die('Erreurdanslasélectiondelabase:'.mysqli_error($dbLink));

            $query = 'SELECT pseudo, email FROM users WHERE pseudo = \''. $_SESSION['sessionName'] . '\'';
            if(!($dbResult = mysqli_query($dbLink, $query))){
                echo 'Erreur de requête<br/>';
                // Affiche le type d'erreur.
                echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                // Affiche la requête envoyée.
                echo 'Requête : ' . $query . '<br/>';
                exit();
            }
            $dbRow = mysqli_fetch_assoc($dbResult);
            $query2 = 'SELECT pages, nbremoji FROM admin WHERE id = 1';
            if(!($dbResult2 = mysqli_query($dbLink, $query2))){
                echo 'Erreur de requête<br/>';
                // Affiche le type d'erreur.
                echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                // Affiche la requête envoyée.
                echo 'Requête : ' . $query2 . '<br/>';
                exit();
            }
            $dbRow2 = mysqli_fetch_assoc($dbResult2);
            echo '<form  class="form" action="compte-processing.php" onSubmit="return modification(this)" method="POST"><br>'.PHP_EOL;
            echo '<label for="pseudo">Pseudo :</label><br>'.PHP_EOL;
            echo '<input id="pseudo" name="pseudo" value="'. $dbRow["pseudo"] .'" type="text" maxlength="20"><br>'.PHP_EOL;
            echo '<label for="email">E-mail :</label><br>'.PHP_EOL;
            echo '<input id="email" name="email" value="'. $dbRow["email"] .'" type="text" maxlength="50"><br>'.PHP_EOL;
            echo '<label for="ancienmdp">Ancien mot de passe :</label><a class="connexion enregistrer recup" href="recuperation.php">Mot de passe oublié</a><br>'.PHP_EOL;
            echo '<input id="ancienmdp" name="ancienmdp" placeholder="*******" type="password" autocomplete="current-password" maxlength="30"><br>'.PHP_EOL;
            echo '<label for="mdp">Nouveau mot de passe :</label><br>'.PHP_EOL;
            echo '<input id="mdp" name="mdp" placeholder="*******" type="password" autocomplete="new-password" minlength="6" maxlength="30"><br>'.PHP_EOL;
            echo '<label for="confirmemdp">Confirmation du nouveau mot de passe :</label><br>'.PHP_EOL;
            echo '<input id="confirmemdp" name="confirmemdp" placeholder="*******" type="password" minlength="6" maxlength="30"><br>'.PHP_EOL;
            if((isset($_SESSION['sessionName']) && $_SESSION['sessionName'] == 'Vanestarre')){
                echo '<label for="page">Post par page :</label><br>'.PHP_EOL;
                echo '<input id="page" name="page" value="'. $dbRow2["pages"] .'" type="text" maxlength="20"><br>'.PHP_EOL;
                echo '<label for="emoji">Nombre d\'emoji avant event :</label><br>'.PHP_EOL;
                echo '<input id="emoji" name="emoji" value="'. $dbRow2["nbremoji"] .'" type="text" maxlength="20"><br>'.PHP_EOL;
            }
            echo '<input class="submit" name ="action" type="submit" value="enregistrer">'.PHP_EOL;
            echo '</form>'.PHP_EOL;
        ?>
    </main>
</body>
</html>
<!-- 
    (\_/)
    ( •,•)
    (")_(")
-->