<div style="padding-top:8rem; padding-left:1rem;"></div>
<main class="paddingX">
    <section class="checkout_header">

        <h1>MON MAGNIFIQUE PANIER Dont le montant s'élève à : <span id="total"></span></h1>

    </section>

    <section class=checkout_main>
        <ul class="flexRCenter products">
            <?php

            $products = SessionH::$mySession->getFromSession("Panier");


            if (!$products) {
                echo "<h2> Mais pourquoi vous n'avez rien dans votre magnifique panier ? :C";
                return;
            }
            $total = 0;
            foreach ($products as $key => $product) { ?>
                <?php $product = Product::getProduct($product);

                $total += $product->price;
                ?>
                <a href="?pages=articles&articles=<?php echo $product->id ?>">
                    <li class=" product flexCCenter alignICenter">
                        <img src="<?php echo $product->images ? $product->images : "https://media.cultura.com/media/wysiwyg/VITRINES/2020/01_OP_CO/09_noel/Livre/W19/W19_liseuse.jpg"; ?>" alt="">
                        <div>
                            <h3><?php echo $product->price ?>€</h3>
                            <h4><?php echo $product->title ?></h4>
                        </div>
                    </li>
                </a>
            <?php
            }
            ?>
        </ul>
    </section>
</main>
<script>
    document.getElementById("total").innerHTML = <?php echo ($total); ?> + "€"
</script>