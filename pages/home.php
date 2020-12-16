<div style="padding-top:8rem; padding-left:1rem;"></div>
<main>
    <section class="home_header">

        <h1>Bienvenue sur ce site approximatif</h1>

    </section>
    <section class="product_holder">

        <ul class="flexRCenter products">
            <?php
            $products = Product::getEveryProducts();
            foreach ($products as $key => $product) { ?>
                <a href="?pages=articles&id=<?php echo $product->id ?>">
                    <li class=" product flexCCenter alignICenter">
                        <img src="<?php echo $product->getProductImages()[0]; ?>" class="paddingAll" alt="">
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

        <!-- https://media.cultura.com/media/wysiwyg/VITRINES/2020/01_OP_CO/09_noel/Livre/W19/W19_liseuse.jpg -->

    </section>
</main>