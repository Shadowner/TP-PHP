<?php 
require(__DIR__ . '/components/product.php');
require(__DIR__ . '/components/session-handler.php');
require(__DIR__ . '/components/stockage-handler.php');


if (isset($_GET["type"])) {
    switch ($_GET["type"]) {
        case 'add':
            if (!isset($_POST["id"]) or !Product::getProduct($_POST["id"])) return;
            SessionH::$mySession->addToSession("Panier", $_POST["id"]);
            return;
            break;
        case 'remove':
            if (!isset($_POST["id"]) or !Product::getProduct($_POST["id"])) return;
            SessionH::$mySession->removeFromSession("Panier", $_POST["id"]);
            return;
            break;


        case 'disconnect':
            if(!SessionH::$mySession->sessionStarted)return;
            SessionH::$mySession->endSession();
            return;
            break;

        default:
            echo ("L'action n'existe pas!");
            break;
    }
}

?>