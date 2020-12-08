<div style="padding-top:8rem; padding-left:1rem;"></div>

<main>
    <?php
    if (isset($_GET['action'])) {

        switch ($_GET['action']) {
            case 'edit':
                if (isset($_GET['id'])) {
                    $product = Product::getProduct($_GET['id']);
                    if (!$product) {
                        echo ("<h1>Le produit n'existe pas, par extension ne peut pas être modifié</h1>");
                        exit();
                    }
    ?>


                    <form action="#" method="post" enctype="multipart/form-data">
                        <label for="product_name">Nom :</label>
                        <input type="text" name="product_name" id="product_name" required value="<?php echo $product->title ?>">
                        <label for="product_description">Description :</label>
                        <textarea name="product_description" id="product_description" cols="30" rows="10" required><?php echo $product->description ?></textarea>
                        <label for="product_price">Prix :</label>
                        <input type="number" name="product_price" id="product_price" required value="<?php echo $product->price ?>">
                        <label for="product_stock">Stock :</label>
                        <input type="number" name="product_stock" id="product_stock" required value="<?php echo $product->stock ?>">
                        Select image to upload:
                        <input type="file" name="product_images[]" multiple>
                        <input type="submit" value="Upload Image" name="submit">
                    </form>

                <?php

                }

                break;
            case 'add':

                ?>

                <form action="#" method="post" enctype="multipart/form-data">
                    <label for="product_name">Nom :</label>
                    <input type="text" name="product_name" id="product_name" required>
                    <label for="product_description">Description :</label>
                    <textarea name="product_description" id="product_description" cols="30" rows="10" required></textarea>
                    <label for="product_price">Prix :</label>
                    <input type="number" name="product_price" id="product_price" required>
                    <label for="product_stock">Stock :</label>
                    <input type="number" name="product_stock" id="product_stock" required>
                    Select image to upload:
                    <input type="file" name="product_images[]" multiple>
                    <input type="submit" value="Upload Image" name="submit">
                </form>

    <?php

                break;
            default:
                # code...
                break;
        }
    }

    if ($_GET['action'] == "add" && isset($_POST["submit"])) {

        $images = reArrayFiles($_FILES['product_images']);
        $dbh->sendQuerry("INSERT INTO " . $dbh->prefix . "products (`NAME_PRODUCT`, `DESCRIPTION_PRODUCT`, `PRICE_PRODUCT`, `STOCK_PRODUCT`) VALUES ('" . $_POST['product_name'] . "','" . $_POST['product_description'] . "'," . $_POST['product_price'] . "," . $_POST['product_stock'] . ")   ");

        $product = $dbh->sendQuerry("SELECT LAST_INSERT_ID() as ID_PRODUCT");
        foreach ($images as $image) {
            $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo $image['name'] . " n'est pas dans le bon format seul JPG, JPEG, PNG & GIF sont alloué.";
            } else {
                $dbh->sendQuerry("INSERT INTO " . $dbh->prefix . "images (ID_PRODUCT, EXTENSION_IMAGE) VALUES (" . $product[0]['ID_PRODUCT'] . ", '" . $imageFileType . "'); SELECT LAST_INSERT_ID() as ID_IMAGE");
                $result = $dbh->sendQuerry("SELECT LAST_INSERT_ID() as ID_IMAGE");
                if (move_uploaded_file($image['tmp_name'], __DIR__ . "/../public/images/" . $result[0]['ID_IMAGE'] . "." . $imageFileType)) {
                    echo 'File succesfully uploaded';
                } else {
                    echo 'File cannot be moved';
                }
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

    ?>
</main>