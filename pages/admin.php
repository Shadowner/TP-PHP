<main>

    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (isset($_GET['action'])) {

        switch ($_GET['action']) {
            case 'edit':
                if (isset($_GET['id'])) {
                    $product = Product::getProduct($_GET['id']);
                    if ($product) {
                        if (isset($_POST['submit'])) {
                            editProduct();
                        } else {
                            displayForm($product->title, $product->description, $product->price, $product->stock);
                        }
                    } else {
                        echo "<h1>Sorry but the provided id is not an existing product</h1>";
    ?>
                        <form action="#" method="get">
                            Page id to edit
                            <input type="hidden" name="pages" value="admin">
                            <input type="hidden" name="action" value="edit">
                            <input type="number" name="id">
                            <input type="submit" value="Edit">
                        </form>
                <?php
                    }
                }
                break;
            case 'add':
                if (isset($_POST['submit'])) {
                    addProduct();
                } else {
                    displayForm("", "", 0, 0);
                }
                break;
            default:
                ?>
                <form action="#" method="get">
                    Page id to edit
                    <input type="hidden" name="pages" value="admin">
                    <input type="hidden" name="action" value="edit">
                    <input type="number" name="id">
                    <input type="submit" value="Edit">
                </form>
                <hr>
                <form action="#" method="get">
                    Add a new product
                    <input type="hidden" name="pages" value="admin">
                    <input type="hidden" name="action" value="add">
                    <input type="submit" value="Add">
                </form>
        <?php

                break;
        }
    } else {
        ?>
        <form action="#" method="get">
            Page id to edit
            <input type="hidden" name="pages" value="admin">
            <input type="hidden" name="action" value="edit">
            <input type="number" name="id">
            <input type="submit" value="Edit">
        </form>
        <hr>
        <form action="#" method="get">
            Add a new product
            <input type="hidden" name="pages" value="admin">
            <input type="hidden" name="action" value="add">
            <input type="submit" value="Add">
        </form>
        <?php
    }



    function addProduct()
    {
        $sanitizedArgs = sanitizeArgs($_POST);
        if ($sanitizedArgs && $_GET['action'] == "add" && isset($_POST["submit"])) {

            $productsDB = $GLOBALS["db_prefix"] . "PRODUCTS";
            $imageDB = $GLOBALS["db_prefix"] . "IMAGES";
            $GLOBALS["dbh"]->sendQuery("INSERT INTO $productsDB (`NAME_PRODUCT`, `DESCRIPTION_PRODUCT`, `PRICE_PRODUCT`, `STOCK_PRODUCT`) VALUES (?, ?, ?, ?)", array($sanitizedArgs['product_name'], $sanitizedArgs['product_description'], $sanitizedArgs['product_price'], $sanitizedArgs['product_stock']));

            $product = $GLOBALS["dbh"]->sendQuery("SELECT LAST_INSERT_ID() as ID_PRODUCT");
            /*
                Put image in the public/images folder. Image's name is the same as is databse id
            */
            if ($_FILES['product_images']['error'][0] == 0) {
                $images = reArrayFiles($_FILES['product_images']);
                foreach ($images as $image) {
                    $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

                    //Extension test

                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        echo $image['name'] . " n'est pas dans le bon format seul JPG, JPEG, PNG & GIF sont authorisés.";
                    } else {
                        $GLOBALS["dbh"]->sendQuery("INSERT INTO $imageDB (ID_PRODUCT, EXTENSION_IMAGE) VALUES (?, ?); SELECT LAST_INSERT_ID() as ID_IMAGE", [$product[0]['ID_PRODUCT'], $imageFileType]);
                        $result = $GLOBALS["dbh"]->sendQuery("SELECT LAST_INSERT_ID() as ID_IMAGE");
                        if (move_uploaded_file($image['tmp_name'], __DIR__ . "/../public/images/" . $result[0]['ID_IMAGE'] . "." . $imageFileType)) {
                        } else {
                            echo $image['name'] . ' cannot be moved';
                        }
                    }
                }
            }
        } else {
            echo "Provided arguments are not valid";
        }
    }

    function editProduct()
    {
        $sanitizedArgs = sanitizeArgs($_POST);
        if ($sanitizedArgs && isset($_GET['id']) && filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
            if ($_GET['action'] == "edit" && isset($_POST["submit"])) {

                $GLOBALS["dbh"]->sendQuery("UPDATE " . $GLOBALS['db_prefix'] . "PRODUCTS
                SET NAME_PRODUCT = '" . $sanitizedArgs['product_name'] . "', DESCRIPTION_PRODUCT= '" . $sanitizedArgs['product_description'] . "', PRICE_PRODUCT = " . $sanitizedArgs['product_price'] . ", STOCK_PRODUCT = " . $sanitizedArgs['product_stock'] . "
                WHERE ID_PRODUCT = " . $_GET['id']);
                echo "<h1>Product has been updated</h1>";
        ?>
                <form action="#" method="get">
                    Page id to edit
                    <input type="hidden" name="pages" value="admin">
                    <input type="hidden" name="action" value="edit">
                    <input type="number" name="id">
                    <input type="submit" value="Edit">
                </form>
                <hr>
                <form action="#" method="get">
                    Add a new product
                    <input type="hidden" name="pages" value="admin">
                    <input type="hidden" name="action" value="add">
                    <input type="submit" value="Add">
                </form>
        <?php
            }
        } else {
            echo "Provided arguments are not valid";
        }
    }

    function reArrayFiles(&$file_post)
    {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }


    /*function validateArgs($args, $fields):bool {
        $areAllArgs = true;
        foreach ($fields as $field) {
            $areAllArgs = $areAllArgs && array_key_exists($field[0], $args) && filter_input($field[0], $field[1]) ;
        }
        return $areAllArgs;
    }*/

    function sanitizeArgs($args)
    {
        $out = [];
        $filterArgs = array(
            'product_name' => FILTER_SANITIZE_STRING,
            'product_description' => FILTER_SANITIZE_STRING,
            'product_price' => FILTER_VALIDATE_FLOAT,
            'product_stock' => FILTER_VALIDATE_INT
        );
        foreach ($filterArgs as $key => $value) {
            $tmp = filter_var($args[$key], $value);
            if (!$tmp) {
                return false;
            } else {
                $out[$key] = $tmp;
            }
        }
        return $out;
    }


    function displayForm($name, $desc, $price, $stock)
    { ?>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="product_name">Nom :</label>
            <input type="text" name="product_name" id="product_name" required value="<?php echo $name ?>">
            <label for="product_description">Description :</label>
            <textarea name="product_description" id="product_description" cols="30" rows="10" required><?php echo $desc ?></textarea>
            <label for="product_price">Prix :</label>
            <input type="number" name="product_price" id="product_price" step="0.01" required value="<?php echo $price ?>">
            <label for="product_stock">Stock :</label>
            <input type="number" name="product_stock" id="product_stock" required value="<?php echo $stock ?>">
            Select image to upload:
            <input type="file" name="product_images[]" multiple>
            <input type="submit" value="Submit" name="submit">
        </form>
    <?php
    }
    ?>
</main>