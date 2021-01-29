<?php session_start();?>
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
    <main>
        <div><a class="fleche" href="#top"><img class="imgfleche" src="image/fleche.png" alt=""></a></div>
        <?php
            if(isset($_SESSION['sessionName']) && $_SESSION['sessionName'] == 'Vanestarre'){
                echo '<div class="post"><h1 class="boutonPost"><a class="addPost" href="post.php">Ajouter un post</a></h1></div>' . PHP_EOL;
            }
            if(isset($_SESSION['emoji']) && $_SESSION['emoji'] == 'errorConnexion'){
                echo '<script type="text/javascript">alert(\'Il faut être connecté pour pouvoir réagir aux post!\')</script>'; 
                unset($_SESSION['emoji']);
            }
            if(isset($_SESSION['emoji']) && $_SESSION['emoji'] == 'errorVote'){
                echo '<script type="text/javascript">alert(\'Vous avez déjà réagi à ce post!\');</script>'; 
                unset($_SESSION['emoji']);
            }
            if(isset($_SESSION['emoji']) && $_SESSION['emoji'] == 'bitcoin'){
                echo '<script type="text/javascript">alert(\'Vous devez payer 10 bitcoin à Vanestarre!\');</script>'; 
                unset($_SESSION['emoji']);
            }
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbmdp = 'root';
            $dbname ='vanestarre';
        
            $dbLink = mysqli_connect($dbhost,$dbuser,$dbmdp) or die('Erreurdeconnexionauserveur:'.mysqli_connect_error());
            mysqli_select_db($dbLink,$dbname) or die('Erreurdanslasélectiondelabase:'.mysqli_error($dbLink));

            if(isset($_GET['page']) && !empty($_GET['page'])){
                $pageActuel = (int) strip_tags($_GET['page']);
            }else{
                $pageActuel = 1;
            }
            $query2 = 'SELECT COUNT(*) AS nbpost FROM post';
            if (!($dbResult2 = mysqli_query($dbLink, $query2))){
                echo 'Erreur de requête<br/>';
                // Affiche le type d'erreur.
                echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                // Affiche la requête envoyée.
                echo 'Requête : ' . $query2 . '<br/>';
                exit();
            }
            $dbRow2 = mysqli_fetch_assoc($dbResult2);
            $nbPost = (int) $dbRow2['nbpost'];
            $parPage = 5;
            $pages = ceil($nbPost / $parPage);
            $premierPost = ($pageActuel * $parPage) - $parPage;

            $query = 'SELECT * FROM post ORDER BY messageId DESC LIMIT '.$premierPost.', '.$parPage;
            if(isset($_GET['search']) && !empty($_GET['search'])){
                $search = htmlspecialchars($_GET['search']);
                $tabSearch = explode(' ',$search);
                $tabSearch = str_replace(' ','',$tabSearch);
                $tabSearch = array_filter($tabSearch);
                $tabSearch = array_values($tabSearch);
                if (count($tabSearch) > 1){
                    //print_r(count($tabSearch));
                    //print_r($tabSearch);
                    $query = 'SELECT * FROM post WHERE ';
                    $query2 = 'SELECT COUNT(*) AS nbpost FROM post WHERE ';
                    for($i = 0; $i < count($tabSearch); ++$i){
                        $query = $query . 'tags LIKE "%'.$tabSearch[$i].'%" OR ';
                        $query2 = $query2 . 'tags LIKE "%'.$tabSearch[$i].'%" OR ';
                    }
                    $query = substr($query, 0, -3);
                    $query2 = substr($query2, 0, -3);
                    $dbResult2 = mysqli_query($dbLink, $query2);
                    $dbRow2 = mysqli_fetch_assoc($dbResult2);
                    $nbPost = (int) $dbRow2['nbpost'];
                    $parPage = 5;
                    $pages = ceil($nbPost / $parPage);
                    $premierPost = ($pageActuel * $parPage) - $parPage;
                    $query = $query . 'ORDER BY messageId DESC LIMIT '.$premierPost.', '.$parPage;
                    $dbResult = mysqli_query($dbLink, $query);
                    $dbRow = mysqli_fetch_assoc($dbResult);
                    $tabTags = unserialize($dbRow['tags']);
                    $titre = $dbRow['titre'];
                    $titre = str_replace('$', '\'',$titre);
                    $message = $dbRow['message'];
                    $message = str_replace('$', '\'',$message);
                    echo '<div class="post">' . PHP_EOL;
                    echo '<h1 class="titre">'. $titre . '<span class="date">'. $dbRow["date"] . '</span></h1>' . PHP_EOL;
                    if ($dbRow["image"] != null){
                        echo '<img class="image" src="image/post/'. $dbRow["image"] . '" alt="">' . PHP_EOL;
                    }
                    echo '<p class="commentaire">'. $message . '</p>' . PHP_EOL;
                    echo '<p class="tags">';
                    for($j = 0; $j < count($tabTags); $j++){
                        $tabTags[$i] = str_replace('$', '\'',$tabTags[$i]);
                        echo '<span class="spantags">'. 'β' .$tabTags[$j] . '</span>';
                    }
                    echo '</p>' . PHP_EOL;
                    echo '<p class="ligneemoji premier"><a href="emoji-processing.php?emoji=emojiLove&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/love.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiLove"].'</span><a href="emoji-processing.php?emoji=emojiCute&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/cute.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiCute"].'</span><a href="emoji-processing.php?emoji=emojiStyle&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/style.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiStyle"].'</span><a href="emoji-processing.php?emoji=emojiSwag&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/swag.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiSwag"].'</span></p>' . PHP_EOL;
                    echo '</div>' . PHP_EOL;
                }
                else{
                    $query = 'SELECT * FROM post WHERE tags LIKE "%'.$search.'%" ORDER BY messageId DESC LIMIT '.$premierPost.', '.$parPage;
                    $query2 = 'SELECT COUNT(*) AS nbpost FROM post WHERE tags LIKE"%'.$search.'%"';
                    $dbResult2 = mysqli_query($dbLink, $query2);
                    $dbRow2 = mysqli_fetch_assoc($dbResult2);
                    $nbPost = (int) $dbRow2['nbpost'];
                    $parPage = 5;
                    $pages = ceil($nbPost / $parPage);
                    $premierPost = ($pageActuel * $parPage) - $parPage;
                    $dbResult = mysqli_query($dbLink, $query);
                    while($dbRow = mysqli_fetch_assoc($dbResult)){
                        $tabTags = unserialize($dbRow['tags']);
                        $titre = $dbRow['titre'];
                        $titre = str_replace('$', '\'',$titre);
                        $message = $dbRow['message'];
                        $message = str_replace('$', '\'',$message);
                        echo '<div class="post">' . PHP_EOL;
                        echo '<h1 class="titre">'. $titre . '<span class="date">'. $dbRow["date"] . '</span></h1>' . PHP_EOL;
                        if ($dbRow["image"] != null){
                            echo '<img class="image" src="image/post/'. $dbRow["image"] . '" alt="">' . PHP_EOL;
                        }
                        echo '<p class="commentaire">'. $message . '</p>' . PHP_EOL;
                        echo '<p class="tags">';
                        for($i = 0; $i < count($tabTags); $i++){
                            $tabTags[$i] = str_replace('$', '\'',$tabTags[$i]);
                            echo '<span class="spantags">'. 'β' .$tabTags[$i] . '</span>';
                        }
                        echo '</p>' . PHP_EOL;
                        echo '<p class="ligneemoji premier"><a href="emoji-processing.php?emoji=emojiLove&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/love.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiLove"].'</span><a href="emoji-processing.php?emoji=emojiCute&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/cute.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiCute"].'</span><a href="emoji-processing.php?emoji=emojiStyle&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/style.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiStyle"].'</span><a href="emoji-processing.php?emoji=emojiSwag&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/swag.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiSwag"].'</span></p>' . PHP_EOL;
                        echo '</div>' . PHP_EOL;
                    }
                }
            }
            else{
                if (!($dbResult = mysqli_query($dbLink, $query))){
                    echo 'Erreur de requête<br/>';
                    // Affiche le type d'erreur.
                    echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                    // Affiche la requête envoyée.
                    echo 'Requête : ' . $query . '<br/>';
                    exit();
                }
                while($dbRow = mysqli_fetch_assoc($dbResult)){
                    $tabTags = unserialize($dbRow['tags']);
                    $titre = $dbRow['titre'];
                    $titre = str_replace('$', '\'',$titre);
                    $message = $dbRow['message'];
                    $message = str_replace('$', '\'',$message);
                    echo '<div class="post">' . PHP_EOL;
                    echo '<h1 class="titre">'. $titre . '<span class="date">'. $dbRow["date"] . '</span></h1>' . PHP_EOL;
                    if ($dbRow["image"] != null){
                        echo '<img class="image" src="image/post/'. $dbRow["image"] . '" alt="">' . PHP_EOL;
                    }
                    echo '<p class="commentaire">'. $message . '</p>' . PHP_EOL;
                    echo '<p class="tags">';
                    for($i = 0; $i < count($tabTags); $i++){
                        $tabTags[$i] = str_replace('$', '\'',$tabTags[$i]);
                        echo '<span class="spantags">'. 'β' .  $tabTags[$i] . '</span>';
                    }
                    echo '</p>' . PHP_EOL;
                    echo '<p class="ligneemoji premier"><a href="emoji-processing.php?emoji=emojiLove&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/love.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiLove"].'</span><a href="emoji-processing.php?emoji=emojiCute&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/cute.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiCute"].'</span><a href="emoji-processing.php?emoji=emojiStyle&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/style.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiStyle"].'</span><a href="emoji-processing.php?emoji=emojiSwag&amp;post='.$dbRow["messageId"].'"><img class="emojis" src="image/swag.png" alt=""></a><span class="emojischiffre">'.$dbRow["emojiSwag"].'</span></p>' . PHP_EOL;
                    echo '</div>' . PHP_EOL;
                }
            }
        ?>
        <ul class="pagelist">
            <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
            <li class="pagination <?php if($pageActuel == 1) echo 'hidden'; ?>">
                <a class="pagelien" href="./?page=<?php echo $pageActuel - 1; if(isset($_GET['search']) && !empty($_GET['search'])) echo '&amp;search='.$_GET['search']; ?>"><img class="flechepage" src="image/left_arrow.png" alt=""></a>
            </li>
            <?php for($page = 1; $page <= $pages; $page++){
                echo '<!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->'. PHP_EOL;
                echo '<li class="pagination ';
                if($pageActuel == $page) echo 'actuel';
                if($pages == 1) echo ' hidden' ;
                echo '">'. PHP_EOL;
                echo '<a class="pagelien chiffre" href="./?page='.$page;
                if(isset($_GET['search']) && !empty($_GET['search'])) echo '&amp;search='.$_GET['search'];
                echo '">'.$page. '</a>'. PHP_EOL;
                echo '</li>'. PHP_EOL;
            }?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li class="pagination <?php if($pageActuel == $pages) echo 'hidden' ?>">
                <a class="pagelien" href="./?page=<?php echo $pageActuel + 1; if(isset($_GET['search']) && !empty($_GET['search'])) echo '&amp;search='.$_GET['search']; ?>"><img class="flechepage" src="image/right_arrow.png" alt=""></a>
            </li>
        </ul>
    </main>
</body>
</html>
<!-- 
    (\_/)
    ( •,•)
    (")_(")
-->