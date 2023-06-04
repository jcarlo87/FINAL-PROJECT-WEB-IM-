<nav id="navbar">
    <div class="logo-div">
        <span class="logo-text"><a class="#">URBAN THREAD</a></span>
    </div>
    <div class="nav-btn-div">
        <div class="nav-btn">
            <span class="material-symbols-outlined"> <a href="home.php">home</a></span>
        </div>
        <div class="nav-btn">
            <span class="material-symbols-outlined"><a href="index.php">shopping_bag</a></span>
        </div>
        <div class="nav-btn nav-cart-wrapper">
            <span class="cart-count material-symbols-outlined"><a href="cart.php">shopping_cart</a></span>

            <?php
            $customer_id = $_SESSION['customer_id'];
            $select_cart_number = mysqli_query($conn, "SELECT * FROM carts WHERE customer_id = '$customer_id'") or die('query failed');
            $cart_rows_number = mysqli_num_rows($select_cart_number);
            ?>
            <span class="quantity-carted-item">
                <?php echo $cart_rows_number ?>
            </span>
        </div>
        <div class="nav-btn">
            <span class="material-symbols-outlined"><a href="account.php">person</a></span>
        </div>
    </div>
</nav>