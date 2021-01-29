<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbmdp = 'root';
    $dbname ='vanestarre';

    $action = $_POST['action'];
    $email = $_POST['email'];
    $mdp = hash('sha256', $_POST['mdp']);

    $dbLink = mysqli_connect($dbhost,$dbuser,$dbmdp) or die('Erreurdeconnexionauserveur:'.mysqli_connect_error());
    mysqli_select_db($dbLink,$dbname) or die('Erreurdanslasélectiondelabase:'.mysqli_error($dbLink));

    if ($action == 's\'inscrire'){
        $pseudo = $_POST['pseudo'];
        $query = 'SELECT pseudo FROM users WHERE pseudo = \''. $pseudo . '\'';

        if (!($dbResult = mysqli_query($dbLink, $query))){
            session_start();
            $_SESSION['session'] = 'errorPseudo';
            unset($_SESSION['sessionName']);
            header('location: signup.php');
        }
        $dbRow = mysqli_fetch_assoc($dbResult);
        if (!$dbRow == NULL){
            session_start();
            $_SESSION['session'] = 'errorPseudo';
            unset($_SESSION['sessionName']);
            header('location: signup.php');
        }
        else{
            $query = 'INSERT INTO users (pseudo, email, mdp) VALUES (\'' . $pseudo . '\', \'' . $email . '\', \'' . $mdp . '\')';
            if(!($dbResult = mysqli_query($dbLink, $query))){
                session_start();
                $_SESSION['session'] = 'errorPseudo';
                unset($_SESSION['sessionName']);
                header('location: signup.php');
            }
            session_start();
            $_SESSION['session'] = 'connecter';
            $_SESSION['sessionName'] = $pseudo;
            header('location: index.php');
        }
    }

    if ($action == 'se connecter'){
        $query = 'SELECT email, mdp, pseudo FROM users WHERE email = \''. $email . '\'';

        if (!($dbResult = mysqli_query($dbLink, $query))){
            session_start();
            $_SESSION['session'] = 'errorEmail';
            unset($_SESSION['sessionName']);
            header('location: index.php');
        }
        $dbRow = mysqli_fetch_assoc($dbResult);
        if ($dbRow['mdp'] == $mdp){
            session_start();
            $_SESSION['session'] = 'connecter';
            $_SESSION['sessionName'] = $dbRow['pseudo'];
            header('location: index.php');
        }
        else {
            session_start();
            $_SESSION['session'] = 'errorEmail';
            unset($_SESSION['sessionName']);
            header('location: index.php');
        }
    }