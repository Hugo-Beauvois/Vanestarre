<?php session_start(); if(!(isset($_SESSION['sessionName']) && $_SESSION['sessionName'] == 'Vanestarre')) header('location: index.php'); ?>
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
        <h1>Nouveau post</h1>
        <?php if(isset($_SESSION['file']) && $_SESSION['file'] == 'error') echo '<span class="errorFile">Erreur dans l\'upload du post</span>' . PHP_EOL; ?>
        <form  class="form" action="post-processing.php" method="POST" enctype="multipart/form-data"><br>
        <label for="titre">Titre :</label><br>
        <input id="titre" name="titre" placeholder="Titre" type="text" maxlength="20" required=""><br>
        <label for="message">Message :</label><br>
        <textarea id="message" name="message" placeholder="Message" maxlength="50" required="" ></textarea><br>
        <label for="tags">Tags :</label><br>
        <textarea id="tags" name="tags" placeholder="Tags" maxlength="100" required=""></textarea><br>
        <input class="file" name="image" type="file" accept="image/png, image/jpeg"><br>
        <input class="submit" name ="action" type="submit" value="poster">
        </form>
    </main>
</body>
</html>
<!-- 
    (\_/)
    ( •,•)
    (")_(")
-->