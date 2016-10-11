<?php
//Authorization
if (PGRFileManagerConfig::$authorize) {
    session_start();
    if (isset($_POST) && isset($_POST['logoff'])) {
        unset($_SESSION['PGRFileManagerAuthorized']);
        include_once dirname(__FILE__) . '/utils.php';
        header('Location:' . PGRFileManagerUtils::curPageURL());
        die();
    }
    if (!isset($_SESSION['PGRFileManagerAuthorized'])) {
        if (isset($_POST) && isset($_POST['user']) && isset($_POST['pass']) &&
            ($_POST['user'] == PGRFileManagerConfig::$authorizeUser) &&
            ($_POST['pass'] == PGRFileManagerConfig::$authorizePass)) {
            $_SESSION['PGRFileManagerAuthorized'] = true;
            include_once dirname(__FILE__) . '/utils.php';
            header('Location:' . PGRFileManagerUtils::curPageURL());
            die();
        } else {
            include_once dirname(__FILE__) . '/login.php';
            die();            
        }
    }
}