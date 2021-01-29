<?php
    session_start();

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbmdp = 'root';
    $dbname ='vanestarre';

    $action = $_POST['action'];
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $ancienmdp = hash('sha256', $_POST['ancienmdp']);
    $mdp = hash('sha256', $_POST['mdp']);

    $dbLink = mysqli_connect($dbhost,$dbuser,$dbmdp) or die('Erreurdeconnexionauserveur:'.mysqli_connect_error());
    mysqli_select_db($dbLink,$dbname) or die('Erreurdanslasélectiondelabase:'.mysqli_error($dbLink));

    $query = 'SELECT pseudo, mdp FROM users WHERE pseudo = \''. $_SESSION['sessionName'] . '\'';
    if (!($dbResult = mysqli_query($dbLink, $query))){
        echo 'Erreur de requête<br/>';
        // Affiche le type d'erreur.
        echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
        // Affiche la requête envoyée.
        echo 'Requête : ' . $query . '<br/>';
        exit();
    }
    $dbRow = mysqli_fetch_assoc($dbResult);
    if (!empty($_POST['ancienmdp']) && $ancienmdp != $dbRow["mdp"]){
        $_SESSION['modification'] = 'errorMdp';
        header('location: compte.php');
    }

    else if ($action == 'enregistrer'){
        $query = 'SELECT id, pseudo, email FROM users WHERE pseudo = \''. $_SESSION['sessionName'] . '\'';
        if (!($dbResult = mysqli_query($dbLink, $query))){
            echo 'Erreur de requête<br/>';
            // Affiche le type d'erreur.
            echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
            // Affiche la requête envoyée.
            echo 'Requête : ' . $query . '<br/>';
            exit();
        }
        $dbRow = mysqli_fetch_assoc($dbResult);
        if ($dbRow["pseudo"] != $pseudo){
            $query2 = 'SELECT pseudo FROM users WHERE pseudo = \''. $pseudo . '\'';
            if (!($dbResult2 = mysqli_query($dbLink, $query2))){
                $_SESSION['modification'] = 'errorPseudo';
            }
            $dbRow2 = mysqli_fetch_assoc($dbResult2);
            if (!$dbRow2 == NULL){
                $_SESSION['modification'] = 'errorPseudo';
            }
            else {
                $query2 = 'UPDATE users SET pseudo = \''. $pseudo . '\' WHERE id = \''. $dbRow["id"] . '\'';
                if (!($dbResult2 = mysqli_query($dbLink, $query2))){
                    $_SESSION['modification'] = 'errorPseudo';
                }
                $_SESSION['sessionName'] = $pseudo;
                //unset($_SESSION['modification']);
            }
        }
        if ($dbRow["email"] != $email){
            $query2 = 'UPDATE users SET email = \''. $email . '\' WHERE id = \''. $dbRow["id"] . '\'';
            if (!($dbResult2 = mysqli_query($dbLink, $query2))){
                $_SESSION['modification'] = 'errorEmail';
            }
            unset($_SESSION['modification']);
        }
        if (!empty($_POST['ancienmdp']) && $dbRow["mdp"] != $mdp){
            $query2 = 'UPDATE users SET mdp = \''. $mdp . '\' WHERE id = \''. $dbRow["id"] . '\'';
            if (!($dbResult2 = mysqli_query($dbLink, $query2))){
                $_SESSION['modification'] = 'errorMdp';
            }
            unset($_SESSION['modification']);
        }
        if($dbRow["pseudo"] == $pseudo && $dbRow["email"] == $email && empty($_POST['ancienmdp'])){
            unset($_SESSION['modification']);
        }
        header('location: compte.php');
    }