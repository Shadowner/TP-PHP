<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./ressources/styles/style.css">
    <title>Magnifique site d'E-Commerce</title>
</head>

<body class="flexCBetween">

    <?php
    require(__DIR__ . '/components/header.php');
    ?>


    <?php

    if (array_key_exists("pages", $_GET)) {
        switch ($_GET["pages"]) {
            case "checkout":
                require(__DIR__ . "/pages/checkout.php");
                break;
            case "articles":
                require(__DIR__ . "/pages/articles.php");
                break;
            case "admin":
                require(__DIR__ . "/pages/admin.php");
                break;
            default:
                require(__DIR__ . "/pages/home.php");
                break;
        }
    } else {
        require(__DIR__ . "/pages/home.php");
    }

    ?>
    <div id="debug"></div>
    <script>
        function f(type, productID = null) {

            const a = document.getElementById("debug");
            let xhr = new XMLHttpRequest();
            xhr.open("POST", `./actions.php?type=${type}`, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(productID ? `id=${productID}` : "");
            xhr.onloadend = () => {
                // console.log(xhr.responseText)
                // a.innerHTML += xhr.responseText
                document.location.reload();
            }
        }
    </script>
</body>

</html>