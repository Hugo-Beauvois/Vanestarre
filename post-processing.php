<?php
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbmdp = 'root';
        $dbname ='vanestarre';

    $action = $_POST['action'];
    $titre = $_POST['titre'];
    $message = $_POST['message'];
    $tags = $_POST['tags'];

    $titre = str_replace('\'', '$',$titre);
    $message = str_replace('\'', '$',$message);

    $beta = array("β");
    $tabTags = explode(" ", $tags);
    for($i = 0; $i < count($tabTags); $i++){
        $tabTags[$i] = str_replace(' ', '',$tabTags[$i]);
        $tabTags[$i] = str_replace('\'', '$',$tabTags[$i]);
    }
    $tabTags = array_filter($tabTags);
    $tabTags = array_values($tabTags);
    $tabTags = serialize($tabTags);

    $repertoireDestination = dirname(__FILE__)."/image/post/";
    $nomDestination        = "image_du_" . date('Y-m-d-h-i-s') . ".png";

    $dbLink = mysqli_connect($dbhost,$dbuser,$dbmdp) or die('Erreurdeconnexionauserveur:'.mysqli_connect_error());
    mysqli_select_db($dbLink,$dbname) or die('Erreurdanslasélectiondelabase:'.mysqli_error($dbLink));

    if ($action == 'poster'){
        if(!empty($_FILES["image"]["tmp_name"])){
            if (is_uploaded_file($_FILES["image"]["tmp_name"]) && rename($_FILES["image"]["tmp_name"], $repertoireDestination.$nomDestination)) {
                $query = 'INSERT INTO post (titre, date, image, message, tags) VALUES (\'' . $titre . '\', \'' . date('Y-m-d h:i:s') . '\', \'' . $nomDestination . '\', \'' . $message . '\', \'' . $tabTags . '\')';
                if(!($dbResult = mysqli_query($dbLink, $query))){
                    session_start();
                    $_SESSION['file'] = 'error';
                    header('location: post.php');
                }
                session_start();
                $_SESSION['file'] = 'done';
                header('location: index.php');
            }
            else {
                session_start();
                $_SESSION['file'] = 'error';
                header('location: post.php');
            }
        }
        else{
            $query = 'INSERT INTO post (titre, date, message, tags) VALUES (\'' . $titre . '\', \'' . date('Y-m-d h:i:s') . '\', \'' . $message . '\', \'' . $tabTags . '\')';
            if(!($dbResult = mysqli_query($dbLink, $query))){
                echo 'Erreur de requête<br/>';
                // Affiche le type d'erreur.
                echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
                // Affiche la requête envoyée.
                echo 'Requête : ' . $query . '<br/>';
                exit();
            }
            session_start();
            $_SESSION['file'] = 'done';
            header('location: index.php');
        }
    }