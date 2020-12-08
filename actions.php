<?php 
require(__DIR__ . '/components/product.php');
require(__DIR__ . '/components/session-handler.php');
require(__DIR__ . '/components/stockage-handler.php');
var_dump(session_status());


if (isset($_GET["type"])) {
    switch ($_GET["type"]) {
        case 'add':
            if (!isset($_POST["id"]) or !Product::getProduct($_POST["id"])) return;
            var_dump($_POST["id"]);
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
            var_dump(SessionH::$mySession->sessionID);
            var_dump(SessionH::$mySession->sessionStarted);
            SessionH::$mySession->endSession();
            var_dump(SessionH::$mySession->sessionID);
            var_dump(SessionH::$mySession->sessionStarted);
            return;
            break;

        default:
            echo ("On as un problème chef !");
            break;
    }
}

?>
<!-- cft7i6b99flnchp1avqep8o7do -->