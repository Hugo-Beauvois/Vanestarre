<?php
    session_start();
    unset($_SESSION['session']);
    unset($_SESSION['sessionName']);
    header('location: index.php');