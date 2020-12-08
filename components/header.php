<div style="position:fixed;z-index:50;bottom:0;">
    <?php require(__DIR__ . '/session-handler.php');
    require(__DIR__ . "/stockage-handler.php");
    require(__DIR__ . "/product.php");
    ?>
</div>

<header class="flexRBetween alignICenter">

    <img src="./ressources/images/logo.png" id="logo" alt="" class="marginAll">
    <nav>
        <ul class="flexRCenter">
            <a href="?" class="btn">
                <li>Home</li>
            </a>
            <a href="?pages=articles" class="btn">
                <li>Articles</li>
            </a>
            <a href="?pages=checkout" class="btn">
                <li>Checkout</li>
            </a>
            <a href="?pages=admin" class="btn">
                <li>Admin</li>
            </a>
        </ul>
    </nav>

    <div class="paddingX">
        <div class="flexCBetween alignICenter">
            <p style="font-size:1rem">Article dans le panier : <?php echo (count(SessionH::$mySession->getFromSession("Panier"))); ?></p>
            <button class="paddingX" id="status">Vider le Panier</button>
        </div>
    </div>

    <script>
        const b = document.getElementById("status");
        b.addEventListener("click", () => {
            f("disconnect");
        });
    </script>

</header>
