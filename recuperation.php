<?php session_start(); ?>
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
        <h1>Récupération de mot de passe :</h1>
        <form  class="form" action="recuperation-processing.php" method="POST"><br>
        <label for="email">E-mail :</label><br>
        <input id="email" name="email" placeholder="exemple@mail.com" type="email" maxlength="50" required="" ><br>
        <input class="submit" name ="action" type="submit" value="récupérer">
        </form>
    </main>
</body>
</html>
<!-- 
    (\_/)
    ( •,•)
    (")_(")
-->