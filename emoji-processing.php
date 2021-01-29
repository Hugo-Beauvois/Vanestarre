<?php 
    session_start();

    if(isset($_SESSION['session']) && ($_SESSION['session'] == 'connecter')){
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbmdp = 'root';
        $dbname ='vanestarre';

        $post = strip_tags($_GET['post']);
        $emoji = strip_tags($_GET['emoji']);
        $name = $_SESSION['sessionName'];

        $dbLink = mysqli_connect($dbhost,$dbuser,$dbmdp) or die('Erreurdeconnexionauserveur:'.mysqli_connect_error());
        mysqli_select_db($dbLink,$dbname) or die('Erreurdanslasélectiondelabase:'.mysqli_error($dbLink));

        $query = 'SELECT vote, '.$emoji.' FROM post WHERE messageId = \''. $post . '\'';
        if (!($dbResult = mysqli_query($dbLink, $query))){
            echo 'Erreur de requête<br/>';
            // Affiche le type d'erreur.
            echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
            // Affiche la requête envoyée.
            echo 'Requête : ' . $query . '<br/>';
            exit();
        }
        $dbRow = mysqli_fetch_assoc($dbResult);
        $query2 = 'SELECT nbremoji FROM admin WHERE id = 1';
        if (!($dbResult2 = mysqli_query($dbLink, $query2))){
            echo 'Erreur de requête<br/>';
            // Affiche le type d'erreur.
            echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
            // Affiche la requête envoyée.
            echo 'Requête : ' . $query2 . '<br/>';
            exit();
        }
        $dbRow2 = mysqli_fetch_assoc($dbResult2);
        $tabVote = unserialize($dbRow['vote']);
        if(empty($dbRow['vote'])){
            $name = array($name);
            $name = serialize($name);
            $query = 'UPDATE post SET vote = \''. $name . '\' WHERE messageId = \''. $post .'\'';
            if (!($dbResult = mysqli_query($dbLink, $query))){
                echo 'Erreur de requête<br/>';
                // Affiche le type d'erreur.
                echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                // Affiche la requête envoyée.
                echo 'Requête : ' . $query . '<br/>';
                exit();
            }
            $query = 'UPDATE post SET '.$emoji.' = '.$emoji.' + 1 WHERE messageId = \''. $post .'\'';
            if (!($dbResult = mysqli_query($dbLink, $query))){
                echo 'Erreur de requête<br/>';
                // Affiche le type d'erreur.
                echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                // Affiche la requête envoyée.
                echo 'Requête : ' . $query . '<br/>';
                exit();
            }
            unset($_SESSION['emoji']);
            if($dbRow2['nbremoji'] == $dbRow[$emoji] + 1){
                $_SESSION['emoji'] = 'bitcoin';
            }
            header('location: index.php');
        }
        else{
            $voted = false;
            for($i = 0; $i < count($tabVote); $i++){
                if($tabVote[$i] == $name) $voted = true;
            }
            if ($voted){
                $_SESSION['emoji'] = 'errorVote';
                header('location: index.php');
            }
            else{
                $tabVote[count($tabVote)] = $name;
                $tabVote = serialize($tabVote);;
                $query = 'UPDATE post SET vote = \''. $tabVote . '\' WHERE messageId = \''. $post .'\'';
                if (!($dbResult = mysqli_query($dbLink, $query))){
                    echo 'Erreur de requête<br/>';
                    // Affiche le type d'erreur.
                    echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                    // Affiche la requête envoyée.
                    echo 'Requête : ' . $query . '<br/>';
                    exit();
                }
                $query = 'UPDATE post SET '.$emoji.' = '.$emoji.' + 1 WHERE messageId = \''. $post .'\'';
                if (!($dbResult = mysqli_query($dbLink, $query))){
                    echo 'Erreur de requête<br/>';
                    // Affiche le type d'erreur.
                    echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                    // Affiche la requête envoyée.
                    echo 'Requête : ' . $query . '<br/>';
                    exit();
                }
                unset($_SESSION['emoji']);
                if($dbRow2['nbremoji'] == $dbRow[$emoji] + 1){
                    $_SESSION['emoji'] = 'bitcoin';
                }
                header('location: index.php');
            }
        }
    }
    else{
        $_SESSION['emoji'] = 'errorConnexion';
        header('location: index.php');
    }