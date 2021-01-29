<?php
    session_start();

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbmdp = 'root';
    $dbname ='vanestarre';

    $action = $_POST['action'];
    $email = $_POST['email'];

    $dbLink = mysqli_connect($dbhost,$dbuser,$dbmdp) or die('Erreurdeconnexionauserveur:'.mysqli_connect_error());
    mysqli_select_db($dbLink,$dbname) or die('Erreurdanslasélectiondelabase:'.mysqli_error($dbLink));

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $mdp = '';
    for ($i = 0; $i < 10; $i++) {
        $mdp = $mdp . $characters[rand(0, strlen($characters) - 1)];
    }
    $message = "Voici votre nouveau mot de passe temporaire :\r\n" . $mdp;
    $mdp = hash('sha256', $mdp);

    $query = 'SELECT email, mdp FROM users WHERE email = \''. $email . '\'';
    if (!($dbResult = mysqli_query($dbLink, $query))){
        echo 'Erreur de requête<br/>';
        // Affiche le type d'erreur.
        echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
        // Affiche la requête envoyée.
        echo 'Requête : ' . $query . '<br/>';
        exit();
    }
    $dbRow = mysqli_fetch_assoc($dbResult);
    if (!$dbRow == NULL){
        $query = 'UPDATE users SET mdp = \''. $mdp . '\' WHERE email = \''. $dbRow["email"] . '\'';
        if (!($dbResult = mysqli_query($dbLink, $query))){
            echo 'Erreur de requête<br/>';
            // Affiche le type d'erreur.
            echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
            // Affiche la requête envoyée.
            echo 'Requête : ' . $query . '<br/>';
            exit();
        }
        mail($dbRow["mail"], 'Récupération de mot de passe', $message);
        unset($_SESSION['session']);
        header('location: recuperation.php');
    }
    else{
        unset($_SESSION['session']);
        header('location: recuperation.php');
    }
        