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
        $fields = ['product_name', 'product_description', 'product_price', 'product_stock'];
        $areAllParameters = true;
        foreach ($fields as $field) {
            $areAllParameters = $areAllParameters && array_key_exists($field, $_POST);
        }

        if ($areAllParameters) {

            if ($_GET['action'] == "add" && isset($_POST["submit"])) {

                $images = reArrayFiles($_FILES['product_images']);
                $GLOBALS["dbh"]->sendQuerry("INSERT INTO " . $GLOBALS["db_prefix"] . "PRODUCTS (`NAME_PRODUCT`, `DESCRIPTION_PRODUCT`, `PRICE_PRODUCT`, `STOCK_PRODUCT`) VALUES ('" . $_POST['product_name'] . "','" . $_POST['product_description'] . "'," . $_POST['product_price'] . "," . $_POST['product_stock'] . ")   ");

                $product = $GLOBALS["dbh"]->sendQuerry("SELECT LAST_INSERT_ID() as ID_PRODUCT");
                /*
                Put image in the public/images folder. Image's name is the same as is databse id
            */
                foreach ($images as $image) {
                    $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

                    //Extension test

                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        echo $image['name'] . " n'est pas dans le bon format seul JPG, JPEG, PNG & GIF sont alloué.";
                    } else {
                        $GLOBALS["dbh"]->sendQuerry("INSERT INTO " . $GLOBALS["db_prefix"] . "IMAGES (ID_PRODUCT, EXTENSION_IMAGE) VALUES (" . $product[0]['ID_PRODUCT'] . ", '" . $imageFileType . "'); SELECT LAST_INSERT_ID() as ID_IMAGE");
                        $result = $GLOBALS["dbh"]->sendQuerry("SELECT LAST_INSERT_ID() as ID_IMAGE");
                        if (move_uploaded_file($image['tmp_name'], __DIR__ . "/../public/images/" . $result[0]['ID_IMAGE'] . "." . $imageFileType)) {
                        } else {
                            echo $image['name'] . ' cannot be moved';
                        }
                    }
                }
            }
        } else {
            echo "<h1>Sorry but you have not provided all the required arguments</h1>";
        }
    }

    function editProduct()
    {
        $fields = ['product_name', 'product_description', 'product_price', 'product_stock'];
        $areAllParameters = true;
        foreach ($fields as $field) {
            $areAllParameters = $areAllParameters && array_key_exists($field, $_POST);
        }

        if ($areAllParameters && isset($_GET['id'])) {
            if ($_GET['action'] == "edit" && isset($_POST["submit"])) {

                $GLOBALS["dbh"]->sendQuerry("UPDATE " . $GLOBALS['db_prefix'] . "PRODUCTS
                SET NAME_PRODUCT = '" . $_POST['product_name'] . "', DESCRIPTION_PRODUCT= '" . $_POST['product_description'] . "', PRICE_PRODUCT = " . $_POST['product_price'] . ", STOCK_PRODUCT = " . $_POST['product_stock'] . "
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




    function displayForm($name, $desc, $price, $stock)
    { ?>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="product_name">Nom :</label>
            <input type="text" name="product_name" id="product_name" required value="<?php echo $name ?>">
            <label for="product_description">Description :</label>
            <textarea name="product_description" id="product_description" cols="30" rows="10" required><?php echo $desc ?></textarea>
            <label for="product_price">Prix :</label>
            <input type="number" name="product_price" id="product_price" required value="<?php echo $price ?>">
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