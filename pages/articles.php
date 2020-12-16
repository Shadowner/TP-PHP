<?php
if (isset($_GET["id"])) {

    $product = Product::getProduct($_GET["id"]);
    if (!$product) return require(__DIR__ . "/home.php");
?>
    <main class="flexRAround marginAll">
        <div class="flexCCenter alignICenter">
            <?php
            foreach ($product->getProductImages() as $image) { ?>
                <img src="<?php echo $image ?>" alt="">
            <?php
            }
            ?>

            <h1><?php echo $product->title ?></h1>
        </div>

        <div class="marginAll flexCCenter">
            <h2>Mon magnifique prix est de : <b><?php echo $product->price ?>€TTC</b></h2>
            <p><?php echo $product->description ?></p>
            <?php
            if (SessionH::$mySession->sessionStarted) {
            ?>
                <?php if (in_array($product->id, SessionH::$mySession->getFromSession("Panier"))) {
                ?>
                    <button onclick="f('remove',<?php echo $product->id ?> )">M'enlever du panier</button>
                <?php
                } else {
                ?>
                    <button onclick="f('add',<?php echo $product->id ?>)">M'ajouter au panier</button>
                <?php } ?>
            <?php } else { ?>
                <button disabled>Merci de vous connectez pour ajouter des produits au panier</button>
            <?php } ?>
        </div>
    </main>

<?php

} else {
    require(__DIR__ . "/home.php");
}
?>